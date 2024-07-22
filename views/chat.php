<!DOCTYPE html>
<html>
<head>
    <title>Chat</title>
</head>
<body>
    <h2>Chat Room</h2>
    <div id="messages"></div>
    <input type="text" id="message" placeholder="Type your message">
    <button onclick="sendMessage()">Send</button>

    <script>
        var conn = new WebSocket('ws://localhost:8080');
        conn.onopen = function(e) {
            console.log("Connection established!");
        };

        conn.onmessage = function(e) {
            var messages = document.getElementById('messages');
            var message = document.createElement('div');
            message.textContent = e.data;
            messages.appendChild(message);
        };

        function sendMessage() {
            var input = document.getElementById('message');
            conn.send(input.value);
            input.value = '';
        }
    </script>
</body>
</html>
