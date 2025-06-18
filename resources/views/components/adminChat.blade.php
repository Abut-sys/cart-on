<div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Chat with <span id="chatUserName"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="chatMessagesContainer" style="max-height: 400px; overflow-y: auto;"></div>
        <div class="d-flex mt-3">
            <input type="text" id="chatMessageInput" class="form-control me-2" placeholder="Type a message...">
            <button class="btn btn-success" onclick="sendAdminMessage()">Send</button>
        </div>
        <input type="hidden" id="chatUserId">
        <input type="hidden" id="currentUserId" value="{{ auth()->id() }}">
      </div>
    </div>
  </div>
</div>

<script>
    function openChatModal(userId, userName) {
        document.getElementById('chatUserName').textContent = userName;
        document.getElementById('chatUserId').value = userId;
        document.getElementById('chatMessagesContainer').innerHTML = 'Loading...';

        fetch(`/api/chat/${userId}`)
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('chatMessagesContainer');
                const currentUserId = document.getElementById('currentUserId').value;
                container.innerHTML = '';

                data.forEach(msg => {
                    const div = document.createElement('div');
                    const isCurrentUser = msg.from_user_id == currentUserId;
                    div.className = isCurrentUser ? 'text-end mb-2' : 'text-start mb-2';
                    div.innerHTML = `<span class="badge bg-${isCurrentUser ? 'success' : 'secondary'}">${msg.message}</span>`;
                    container.appendChild(div);
                });

                container.scrollTop = container.scrollHeight;
            });

        new bootstrap.Modal(document.getElementById('chatModal')).show();
    }

    function sendAdminMessage() {
        const message = document.getElementById('chatMessageInput').value;
        const userId = document.getElementById('chatUserId').value;

        fetch(`{{ route('admin.chat.send') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                to_user_id: userId,
                message: message
            })
        }).then(response => {
            if (response.ok) {
                document.getElementById('chatMessageInput').value = '';
                openChatModal(userId, document.getElementById('chatUserName').textContent); // Refresh chat
            }
        });
    }
</script>
