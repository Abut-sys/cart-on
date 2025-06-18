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
                width: 350px;
                height: 480px;
                background: white;
                border: 1px solid #e0e0e0;
                border-radius: 16px;
                overflow: hidden;
                box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
                flex-direction: column;
                transform: translateY(20px);
                opacity: 0;
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            }

            .chatbox.active {
                display: flex;
                transform: translateY(0);
                opacity: 1;
            }

            .chat-header {
                background: linear-gradient(135deg, #99bc85 0%, #88a871 100%);
                color: white;
                padding: 16px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            .chat-header span {
                font-weight: 600;
                font-size: 1.1rem;
                letter-spacing: 0.3px;
            }

            .chat-messages {
                flex: 1;
                overflow-y: auto;
                padding: 20px 16px;
                background: #f8f9fa;
                display: flex;
                flex-direction: column;
                gap: 16px;
                background-image:
                    radial-gradient(#e2e8e0 1px, transparent 1px),
                    radial-gradient(#e2e8e0 1px, transparent 1px);
                background-size: 30px 30px;
                background-position: 0 0, 15px 15px;
            }

            .message {
                max-width: 85%;
                padding: 12px 16px;
                border-radius: 18px;
                font-size: 14px;
                animation: fadeIn 0.4s ease-out;
                position: relative;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
                line-height: 1.5;
                transition: transform 0.2s ease;
            }

            .message:hover {
                transform: translateY(-2px);
            }

            .user-message {
                background: linear-gradient(135deg, #99bc85 0%, #8db277 100%);
                color: white;
                align-self: flex-end;
                border-bottom-right-radius: 5px;
            }

            .bot-message {
                background: white;
                border: 1px solid #e8efe3;
                align-self: flex-start;
                border-bottom-left-radius: 5px;
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.04);
            }

            /* Bubble tip effect */
            .user-message::after {
                content: '';
                position: absolute;
                bottom: 0;
                right: -8px;
                width: 0;
                height: 0;
                border: 10px solid transparent;
                border-left-color: #99bc85;
                border-bottom: 0;
                border-right: 0;
                margin-bottom: -5px;
            }

            .bot-message::before {
                content: '';
                position: absolute;
                bottom: 0;
                left: -8px;
                width: 0;
                height: 0;
                border: 10px solid transparent;
                border-right-color: white;
                border-bottom: 0;
                border-left: 0;
                margin-bottom: -5px;
            }

            .chat-input {
                display: flex;
                gap: 10px;
                padding: 16px;
                border-top: 1px solid #e8efe3;
                background: white;
                box-shadow: 0 -3px 10px rgba(0, 0, 0, 0.03);
            }

            .chat-input input {
                flex: 1;
                padding: 12px 18px;
                border: 1px solid #e0e0e0;
                border-radius: 25px;
                outline: none;
                font-size: 14px;
                background: #f9fbf8;
                transition: all 0.3s ease;
                box-shadow: inset 0 1px 4px rgba(0, 0, 0, 0.05);
            }

            .chat-input input:focus {
                border-color: #99bc85;
                box-shadow: 0 0 0 3px rgba(153, 188, 133, 0.2);
            }

            .chat-input button {
                background: linear-gradient(135deg, #99bc85 0%, #8db277 100%);
                color: white;
                border: none;
                padding: 0;
                width: 44px;
                height: 44px;
                border-radius: 50%;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 4px 8px rgba(153, 188, 133, 0.3);
            }

            .chat-input button:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 12px rgba(153, 188, 133, 0.4);
            }

            .chat-input button:active {
                transform: translateY(1px);
            }

            .chat-toggle-btn {
                width: 64px;
                height: 64px;
                background: linear-gradient(135deg, #99bc85 0%, #8db277 100%);
                color: white;
                border-radius: 50%;
                border: none;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                box-shadow: 0 6px 20px rgba(153, 188, 133, 0.5);
                overflow: hidden;
            }

            .chat-toggle-btn::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: rgba(255, 255, 255, 0.1);
                transform: rotate(30deg);
                transition: all 0.6s ease;
            }

            .chat-toggle-btn:hover {
                transform: scale(1.08) rotate(5deg);
                box-shadow: 0 8px 25px rgba(153, 188, 133, 0.6);
            }

            .chat-toggle-btn:hover::before {
                transform: rotate(30deg) translate(20%, 20%);
            }

            .chat-toggle-btn svg {
                width: 28px;
                height: 28px;
                transition: transform 0.3s ease;
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
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(15px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes float {
                0% {
                    transform: translateY(0px);
                }

                50% {
                    transform: translateY(-8px);
                }

                100% {
                    transform: translateY(0px);
                }
            }

            /* Scrollbar Styling */
            .chat-messages::-webkit-scrollbar {
                width: 8px;
            }

            .chat-messages::-webkit-scrollbar-track {
                background: rgba(232, 239, 227, 0.3);
                border-radius: 4px;
            }

            .chat-messages::-webkit-scrollbar-thumb {
                background: #c1d4b0;
                border-radius: 4px;
            }

            .chat-messages::-webkit-scrollbar-thumb:hover {
                background: #99bc85;
            }

            /* Typing indicator */
            .typing-indicator {
                display: inline-flex;
                padding: 10px 15px;
                background: white;
                border-radius: 18px;
                border: 1px solid #e8efe3;
                align-self: flex-start;
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.04);
            }

            .typing-indicator span {
                height: 8px;
                width: 8px;
                background: #99bc85;
                border-radius: 50%;
                display: inline-block;
                margin: 0 2px;
                animation: bounce 1.3s infinite ease-in-out;
            }

            .typing-indicator span:nth-child(1) {
                animation-delay: 0s;
            }

            .typing-indicator span:nth-child(2) {
                animation-delay: 0.15s;
            }

            .typing-indicator span:nth-child(3) {
                animation-delay: 0.3s;
            }

            @keyframes bounce {

                0%,
                60%,
                100% {
                    transform: translateY(0);
                }

                30% {
                    transform: translateY(-5px);
                }
            }
        </style>


        <div class="chat-wrapper">
            <div class="chatbox" id="chatbox">
                <div class="chat-header">
                    <span>Live Chat Support</span>
                    <button onclick="toggleChat()" style="background: transparent; border: none; cursor: pointer;">
                        <svg width="20" height="20" fill="white" viewBox="0 0 20 20">
                            <path d="M2 2L18 18M18 2L2 18" stroke="white" stroke-width="2" />
                        </svg>
                    </button>
                </div>

                <div class="chat-messages" id="chatMessages">
                    @foreach ($messages as $message)
                        @if ($message->from_user_id === auth()->id())
                            <div class="message user-message">{{ $message->message }}</div>
                        @else
                            <div class="message admin-message">{{ $message->message }}</div>
                        @endif
                    @endforeach
                </div>

                <div class="chat-input">
                    {{-- Hidden to_user_id, misalnya admin id = 1 --}}
                    <input type="hidden" id="toUserId" value="1">

                    <input type="text" id="messageInput" placeholder="Type a message..." onkeypress="handleKeyPress(event)">
                    <button onclick="sendMessage()" aria-label="Send message">
                        <svg width="20" height="20" fill="white" viewBox="0 0 24 24">
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
            </button>
        </div>


        <script>
            function toggleChat() {
                const chatbox = document.getElementById('chatbox');
                const toggleBtn = document.getElementById('chatToggleBtn');
                chatbox.classList.toggle('active');
                toggleBtn.classList.toggle('active');
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

                if (message) {
                    // Tampilkan pesan user langsung
                    const userMessage = document.createElement('div');
                    userMessage.className = 'message user-message';
                    userMessage.textContent = message;
                    chatMessages.appendChild(userMessage);
                    input.value = '';
                    chatMessages.scrollTop = chatMessages.scrollHeight;

                    // Kirim ke backend
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
                            alert("Gagal mengirim pesan");
                        } else {
                            // Bot response setelah pengiriman sukses
                            setTimeout(() => {
                                const botResponse = document.createElement('div');
                                botResponse.className = 'message admin-message';

                                // Bot logic: ubah sesuai kebutuhan
                                if (message.toLowerCase().includes("refund")) {
                                    botResponse.textContent =
                                        "Mohon tunggu, kami akan bantu proses refund Anda segera.";
                                } else if (message.toLowerCase().includes("produk")) {
                                    botResponse.textContent =
                                        "Silakan sebutkan nama produk yang Anda maksud 😊";
                                } else {
                                    if (!sessionStorage.getItem('hasThanksResponse')) {
                                        botResponse.textContent =
                                            "Terima kasih! Admin kami akan segera menghubungi Anda.";
                                        sessionStorage.setItem('hasThanksResponse', 'true');
                                    } else {
                                        return; // Jangan tampilkan respon bot lagi
                                    }
                                }

                                chatMessages.appendChild(botResponse);
                                chatMessages.scrollTop = chatMessages.scrollHeight;
                            }, 1000);
                        }
                    }).catch(error => {
                        console.error("Error:", error);
                    });
                }
            }
        </script>
    @endauth
@endsection
