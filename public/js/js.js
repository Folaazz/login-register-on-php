function sendMessage() {
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value;
    const selectedUser  = document.getElementById('selectedUser ').innerText;

    if (message && selectedUser ) {
        fetch('send_message.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ message: message, to: selectedUser  }),
        })
        .then(response => response.json())
        .then(data => {
            messageInput.value = '';
            loadMessages(selectedUser );
        })
        .catch(error => console.error('Ошибка:', error));
    }
}

function loadMessages(selectedUser ) {
    fetch('get_messages.php?to=' + encodeURIComponent(selectedUser ))
        .then(response => response.json())
        .then(data => {
            const messageList = document.getElementById('messageList');
            messageList.innerHTML = '';

            data.messages.forEach(message => {
                const messageItem = document.createElement('div');
                messageItem.textContent = message;
                messageList.appendChild(messageItem);
            });
        })
        .catch(error => console.error('Ошибка:', error));
}

setInterval(() => {
    const selectedUser  = document.getElementById('selectedUser ').innerText;
    if (selectedUser ) {
        loadMessages(selectedUser );
    }
}, 3000);

const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye-slash');
            this.querySelector('i').classList.toggle('fa-eye');
        });