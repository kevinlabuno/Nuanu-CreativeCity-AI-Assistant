let currentIndex = window.generalCardData.currentIndex;
const totalItems = window.generalCardData.totalItems;
const apiUrl = window.generalCardData.apiUrl;
const background = document.getElementById('background-image');

// Function to rewrite description with animation
function rewriteDescription(newDescription) {
    const descriptionElement = document.getElementById('card-description');
    descriptionElement.classList.add('fade-out');
    
    setTimeout(() => {
        descriptionElement.textContent = `"${newDescription}"`;
        descriptionElement.classList.remove('fade-out');
        descriptionElement.classList.add('fade-in');
        
        setTimeout(() => {
            descriptionElement.classList.remove('fade-in');
        }, 500);
    }, 500);
}

// Set initial background image
window.addEventListener('DOMContentLoaded', function() {
    // Get the initial background image from the data attribute
    const initialBgImage = document.getElementById('background-image').getAttribute('data-background');
    if (initialBgImage) {
        background.style.backgroundImage = `url('${initialBgImage}')`;
    }
});

// Ensure DOM is loaded
window.addEventListener('DOMContentLoaded', function() {
    document.getElementById('next-btn').addEventListener('click', async function() {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = `Loading...`;
        document.getElementById('card-image').classList.add('fade-out');
        document.getElementById('card-content').classList.add('fade-out');

        try {
            const response = await fetch(`${apiUrl}?index=${currentIndex + 1}`);
            const data = await response.json();
            currentIndex = data.currentIndex;
            const bgImg = new Image();
            bgImg.src = data.background_image;
            bgImg.onload = function() {
                background.style.backgroundImage = `url('${data.background_image}')`;
                setTimeout(() => {
                    document.getElementById('card-image').src = '/assets/img/' + data.data.image;
                    document.getElementById('card-title').textContent = data.data.title;
                    document.getElementById('card-subtitle').textContent = data.data.concept;
                    rewriteDescription(data.data.description);
                    document.getElementById('ai-input').placeholder = 'Ask about ' + data.data.title + ' to AI';
                    btn.innerHTML = `Next (${currentIndex + 1}/${totalItems})`;

                    // Start fade in animation
                    document.getElementById('card-image').classList.remove('fade-out');
                    document.getElementById('card-content').classList.remove('fade-out');
                    document.getElementById('card-image').classList.add('fade-in');
                    document.getElementById('card-content').classList.add('fade-in');

                    // Remove fade-in class after animation completes
                    setTimeout(() => {
                        document.getElementById('card-image').classList.remove('fade-in');
                        document.getElementById('card-content').classList.remove('fade-in');
                        btn.disabled = false;
                    }, 500);
                }, 500);
            };

            // Fallback in case image takes too long to load
            setTimeout(() => {
                if (btn.disabled) {
                    background.style.backgroundImage = `url('${data.background_image}')`;
                    document.getElementById('card-image').src = '/assets/img/' + data.data.image;
                    document.getElementById('card-title').textContent = data.data.title;
                    document.getElementById('card-subtitle').textContent = data.data.concept;
                    rewriteDescription(data.data.description);
                    document.getElementById('ai-input').placeholder = 'Ask about ' + data.data.title + ' to AI';
                    btn.innerHTML = `Next (${currentIndex + 1}/${totalItems})`;

                    document.getElementById('card-image').classList.remove('fade-out');
                    document.getElementById('card-content').classList.remove('fade-out');
                    document.getElementById('card-image').classList.add('fade-in');
                    document.getElementById('card-content').classList.add('fade-in');

                    setTimeout(() => {
                        document.getElementById('card-image').classList.remove('fade-in');
                        document.getElementById('card-content').classList.remove('fade-in');
                        btn.disabled = false;
                    }, 500);
                }
            }, 2000);

        } catch (error) {
            console.error('Error fetching data:', error);
            document.getElementById('card-image').classList.remove('fade-out');
            document.getElementById('card-content').classList.remove('fade-out');
            btn.disabled = false;
            btn.innerHTML = `Next (${currentIndex + 1}/${totalItems})`;
        }
    });
});

// AI Chat functionality
function generateDynamicQuestions(title, description) {
    const baseQuestions = [
        { text: "Explain more", question: `Explain more about ${title}` },
        { text: "Opening hours", question: `What are the opening hours for ${title}?` },
        { text: "Activities", question: `What can I do at ${title}?` }
    ];
    if (description.toLowerCase().includes('capacity')) {
        baseQuestions.push({ 
            text: "Capacity", 
            question: `What is the capacity of ${title}?` 
        });
    }
    if (description.toLowerCase().includes('event')) {
        baseQuestions.push({ 
            text: "Event types", 
            question: `What kind of events are held at ${title}?` 
        });
    }
    if (description.toLowerCase().includes('food') || 
        description.toLowerCase().includes('café') || 
        description.toLowerCase().includes('cuisine')) {
        baseQuestions.push({ 
            text: "Food options", 
            question: `What food options are available at ${title}?` 
        });
    }
    
    return baseQuestions;
}

