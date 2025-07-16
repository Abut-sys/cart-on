// Initialize Pusher
const pusher = new Pusher(window.PUSHER_APP_KEY, {
    cluster: window.PUSHER_APP_CLUSTER,
    encrypted: true,
    authEndpoint: '/broadcasting/auth',
    auth: {
        headers: {
            'X-CSRF-TOKEN': window.CSRF_TOKEN
        }
    }
});

// Subscribe to user's private channel
const userChannel = pusher.subscribe(`private-chat.${window.USER_ID}`);
let isTyping = false;
let typingTimeout;

// Listen for new messages
userChannel.bind('message.sent', function(data) {
    console.log('New message received:', data);
    displayNewMessage(data);

    // Show notification if chat is closed
    const chatbox = document.getElementById('chatbox');
    if (!chatbox.classList.contains('active')) {
        showNotification();
    }
});

function displayNewMessage(data) {
    const chatMessages = document.getElementById('chatMessages');
    const messageDiv = document.createElement('div');

    const isCurrentUser = data.from_user_id == window.USER_ID;
    messageDiv.className = 'message ' + (isCurrentUser ? 'user-message' : 'admin-message');

    messageDiv.innerHTML = `
        <div class="message-content">${data.message}</div>
        <div class="message-time">${data.timestamp}</div>
        ${isCurrentUser ? '<div class="message-status">Sent</div>' : ''}
    `;

    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;

    // Add smooth animation
    messageDiv.style.opacity = '0';
    messageDiv.style.transform = 'translateY(20px)';

    requestAnimationFrame(() => {
        messageDiv.style.transition = 'all 0.3s ease';
        messageDiv.style.opacity = '1';
        messageDiv.style.transform = 'translateY(0)';
    });

    // Remove typing indicator if it exists
    removeTypingIndicator();
}

function showNotification() {
    const notificationDot = document.getElementById('notificationDot');
    notificationDot.style.display = 'block';
    notificationDot.style.animation = 'pulse 2s infinite';
}

function hideNotification() {
    const notificationDot = document.getElementById('notificationDot');
    notificationDot.style.display = 'none';
    notificationDot.style.animation = '';
}

function toggleChat() {
    const chatbox = document.getElementById('chatbox');
    const toggleBtn = document.getElementById('chatToggleBtn');
    chatbox.classList.toggle('active');
    toggleBtn.classList.toggle('active');

    if (chatbox.classList.contains('active')) {
        hideNotification();
        setTimeout(() => {
            const chatMessages = document.getElementById('chatMessages');
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }, 500);
    }
}

function handleKeyPress(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
}

function sendMessage() {
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    const toUserId = document.getElementById('toUserId').value;
    const chatMessages = document.getElementById('chatMessages');
    const sendButton = document.getElementById('sendButton');

    if (message) {
        sendButton.disabled = true;
        sendButton.style.opacity = '0.6';
        input.value = '';
        showTypingIndicator();

        fetch(window.CHAT_ROUTE, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": window.CSRF_TOKEN
            },
            body: JSON.stringify({
                message: message,
                to_user_id: toUserId
            })
        }).then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        }).then(data => {
            console.log('Message sent successfully:', data);
        }).catch(error => {
            console.error("Error:", error);
            const errorDiv = document.createElement('div');
            errorDiv.className = 'message error-message';
            errorDiv.innerHTML = `
                <div class="message-content">Failed to send message. Please try again.</div>
            `;
            chatMessages.appendChild(errorDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
            input.value = message;
        }).finally(() => {
            sendButton.disabled = false;
            sendButton.style.opacity = '1';
            removeTypingIndicator();
        });
    }
}

function showTypingIndicator() {
    removeTypingIndicator();
    const chatMessages = document.getElementById('chatMessages');
    const typingDiv = document.createElement('div');
    typingDiv.className = 'typing-indicator';
    typingDiv.id = 'typingIndicator';
    typingDiv.innerHTML = `
        <div class="typing-text">Sending your message</div>
        <div class="typing-dots">
            <span></span><span></span><span></span>
        </div>
    `;
    chatMessages.appendChild(typingDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function removeTypingIndicator() {
    const indicator = document.getElementById('typingIndicator');
    if (indicator) {
        indicator.remove();
    }
}

// Connection status handling
pusher.connection.bind('connected', () => {
    console.log('Connected to Pusher');
    updateConnectionStatus('connected', 'Connected');
});

pusher.connection.bind('disconnected', () => {
    console.log('Disconnected from Pusher');
    updateConnectionStatus('disconnected', 'Disconnected');
});

pusher.connection.bind('connecting', () => {
    updateConnectionStatus('connecting', 'Connecting...');
});

pusher.connection.bind('error', (error) => {
    console.error('Pusher connection error:', error);
    updateConnectionStatus('error', 'Connection Error');
});

function updateConnectionStatus(status, text) {
    const statusDot = document.getElementById('statusDot');
    const statusText = document.getElementById('statusText');
    statusDot.className = 'status-dot';
    statusDot.classList.add(status);
    statusText.textContent = text;
}

// Input animations
document.getElementById('messageInput').addEventListener('focus', function() {
    this.style.transform = 'scale(1.02)';
});

document.getElementById('messageInput').addEventListener('blur', function() {
    this.style.transform = 'scale(1)';
});

// Initialize chat
document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chatMessages');

    if (chatMessages.children.length === 0) {
        const welcomeMessage = document.createElement('div');
        welcomeMessage.className = 'message admin-message';
        welcomeMessage.innerHTML = `
            <div class="message-content">Halo! Selamat datang di Live Chat Support. Ada yang bisa kami bantu hari ini? 😊</div>
            <div class="message-time">${new Date().toLocaleTimeString('en-US', {hour: '2-digit', minute: '2-digit'})}</div>
        `;
        chatMessages.appendChild(welcomeMessage);
    }

    console.log('User chat initialized with real-time messaging');
});
