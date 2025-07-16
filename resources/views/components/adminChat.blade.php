@section('admin_chat')
    <link rel="stylesheet" href="{{ asset('pemai/css/adminChat.css') }}">

    <div class="admin-chat-wrapper">
        <button class="admin-chat-btn" onclick="toggleAdminChatModal()">
            💬
            <span class="notification-badge" id="notificationBadge" style="display: none;"></span>
        </button>

        <div class="admin-chat-modal" id="adminChatModal">
            <div class="chat-header">
                <span id="chatHeaderTitle">User List</span>
                <button onclick="closeAdminChatModal()">✕</button>
            </div>

            <div id="adminChatUserList">
                <div class="search-container">
                    <input type="text" class="search-input" placeholder="🔍 Search users..."
                        onkeyup="filterUserList(this.value)">
                </div>

                <div class="chat-body" id="userListContainer">
                    @foreach ($users as $user)
                        <div class="user-item" data-name="{{ strtolower($user->name) }}"
                            onclick="loadMessages({{ $user->id }}, '{{ $user->name }}')">
                            <div class="user-avatar">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="user-info">
                                <div class="user-name">{{ $user->name }}</div>
                                <div class="user-status">
                                    <span class="status-indicator online"></span>
                                    Online
                                </div>
                            </div>
                            <div class="unread-badge" id="unread-{{ $user->id }}" style="display: none;">
                                <span class="unread-count">0</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="d-none" id="adminChatBoxWrapper">
                <div class="chat-body" id="chatBox">
                    <div class="loading-spinner">
                        <div class="spinner"></div>
                        <div class="loading-text">Loading messages...</div>
                    </div>
                </div>

                <div class="chat-footer">
                    <input type="hidden" id="toUserId">
                    <textarea class="message-input" id="adminMessage" placeholder="Type your message..." rows="1"
                        onkeypress="handleMessageInput(event)" oninput="autoResize(this)"></textarea>
                    <button class="send-btn" onclick="sendAdminMessage()">
                        ➤
                    </button>
                </div>
                <div style="padding: 0 24px 20px;">
                    <button class="back-btn" onclick="backToUserList()">
                        ← Back to Users
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        // Pass Laravel variables to JavaScript
        window.PUSHER_APP_KEY = '{{ env('PUSHER_APP_KEY') }}';
        window.PUSHER_APP_CLUSTER = '{{ env('PUSHER_APP_CLUSTER') }}';
        window.CSRF_TOKEN = '{{ csrf_token() }}';
        window.AUTH_USER_ID = {{ auth()->id() }};
        window.ADMIN_CHAT_MESSAGES_URL = "{{ url('admin/chat/messages') }}";
        window.ADMIN_CHAT_SEND_URL = "{{ route('admin.chat.send') }}";
    </script>
    <script src="{{ asset('js/adminChat.js') }}"></script>
@endsection
