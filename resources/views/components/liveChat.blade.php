@section('chat')
    @auth
        <link rel="stylesheet" href="{{ asset('pemai/css/liveChat.css') }}">

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
            // Pass Laravel variables to JavaScript
            window.PUSHER_APP_KEY = '{{ env('PUSHER_APP_KEY') }}';
            window.PUSHER_APP_CLUSTER = '{{ env('PUSHER_APP_CLUSTER') }}';
            window.CSRF_TOKEN = '{{ csrf_token() }}';
            window.USER_ID = {{ auth()->id() }};
            window.CHAT_ROUTE = '{{ route('chat.send') }}';
        </script>
        <script src="{{ asset('js/liveChat.js') }}"></script>
    @endauth
@endsection
