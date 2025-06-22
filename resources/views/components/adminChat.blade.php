@section('admin_chat')
    <style>
        .admin-chat-wrapper {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
        }

        .admin-chat-btn {
            background: linear-gradient(135deg, #99bc85, #85a87d);
            color: white;
            border: none;
            border-radius: 50%;
            width: 64px;
            height: 64px;
            box-shadow: 0 8px 25px rgba(153, 188, 133, 0.4);
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .admin-chat-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .admin-chat-btn:hover {
            transform: scale(1.15) rotate(5deg);
            box-shadow: 0 12px 35px rgba(153, 188, 133, 0.6);
        }

        .admin-chat-btn:hover::before {
            left: 100%;
        }

        .admin-chat-btn:active {
            transform: scale(0.95);
        }

        /* Enhanced notification pulse animation */
        .admin-chat-btn.has-notification::after {
            content: '';
            position: absolute;
            top: -3px;
            right: -3px;
            width: 18px;
            height: 18px;
            background: linear-gradient(135deg, #ff4757, #ff3838);
            border-radius: 50%;
            animation: pulse 2s infinite;
            border: 3px solid white;
            box-shadow: 0 2px 8px rgba(255, 71, 87, 0.4);
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.3); opacity: 0.7; }
            100% { transform: scale(1); opacity: 1; }
        }

        .admin-chat-modal {
            position: fixed;
            bottom: 100px;
            right: 24px;
            width: 450px;
            max-height: 75vh;
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15),
                       0 0 0 1px rgba(0, 0, 0, 0.05),
                       0 0 100px rgba(153, 188, 133, 0.1);
            display: none;
            flex-direction: column;
            overflow: hidden;
            z-index: 9999;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateY(30px) scale(0.9);
            opacity: 0;
            backdrop-filter: blur(20px);
        }

        .admin-chat-modal.active {
            display: flex;
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        .chat-header {
            background: linear-gradient(135deg, #99bc85 0%, #85a87d 100%);
            color: white;
            padding: 20px 24px;
            font-weight: 600;
            font-size: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top-left-radius: 24px;
            border-top-right-radius: 24px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 20px rgba(153, 188, 133, 0.2);
        }

        .chat-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
            animation: shimmer 4s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .chat-header button {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            font-size: 18px;
        }

        .chat-header button:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: rotate(90deg) scale(1.1);
        }

        .search-container {
            padding: 20px 24px 16px;
            background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
            border-bottom: 1px solid rgba(233, 236, 239, 0.5);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .search-input {
            width: 100%;
            padding: 14px 20px;
            border: 2px solid #e9ecef;
            border-radius: 30px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            font-family: inherit;
        }

        .search-input:focus {
            outline: none;
            border-color: #99bc85;
            box-shadow: 0 0 0 4px rgba(153, 188, 133, 0.1),
                       0 4px 20px rgba(153, 188, 133, 0.15);
            transform: translateY(-2px);
        }

        /* Scrollable chat body */
        .chat-body {
            flex: 1;
            overflow-y: auto;
            padding: 8px 4px;
            background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
            max-height: calc(75vh - 200px);
            min-height: 200px;
        }

        .chat-body::-webkit-scrollbar {
            width: 8px;
        }

        .chat-body::-webkit-scrollbar-track {
            background: rgba(241, 241, 241, 0.5);
            border-radius: 4px;
            margin: 8px 0;
        }

        .chat-body::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #99bc85, #85a87d);
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .chat-body::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #85a87d, #739670);
            box-shadow: 0 2px 8px rgba(153, 188, 133, 0.3);
        }

        .user-item {
            padding: 16px 20px;
            margin: 6px 12px;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            background: white;
            border: 1px solid transparent;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .user-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 5px;
            background: linear-gradient(135deg, #99bc85, #85a87d);
            border-radius: 0 3px 3px 0;
            transform: scaleX(0);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .user-item:hover {
            background: linear-gradient(135deg, rgba(153, 188, 133, 0.08), rgba(153, 188, 133, 0.12));
            border-color: rgba(153, 188, 133, 0.2);
            transform: translateX(12px) translateY(-2px);
            box-shadow: 0 8px 25px rgba(153, 188, 133, 0.15);
        }

        .user-item:hover::before {
            transform: scaleX(1);
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #99bc85, #85a87d);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(153, 188, 133, 0.3);
            position: relative;
            overflow: hidden;
        }

        .user-avatar::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.2), transparent);
            transform: rotate(45deg);
            transition: all 0.5s ease;
            opacity: 0;
        }

        .user-item:hover .user-avatar::before {
            opacity: 1;
            animation: shine 0.8s ease;
        }

        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }

        .user-info {
            flex: 1;
        }

        .user-name {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 4px;
            font-size: 15px;
        }

        .user-status {
            font-size: 13px;
            color: #6c757d;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .user-status::before {
            content: '';
            width: 8px;
            height: 8px;
            background: #28a745;
            border-radius: 50%;
            animation: pulse-status 2s infinite;
        }

        @keyframes pulse-status {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .message-bubble {
            max-width: 85%;
            padding: 12px 16px;
            margin: 10px 0;
            border-radius: 20px;
            position: relative;
            animation: fadeInUp 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            word-wrap: break-word;
            font-size: 14px;
            line-height: 1.4;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message-sent {
            background: linear-gradient(135deg, #99bc85, #85a87d);
            color: white;
            margin-left: auto;
            border-bottom-right-radius: 8px;
            box-shadow: 0 3px 12px rgba(153, 188, 133, 0.3);
        }

        .message-received {
            background: linear-gradient(135deg, #f1f3f4, #e9ecef);
            color: #2c3e50;
            margin-right: auto;
            border-bottom-left-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .chat-footer {
            padding: 20px 24px;
            background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
            border-top: 1px solid rgba(233, 236, 239, 0.5);
            display: flex;
            gap: 16px;
            align-items: end;
            border-bottom-left-radius: 24px;
            border-bottom-right-radius: 24px;
            position: sticky;
            bottom: 0;
        }

        .message-input {
            flex: 1;
            padding: 14px 20px;
            border: 2px solid #e9ecef;
            border-radius: 25px;
            font-size: 14px;
            resize: none;
            max-height: 120px;
            min-height: 48px;
            transition: all 0.3s ease;
            background: white;
            font-family: inherit;
            line-height: 1.4;
        }

        .message-input:focus {
            outline: none;
            border-color: #99bc85;
            background: white;
            box-shadow: 0 0 0 4px rgba(153, 188, 133, 0.1),
                       0 4px 20px rgba(153, 188, 133, 0.1);
        }

        .send-btn {
            background: linear-gradient(135deg, #99bc85, #85a87d);
            color: white;
            border: none;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(153, 188, 133, 0.3);
            font-size: 18px;
            position: relative;
            overflow: hidden;
        }

        .send-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: all 0.4s ease;
        }

        .send-btn:hover {
            transform: scale(1.1) rotate(15deg);
            box-shadow: 0 6px 20px rgba(153, 188, 133, 0.4);
        }

        .send-btn:hover::before {
            width: 100%;
            height: 100%;
        }

        .send-btn:active {
            transform: scale(0.95);
        }

        .back-btn {
            background: linear-gradient(135deg, rgba(153, 188, 133, 0.1), rgba(153, 188, 133, 0.15));
            color: #99bc85;
            border: 2px solid rgba(153, 188, 133, 0.2);
            border-radius: 25px;
            padding: 10px 20px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.4s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            margin-top: 8px;
        }

        .back-btn:hover {
            background: linear-gradient(135deg, #99bc85, #85a87d);
            color: white;
            border-color: #99bc85;
            transform: translateX(-6px);
            box-shadow: 0 4px 15px rgba(153, 188, 133, 0.2);
        }

        .loading-spinner {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
            flex-direction: column;
            gap: 16px;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(153, 188, 133, 0.2);
            border-top: 4px solid #99bc85;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading-text {
            color: #6c757d;
            font-size: 14px;
            animation: pulse-text 1.5s infinite;
        }

        @keyframes pulse-text {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            color: #6c757d;
            text-align: center;
        }

        .empty-state-icon {
            font-size: 56px;
            margin-bottom: 20px;
            opacity: 0.6;
            filter: grayscale(0.3);
        }

        .empty-state h3 {
            margin: 0 0 8px 0;
            font-size: 16px;
            font-weight: 600;
        }

        .empty-state p {
            margin: 0;
            font-size: 14px;
            opacity: 0.8;
        }

        .slide-in {
            animation: slideIn 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .slide-out {
            animation: slideOut 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(-100%);
                opacity: 0;
            }
        }

        /* Enhanced responsive design */
        @media (max-width: 768px) {
            .admin-chat-modal {
                width: calc(100vw - 32px);
                right: 16px;
                left: 16px;
                bottom: 90px;
                max-height: 80vh;
            }

            .admin-chat-wrapper {
                bottom: 20px;
                right: 20px;
            }
        }

        @media (max-width: 480px) {
            .chat-header {
                padding: 16px 20px;
                font-size: 15px;
            }

            .search-container,
            .chat-footer {
                padding: 16px 20px;
            }

            .user-item {
                padding: 14px 16px;
                margin: 4px 8px;
            }

            .user-avatar {
                width: 38px;
                height: 38px;
                font-size: 14px;
            }
        }
    </style>

    <div class="admin-chat-wrapper">
        <button class="admin-chat-btn" onclick="toggleAdminChatModal()">
            💬
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
                                <div class="user-status">Online</div>
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

    <script>
        function toggleAdminChatModal() {
            const modal = document.getElementById('adminChatModal');
            modal.classList.toggle('active');
        }

        function closeAdminChatModal() {
            document.getElementById('adminChatModal').classList.remove('active');
        }

        function loadMessages(userId, userName) {
            console.log('Loading messages for userId:', userId, 'userName:', userName);

            document.getElementById('chatHeaderTitle').innerText = 'Chat with ' + userName;
            document.getElementById('toUserId').value = userId;

            // Add slide animations
            const userList = document.getElementById('adminChatUserList');
            const chatBox = document.getElementById('adminChatBoxWrapper');

            userList.classList.add('slide-out');

            setTimeout(() => {
                userList.classList.add('d-none');
                userList.classList.remove('slide-out');
                chatBox.classList.remove('d-none');
                chatBox.classList.add('slide-in');
            }, 400);

            const chatContainer = document.getElementById('chatBox');
            chatContainer.innerHTML = '<div class="loading-spinner"><div class="spinner"></div><div class="loading-text">Loading messages...</div></div>';

            const url = "{{ url('admin/chat/messages') }}/" + userId;
            console.log('Fetching URL:', url);

            fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => {
                console.log('Response status:', res.status);
                console.log('Response headers:', res.headers);

                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                return res.json();
            })
            .then(data => {
                console.log('Messages data received:', data);
                chatContainer.innerHTML = '';

                if (!data || data.length === 0) {
                    chatContainer.innerHTML = `
                        <div class="empty-state">
                            <div class="empty-state-icon">💬</div>
                            <h3>No messages yet</h3>
                            <p>Start the conversation!</p>
                        </div>
                    `;
                } else {
                    data.forEach((msg, index) => {
                        console.log('Processing message', index, ':', msg);
                        const div = document.createElement('div');
                        div.className = 'message-bubble ' +
                            (msg.from_user_id == {{ auth()->id() }} ? 'message-sent' : 'message-received');
                        div.textContent = msg.message;
                        chatContainer.appendChild(div);
                    });
                }
                chatContainer.scrollTop = chatContainer.scrollHeight;
            })
            .catch(error => {
                console.error('Error loading messages:', error);
                chatContainer.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">❌</div>
                        <h3>Error loading messages</h3>
                        <p>${error.message}</p>
                    </div>
                `;
            });
        }

        function sendAdminMessage() {
            const userId = document.getElementById('toUserId').value;
            const input = document.getElementById('adminMessage');
            const message = input.value.trim();

            console.log('Sending message:', { userId, message });

            if (!message || !userId) {
                console.log('Message or userId is empty');
                return;
            }

            // Add sending animation
            const sendBtn = document.querySelector('.send-btn');
            sendBtn.style.transform = 'scale(0.9) rotate(360deg)';
            sendBtn.disabled = true;

            setTimeout(() => {
                sendBtn.style.transform = '';
            }, 400);

            fetch(`{{ route('admin.chat.send') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    to_user_id: parseInt(userId),
                    message: message
                })
            })
            .then(res => {
                console.log('Send response status:', res.status);
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                return res.json();
            })
            .then(data => {
                console.log('Send response data:', data);
                if (data.success) {
                    loadMessages(userId, document.getElementById('chatHeaderTitle').innerText.replace('Chat with ', ''));
                    input.value = '';
                    input.style.height = '48px';
                } else {
                    console.error('Send failed:', data.message);
                    alert('Gagal mengirim pesan: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error sending message:', error);
                alert('Terjadi kesalahan saat mengirim pesan');
            })
            .finally(() => {
                sendBtn.disabled = false;
            });
        }

        function backToUserList() {
            document.getElementById('chatHeaderTitle').innerText = 'User List';

            const userList = document.getElementById('adminChatUserList');
            const chatBox = document.getElementById('adminChatBoxWrapper');

            chatBox.classList.add('slide-out');

            setTimeout(() => {
                chatBox.classList.add('d-none');
                chatBox.classList.remove('slide-out', 'slide-in');
                userList.classList.remove('d-none');
                userList.classList.add('slide-in');
            }, 400);
        }

        function filterUserList(searchTerm) {
            const term = searchTerm.toLowerCase();
            const items = document.querySelectorAll('.user-item');

            items.forEach(item => {
                const name = item.getAttribute('data-name');
                const shouldShow = name.includes(term);

                if (shouldShow) {
                    item.style.display = '';
                    item.style.animation = 'fadeInUp 0.4s ease';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function handleMessageInput(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                sendAdminMessage();
            }
        }

        function autoResize(textarea) {
            textarea.style.height = '48px';
            textarea.style.height = Math.min(textarea.scrollHeight, 120) + 'px';
        }

        // Enhanced notification badge simulation
        document.addEventListener('DOMContentLoaded', function() {
            // Simulate new message notification
            setTimeout(() => {
                document.querySelector('.admin-chat-btn').classList.add('has-notification');
            }, 3000);
        });
    </script>
@endsection
