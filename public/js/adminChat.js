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

// Global variables
const adminChannel = pusher.subscribe(`private-chat.${window.AUTH_USER_ID}`);
let currentChatUserId = null;
let unreadCounts = {};

// Event Listeners
adminChannel.bind('message.sent', function(data) {
    console.log('New message received:', data);

    if (currentChatUserId && (data.from_user_id == currentChatUserId || data.to_user_id == currentChatUserId)) {
        displayNewMessage(data);
    } else {
        updateUnreadCount(data.from_user_id);
        showNotificationBadge();
    }
});

// Message Display Functions
function displayNewMessage(data) {
    const chatContainer = document.getElementById('chatBox');
    const messageDiv = document.createElement('div');

    messageDiv.className = 'message-bubble ' +
        (data.from_user_id == window.AUTH_USER_ID ? 'message-sent' : 'message-received');

    messageDiv.innerHTML = `
        <div class="message-content">${data.message}</div>
        <div class="message-time">${data.timestamp}</div>
    `;

    chatContainer.appendChild(messageDiv);
    chatContainer.scrollTop = chatContainer.scrollHeight;

    // Add smooth animation
    messageDiv.style.opacity = '0';
    messageDiv.style.transform = 'translateY(20px)';

    requestAnimationFrame(() => {
        messageDiv.style.transition = 'all 0.3s ease';
        messageDiv.style.opacity = '1';
        messageDiv.style.transform = 'translateY(0)';
    });
}

// Notification Functions
function updateUnreadCount(userId) {
    if (!unreadCounts[userId]) {
        unreadCounts[userId] = 0;
    }
    unreadCounts[userId]++;

    const unreadBadge = document.getElementById(`unread-${userId}`);
    if (unreadBadge) {
        const countSpan = unreadBadge.querySelector('.unread-count');
        countSpan.textContent = unreadCounts[userId];
        unreadBadge.style.display = 'block';
    }
}

function showNotificationBadge() {
    const badge = document.getElementById('notificationBadge');
    const totalUnread = Object.values(unreadCounts).reduce((sum, count) => sum + count, 0);

    if (totalUnread > 0) {
        badge.textContent = totalUnread > 99 ? '99+' : totalUnread;
        badge.style.display = 'block';
    }
}

function clearUnreadCount(userId) {
    unreadCounts[userId] = 0;
    const unreadBadge = document.getElementById(`unread-${userId}`);
    if (unreadBadge) {
        unreadBadge.style.display = 'none';
    }
    showNotificationBadge();
}

// UI Control Functions
function toggleAdminChatModal() {
    const modal = document.getElementById('adminChatModal');
    modal.classList.toggle('active');
}

function closeAdminChatModal() {
    document.getElementById('adminChatModal').classList.remove('active');
}

function loadMessages(userId, userName) {
    console.log('Loading messages for userId:', userId, 'userName:', userName);

    currentChatUserId = userId;
    clearUnreadCount(userId);

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

    fetch(`${window.ADMIN_CHAT_MESSAGES_URL}/${userId}`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': window.CSRF_TOKEN
        }
    })
    .then(res => {
        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
        return res.json();
    })
    .then(data => {
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
            data.forEach(msg => {
                const div = document.createElement('div');
                div.className = 'message-bubble ' +
                    (msg.from_user_id == window.AUTH_USER_ID ? 'message-sent' : 'message-received');

                const messageTime = new Date(msg.created_at).toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                div.innerHTML = `
                    <div class="message-content">${msg.message}</div>
                    <div class="message-time">${messageTime}</div>
                `;

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

// Message Handling Functions
function sendAdminMessage() {
    const userId = document.getElementById('toUserId').value;
    const input = document.getElementById('adminMessage');
    const message = input.value.trim();

    if (!message || !userId) return;

    // UI feedback
    const sendBtn = document.querySelector('.send-btn');
    sendBtn.style.transform = 'scale(0.9) rotate(360deg)';
    sendBtn.disabled = true;

    // Show typing indicator
    const chatContainer = document.getElementById('chatBox');
    const typingDiv = document.createElement('div');
    typingDiv.className = 'typing-indicator';
    typingDiv.id = 'typingIndicator';
    typingDiv.innerHTML = '<span></span><span></span><span></span>';
    chatContainer.appendChild(typingDiv);
    chatContainer.scrollTop = chatContainer.scrollHeight;

    setTimeout(() => sendBtn.style.transform = '', 400);

    fetch(window.ADMIN_CHAT_SEND_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': window.CSRF_TOKEN,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            to_user_id: parseInt(userId),
            message: message
        })
    })
    .then(res => {
        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
        return res.json();
    })
    .then(data => {
        const indicator = document.getElementById('typingIndicator');
        if (indicator) indicator.remove();

        if (data.success) {
            input.value = '';
            input.style.height = '48px';
        } else {
            throw new Error(data.message);
        }
    })
    .catch(error => {
        console.error('Error sending message:', error);
        const indicator = document.getElementById('typingIndicator');
        if (indicator) indicator.remove();
        alert('Error sending message: ' + error.message);
    })
    .finally(() => {
        sendBtn.disabled = false;
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

function backToUserList() {
    currentChatUserId = null;
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
        if (name.includes(term)) {
            item.style.display = '';
            item.style.animation = 'fadeInUp 0.4s ease';
        } else {
            item.style.display = 'none';
        }
    });
}

// Connection status handling
pusher.connection.bind('connected', () => console.log('Connected to Pusher'));
pusher.connection.bind('disconnected', () => console.log('Disconnected from Pusher'));
pusher.connection.bind('error', (error) => console.error('Pusher connection error:', error));

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    console.log('Admin chat initialized with real-time messaging');
});

