<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Nuanu City Tour</title>
    <link rel="stylesheet" href="{{ asset('css/general-card.css') }}">
    <link rel="icon" href="{{ asset('assets/icon/dark-icon.svg') }}" type="image/svg+xml">
</head>
<body>
    <div class="transition-overlay"></div>
    <div class="general-bg bg-fade" id="background-image" data-background="{{ $backgroundImage }}"></div>
    <section class="general-section">
        <div class="general-card new-layout">
            <div class="general-logo-topright">
                <img src="{{ asset('assets/icon/dark-icon.svg') }}" alt="Logo">
            </div>

            <div class="general-image">
                <img src="{{ asset('assets/img/'.($initialData['image'] ?? 'placeholder.jpg')) }}" alt="Card Image" id="card-image">
            </div>
            
            <div class="general-content" id="card-content">
                <h2 class="general-title" id="card-title">{{ $initialData['title'] ?? 'Title' }}</h2>
                <div class="general-desc-card">
                    <strong id="card-subtitle">{{ $initialData['concept'] ?? 'Subtitle' }}</strong>
                    <br>
                    <br>
                    <span id="card-description">"{{ $initialData['description'] ?? 'Description goes here.' }}"</span>
                </div>
                <button class="general-btn" id="next-btn">
                    Next ({{ $currentIndex + 1 }}/{{ $totalItems }})
                </button>
            </div>

            <div class="ai-chat-card">
                <div class="ai-chat-messages" id="ai-chat-messages">
                    <div class="ai-message ai-message-bot">
                        <span class="ai-icon">
                            <img src="{{ asset('assets/icon/ai2.gif') }}" alt="Bot">
                        </span>
                        <span>Hi <b>{{ $guestName }}</b>, I'm your AI Assistant for Nuanu City Tour.</span>
                    </div>
                </div>
                
                <div class="quick-questions" id="quick-questions"></div>
                
                <div class="ai-chat-input-group">
                    <input class="ai-chat-input" type="text" id="ai-input" 
                        placeholder="Ask about {{ $initialData['title'] ?? 'this' }} to AI"
                        autocomplete="off">
                    <button class="ai-chat-send" id="ai-send-btn">
                        <img src="{{ asset('assets/icon/sent.svg') }}" alt="Send">
                    </button>
                </div>
            </div>
        </div>
    </section>

    <script>
        window.generalCardData = {
            currentIndex: {{ $currentIndex }},
            totalItems: {{ $totalItems }},
            apiUrl: "{{ route('tour.data') }}",
            currentLocation: "{{ $initialData['title'] ?? 'this location' }}"
        };

        window.initialGreeting = `
            <div class="ai-message ai-message-bot">
                <span class="ai-icon">
                    <img src="{{ asset('assets/icon/ai2.gif') }}" alt="Bot">
                </span>
                <span>Hi <b>{{ $guestName }}</b>, I'm your AI Assistant for Nuanu City Tour. How can I help you?</span>
            </div>
        `;

        window.initialData = @json($initialData ?? []);

        // Helper function to get cookie value
        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
        }

        // Function to save user question to Firebase
        function saveQuestionToFirebase(guestId, question, location) {
    const timestamp = new Date().toISOString();
    const fullname = "{{ $guestName }}";
    
    fetch('/save-chat', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            guest_id: guestId,
            fullname: fullname,
            type: 'question',
            content: question,
            location: location,
            timestamp: timestamp
        })
    })
    .catch(error => console.error('Error saving question:', error));
}

        // Function to save AI response to Firebase
        function saveResponseToFirebase(guestId, response, location) {
    const timestamp = new Date().toISOString();
    const fullname = "{{ $guestName }}";
    
    fetch('/save-chat', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            guest_id: guestId,
            fullname: fullname,
            type: 'response',
            content: response,
            location: location,
            timestamp: timestamp
        })
    })
    .catch(error => console.error('Error saving response:', error));
}
    </script>
   
    <script src="{{ asset('js/general-card.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const overlay = document.querySelector('.transition-overlay');
            const section = document.querySelector('.general-section');
            setTimeout(() => {
                overlay.classList.add('fade-out');
                section.classList.add('fade-in');
            }, 100);
            setTimeout(() => {
                overlay.remove();
            }, 600);
        });
    </script>
</body>
</html>