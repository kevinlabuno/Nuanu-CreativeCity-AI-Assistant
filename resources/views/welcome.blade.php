<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuanu Creative City</title>
    <link rel="icon" href="assets/icon/dark-icon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="{{asset('css/welcome.css')}}">
    <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-firestore-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-database-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-auth-compat.js"></script>
    </head>
    <body>
    <div class="overlay"></div>
    <div class="loading-overlay">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <p>Please wait while we prepare your tour experience</p>
        </div>
    </div>
    <div class="container">
        <div class="logo">
            <img src="assets/icon/dark-icon.svg" alt="Nuanu Logo">
        </div>
        <div class="subtitle">Creative City</div>
        <div class="title" id="animated-title"></div>
        <div class="desc" id="animated-desc"></div>
        
        @if(Cookie::has('guest_id'))
            <div class="alert alert-info" style="margin: 1rem 0; padding: 1rem; background: #e3f2fd; border-radius: 4px;">
                <h4 style="margin-bottom: 0.5rem;">Welcome back!</h4>
                <p style="margin: 0.25rem 0;">Your Guest ID: <strong>{{ Cookie::get('guest_id') }}</strong></p>
                <form method="POST" action="{{ route('clear.cookie') }}" style="margin-top: 0.5rem;">
                    @csrf
                    <button type="submit" style="background: #ff4444; color: white; border: none; padding: 0.25rem 0.5rem; border-radius: 4px; cursor: pointer;">Clear Data</button>
                </form>
            </div>
        @endif

        <form method="POST" action="{{ route('guests.store') }}">
            @csrf
            <div class="input-group">
                <input type="text" name="fullname" placeholder="Full Name" required autocomplete="off">
            </div>
            <button class="btn" type="submit">Next</button>
        </form>
        
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const loadingOverlay = document.querySelector('.loading-overlay');
            
            form.addEventListener('submit', function(e) {
                loadingOverlay.style.display = 'flex';
            });
        });
    </script>
    <script src="{{asset('js/welcome.js')}}"></script>
</body>
</html>