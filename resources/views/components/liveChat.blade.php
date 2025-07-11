@section('chat')
    @auth
        <style>
            .chat-wrapper {
                position: fixed;
                bottom: 24px;
                right: 24px;
                z-index: 9999;
                display: flex;
                flex-direction: column;
                align-items: flex-end;
                gap: 12px;
            }

            .chatbox {
                display: none;
                width: 300px;
                max-height: 400px;
                background: white;
                border: 1px solid #e8efe3;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 8px 20px rgba(153, 188, 133, 0.2), 0 4px 15px rgba(0, 0, 0, 0.1);
                flex-direction: column;
                transform: translateY(15px) scale(0.95);
                opacity: 0;
                transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            }

            .chatbox.active {
                display: flex;
                transform: translateY(0) scale(1);
                opacity: 1;
            }

            .chat-header {
                background: linear-gradient(135deg, #99bc85 0%, #88a871 100%);
                color: white;
                padding: 12px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                box-shadow: 0 2px 8px rgba(153, 188, 133, 0.3);
            }

            .chat-header span {
                font-weight: 600;
                font-size: 1rem;
                letter-spacing: 0.3px;
            }

            .chat-header button {
                background: rgba(255, 255, 255, 0.2);
                border: none;
                border-radius: 4px;
                padding: 4px;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .chat-header button:hover {
                background: rgba(255, 255, 255, 0.25);
                transform: scale(1.1);
            }

            .chat-messages {
                flex: 1;
                max-height: 250px;
                overflow-y: auto;
                padding: 16px 12px;
                background: #f8f9fa;
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .message {
                max-width: 85%;
                padding: 10px 14px;
                border-radius: 16px;
                font-size: 13px;
                animation: slideIn 0.4s ease-out;
                position: relative;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                line-height: 1.4;
                transition: all 0.2s ease;
                word-wrap: break-word;
            }

            .message:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            }

            .user-message {
                background: linear-gradient(135deg, #99bc85 0%, #8db277 100%);
                color: white;
                align-self: flex-end;
                border-bottom-right-radius: 6px;
                box-shadow: 0 2px 8px rgba(153, 188, 133, 0.3);
            }

            .admin-message {
                background: white;
                border: 1px solid #e8efe3;
                align-self: flex-start;
                border-bottom-left-radius: 6px;
                box-shadow: 0 2px 8px rgba(153, 188, 133, 0.15);
                color: #333;
            }

            .bot-message {
                background: white;
                border: 1px solid #e8efe3;
                align-self: flex-start;
                border-bottom-left-radius: 6px;
                box-shadow: 0 2px 8px rgba(153, 188, 133, 0.15);
                color: #333;
            }

            /* Enhanced bubble tips */
            .user-message::after {
                content: '';
                position: absolute;
                bottom: 0;
                right: -10px;
                width: 0;
                height: 0;
                border: 12px solid transparent;
                border-left-color: #99bc85;
                border-bottom: 0;
                border-right: 0;
                margin-bottom: -6px;
                filter: drop-shadow(2px 2px 4px rgba(153, 188, 133, 0.3));
            }

            .admin-message::before,
            .bot-message::before {
                content: '';
                position: absolute;
                bottom: 0;
                left: -12px;
                width: 0;
                height: 0;
                border: 12px solid transparent;
                border-right-color: white;
                border-bottom: 0;
                border-left: 0;
                margin-bottom: -6px;
                filter: drop-shadow(-2px 2px 4px rgba(0, 0, 0, 0.1));
            }

            .chat-input {
                display: flex;
                gap: 8px;
                padding: 12px;
                border-top: 1px solid #e8efe3;
                background: white;
                box-shadow: 0 -2px 8px rgba(153, 188, 133, 0.1);
            }

            .chat-input input {
                flex: 1;
                padding: 10px 16px;
                border: 1px solid #e0e0e0;
                border-radius: 20px;
                outline: none;
                font-size: 13px;
                background: #f9fbf8;
                transition: all 0.3s ease;
                box-shadow: inset 0 1px 4px rgba(0, 0, 0, 0.05);
                font-family: inherit;
            }

            .chat-input input:focus {
                border-color: #99bc85;
                box-shadow: 0 0 0 2px rgba(153, 188, 133, 0.15), inset 0 1px 4px rgba(0, 0, 0, 0.05);
            }

            .chat-input input::placeholder {
                color: #888;
                transition: all 0.3s ease;
            }

            .chat-input input:focus::placeholder {
                color: #bbb;
                transform: translateX(5px);
            }

            .chat-input button {
                background: linear-gradient(135deg, #99bc85 0%, #8db277 100%);
                color: white;
                border: none;
                padding: 0;
                width: 38px;
                height: 38px;
                border-radius: 50%;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 2px 8px rgba(153, 188, 133, 0.3);
            }

            .chat-input button:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(153, 188, 133, 0.4);
            }

            .chat-input button:active {
                transform: translateY(-2px) scale(1.05);
            }

            .chat-toggle-btn {
                width: 56px;
                height: 56px;
                background: linear-gradient(135deg, #99bc85 0%, #8db277 100%);
                color: white;
                border-radius: 50%;
                border: none;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(153, 188, 133, 0.4);
            }

            .chat-toggle-btn:hover {
                transform: scale(1.1);
                box-shadow: 0 6px 20px rgba(153, 188, 133, 0.5);
            }

            .chat-toggle-btn svg {
                width: 24px;
                height: 24px;
                transition: all 0.3s ease;
                position: relative;
                z-index: 2;
            }

            .chat-toggle-btn .close-icon {
                display: none;
            }

            .chat-toggle-btn.active .chat-icon {
                display: none;
            }

            .chat-toggle-btn.active .close-icon {
                display: block;
            }

            .chat-toggle-btn.active {
                transform: rotate(90deg);
                background: linear-gradient(135deg, #8db277 0%, #7a9f65 100%);
            }

            /* Enhanced scrollbar */
            .chat-messages::-webkit-scrollbar {
                width: 6px;
            }

            .chat-messages::-webkit-scrollbar-track {
                background: rgba(232, 239, 227, 0.3);
                border-radius: 6px;
                margin: 4px;
            }

            .chat-messages::-webkit-scrollbar-thumb {
                background: #c1d4b0;
                border-radius: 6px;
                transition: all 0.3s ease;
            }

            .chat-messages::-webkit-scrollbar-thumb:hover {
                background: #99bc85;
            }

            /* Enhanced typing indicator */
            .typing-indicator {
                display: inline-flex;
                padding: 16px 20px;
                background: linear-gradient(135deg, white 0%, #fdfdfd 100%);
                border-radius: 20px;
                border: 2px solid #e8efe3;
                align-self: flex-start;
                box-shadow: 0 4px 20px rgba(153, 188, 133, 0.15);
                backdrop-filter: blur(5px);
            }

            .typing-indicator span {
                height: 10px;
                width: 10px;
                background: linear-gradient(135deg, #99bc85 0%, #8db277 100%);
                border-radius: 50%;
                display: inline-block;
                margin: 0 3px;
                animation: bounce 1.4s infinite ease-in-out;
                box-shadow: 0 2px 4px rgba(153, 188, 133, 0.3);
            }

            .typing-indicator span:nth-child(1) {
                animation-delay: 0s;
            }

            .typing-indicator span:nth-child(2) {
                animation-delay: 0.2s;
            }

            .typing-indicator span:nth-child(3) {
                animation-delay: 0.4s;
            }

            /* Animations */
            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateY(20px) scale(0.9);
                }

                to {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }

            @keyframes bounce {

                0%,
                60%,
                100% {
                    transform: translateY(0) scale(1);
                }

                30% {
                    transform: translateY(-8px) scale(1.1);
                }
            }

            @keyframes pulse {
                0% {
                    box-shadow: 0 8px 30px rgba(153, 188, 133, 0.4), 0 0 0 0 rgba(153, 188, 133, 0.3);
                }

                50% {
                    box-shadow: 0 8px 30px rgba(153, 188, 133, 0.4), 0 0 0 8px rgba(153, 188, 133, 0.1);
                }

                100% {
                    box-shadow: 0 8px 30px rgba(153, 188, 133, 0.4), 0 0 0 0 rgba(153, 188, 133, 0);
                }
            }

            @keyframes rotate {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }

            @keyframes shimmer {
                0% {
                    transform: translateX(-100%) translateY(-100%) rotate(30deg);
                }

                100% {
                    transform: translateX(100%) translateY(100%) rotate(30deg);
                }
            }

            /* Responsive design */
            @media (max-width: 480px) {
                .chatbox {
                    width: calc(100vw - 40px);
                    max-height: 350px;
                    right: 20px;
                }

                .chat-messages {
                    max-height: 200px;
                }
            }

            /* Smooth scroll behavior */
            .chat-messages {
                scroll-behavior: smooth;
            }

            /* Message status indicators */
            .message-status {
                font-size: 10px;
                opacity: 0.7;
                margin-top: 4px;
                text-align: right;
            }

            .user-message .message-status {
                color: rgba(255, 255, 255, 0.8);
            }
        </style>

        <div class="chat-wrapper">
            <div class="chatbox" id="chatbox">
                <div class="chat-header">
                    <span>Live Chat Support</span>
                    <div class="connection-status" id="connectionStatus">
                        <span class="status-dot" id="statusDot"></span>
                        <span id="statusText">Connecting...</span>
                    </div>
                    <button onclick="toggleChat()" style="background: transparent; border: none; cursor: pointer;">
                        <svg width="20" height="20" fill="white" viewBox="0 0 20 20">
                            <path d="M2 2L18 18M18 2L2 18" stroke="white" stroke-width="2" />
                        </svg>
                    </button>
                </div>

                <div class="chat-messages" id="chatMessages">
                    @foreach ($messages as $message)
                        @if ($message->from_user_id === auth()->id())
                            <div class="message user-message">
                                <div class="message-content">{{ $message->message }}</div>
                                <div class="message-time">{{ $message->created_at->format('H:i') }}</div>
                                <div class="message-status">Sent</div>
                            </div>
                        @else
                            <div class="message admin-message">
                                <div class="message-content">{{ $message->message }}</div>
                                <div class="message-time">{{ $message->created_at->format('H:i') }}</div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="chat-input">
                    {{-- Admin user ID --}}
                    <input type="hidden" id="toUserId" value="{{ $admin->id ?? 1 }}">

                    <input type="text" id="messageInput" placeholder="Type your message..."
                        onkeypress="handleKeyPress(event)">
                    <button onclick="sendMessage()" aria-label="Send message" id="sendButton">
                        <svg width="24" height="24" fill="white" viewBox="0 0 24 24">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <button class="chat-toggle-btn" id="chatToggleBtn" onclick="toggleChat()">
                <span class="chat-icon">
                    <svg fill="white" viewBox="0 0 28 28">
                        <path
                            d="M19.833 14V3.5a1.167 1.167 0 0 0-1.167-1.167H3.5A1.167 1.167 0 0 0 2.333 3.5V19.833L7 15.167h11.666A1.167 1.167 0 0 0 19.833 14Z" />
                        <path
                            d="M24.5 7h-2.333v10.5H7v2.333a1.167 1.167 0 0 0 1.167 1.167H21l4.667 4.667V8.167A1.167 1.167 0 0 0 24.5 7Z" />
                    </svg>
                </span>
                <span class="close-icon">
                    <svg fill="white" viewBox="0 0 20 20">
                        <path d="M2 2L18 18M18 2L2 18" stroke="white" stroke-width="2" />
                    </svg>
                </span>
                <span class="notification-dot" id="notificationDot" style="display: none;"></span>
            </button>
        </div>

        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        <script>
            // Initialize Pusher
            const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
                cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
                encrypted: true,
                authEndpoint: '/broadcasting/auth',
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }
            });

            // Subscribe to user's private channel
            const userChannel = pusher.subscribe('private-chat.{{ auth()->id() }}');
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

                const isCurrentUser = data.from_user_id == {{ auth()->id() }};
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

                // Add pulse animation
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

                // Auto scroll to bottom when opened
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
                    // Disable send button temporarily
                    sendButton.disabled = true;
                    sendButton.style.opacity = '0.6';

                    // Clear input immediately
                    input.value = '';

                    // Show typing indicator for admin
                    showTypingIndicator();

                    // Send to backend
                    fetch("{{ route('chat.send') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
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
                        // Message will be displayed via real-time event
                    }).catch(error => {
                        console.error("Error:", error);

                        // Show error message
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'message error-message';
                        errorDiv.innerHTML = `
                            <div class="message-content">Failed to send message. Please try again.</div>
                        `;
                        chatMessages.appendChild(errorDiv);
                        chatMessages.scrollTop = chatMessages.scrollHeight;

                        // Restore the message to input
                        input.value = message;
                    }).finally(() => {
                        // Re-enable send button
                        sendButton.disabled = false;
                        sendButton.style.opacity = '1';
                        removeTypingIndicator();
                    });
                }
            }

            function showTypingIndicator() {
                removeTypingIndicator(); // Remove existing one first

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
            pusher.connection.bind('connected', function() {
                console.log('Connected to Pusher');
                updateConnectionStatus('connected', 'Connected');
            });

            pusher.connection.bind('disconnected', function() {
                console.log('Disconnected from Pusher');
                updateConnectionStatus('disconnected', 'Disconnected');
            });

            pusher.connection.bind('connecting', function() {
                updateConnectionStatus('connecting', 'Connecting...');
            });

            pusher.connection.bind('error', function(error) {
                console.error('Pusher connection error:', error);
                updateConnectionStatus('error', 'Connection Error');
            });

            function updateConnectionStatus(status, text) {
                const statusDot = document.getElementById('statusDot');
                const statusText = document.getElementById('statusText');

                // Remove all status classes
                statusDot.className = 'status-dot';
                statusDot.classList.add(status);
                statusText.textContent = text;
            }

            // Auto-resize input on focus
            document.getElementById('messageInput').addEventListener('focus', function() {
                this.style.transform = 'scale(1.02)';
            });

            document.getElementById('messageInput').addEventListener('blur', function() {
                this.style.transform = 'scale(1)';
            });

            // Initialize chat
            document.addEventListener('DOMContentLoaded', function() {
                const chatMessages = document.getElementById('chatMessages');

                // Add welcome message if no messages exist
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
        </script>


    @endauth
@endsection