function updateQuickQuestions() {
    const currentTitle = document.getElementById('card-title').textContent;
    const currentDescription = document.getElementById('card-description').textContent;
    const quickQuestions = document.getElementById('quick-questions');
    const dynamicQuestions = generateDynamicQuestions(currentTitle, currentDescription);
    quickQuestions.innerHTML = '';
    dynamicQuestions.forEach(q => {
        const button = document.createElement('button');
        button.className = 'quick-question-btn';
        button.textContent = q.text;
        button.setAttribute('data-question', q.question);
        quickQuestions.appendChild(button);
    });
}

function updateAIContext() {
    const currentSectionData = {
        title: document.getElementById('card-title').textContent,
        description: document.getElementById('card-description').textContent.replace(/"/g, ''),
        image: document.getElementById('card-image').src.split('/').pop()
    };
    window.generalCardData.currentLocation = currentSectionData.title;
    const inputField = document.getElementById('ai-input');
    inputField.placeholder = `Ask about ${currentSectionData.title} to AI`;
    inputField.focus();
    const chatMessages = document.getElementById('ai-chat-messages');
    const existingUpdates = document.querySelectorAll('.ai-context-update');
    existingUpdates.forEach(update => update.remove());
    const contextMessage = document.createElement('div');
    contextMessage.className = 'ai-message ai-message-bot ai-context-update';
    contextMessage.innerHTML = `
        <span class="ai-icon">
            <img src="/assets/icon/ai2.gif" alt="Bot">
        </span>
        <span>Now showing information about <b>${currentSectionData.title}</b>. ${currentSectionData.concept ? currentSectionData.concept + '.' : ''} Feel free to ask me anything about it!</span>
    `;
    
    if (chatMessages.children.length === 0) {
        chatMessages.innerHTML = window.initialGreeting;
    }
    chatMessages.appendChild(contextMessage);
    chatMessages.scrollTop = chatMessages.scrollHeight;
    updateQuickQuestions();
}

function addMessage(message, isUser) {
    const chatMessages = document.getElementById('ai-chat-messages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `ai-message ai-message-${isUser ? 'user' : 'bot'}`;
    
    if (isUser) {
        messageDiv.innerHTML = `
            <span>${message}</span>
            <span class="ai-icon">
                <img src="/assets/icon/Users.svg" alt="User">
            </span>
        `;
    } else {
        messageDiv.innerHTML = `
            <span class="ai-icon">
                <img src="/assets/icon/ai2.gif" alt="Bot">
            </span>
            <span>${message}</span>
        `;
    }
    
    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function sendMessage() {
    const inputField = document.getElementById('ai-input');
    const message = inputField.value.trim();
    if (!message) return;
    
    addMessage(message, true);
    inputField.value = '';

    const currentSectionData = {
        title: document.getElementById('card-title').textContent,
        concept: document.getElementById('card-subtitle').textContent,
        description: document.getElementById('card-description').textContent.replace(/"/g, ''),
        image: document.getElementById('card-image').src.split('/').pop()
    };

    // Get guest ID from cookie
    const guestId = getCookie('guest_id');
    
    // Save the user question to Firebase
    if (guestId) {
        saveQuestionToFirebase(guestId, message, currentSectionData.title);
    }

    fetch('/ai-chat/send', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            message: message,
            context: JSON.stringify({
                currentSection: currentSectionData,
                initialData: window.initialData || []
            })
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            addMessage(data.message, false);
            // Save the AI response to Firebase if guest ID exists
            if (guestId) {
                saveResponseToFirebase(guestId, data.message, currentSectionData.title);
            }
        } else {
            addMessage("Sorry, an error occurred. Please try again.", false);
        }
    })
    .catch(error => {
        addMessage("Connection error. Please try again later.", false);
    });
}

// Initialize AI Chat functionality
document.addEventListener('DOMContentLoaded', function() {
    const contentObserver = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList' || mutation.type === 'characterData') {
                updateAIContext();
            }
        });
    });

    const contentElement = document.getElementById('card-content');
    contentObserver.observe(contentElement, {
        childList: true,
        subtree: true,
        characterData: true
    });

    // Chat event listeners
    const sendButton = document.getElementById('ai-send-btn');
    const inputField = document.getElementById('ai-input');

    sendButton.addEventListener('click', sendMessage);
    inputField.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    // Quick questions event delegation
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('quick-question-btn')) {
            const question = e.target.getAttribute('data-question');
            document.getElementById('ai-input').value = question;
            sendMessage();
        }
    });

    // Initial AI context update
    updateAIContext();
}); 