<?php
session_start();
if (!isset($_SESSION["username"]) || !isset($_GET['with'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION["username"];
$chat_with = $_GET['with'];

// Database connection
$dsn = 'mysql:host=localhost;dbname=chat_application';
$db_username = 'root';
$db_password = '';

try {
    $pdo = new PDO($dsn, $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

require '../controllers/MessageController.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with <?php echo htmlspecialchars($chat_with); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
        }

        .chat-container {
            display: flex;
            flex-direction: column;
            width: 100%;
            height: 100%;
            max-width: 1200px;
            max-height: 800px;
            margin: auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative; /* Add this to position the logout button */
        }

        .header {
            padding: 30px;
            background-color: #007bff;
            color: #fff;
            text-align: center;
            font-size: 1.2em;
        }

        /* Logout button styles */
        #logout-button {
            position: absolute;
            top: 20px;
            right: 10px;
            background-color: #f44336; /* Red background */
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 5px;
        }

        #logout-button:hover {
            background-color: #e53935; /* Darker red on hover */
        }

        /* Other styles */
        .chat-content {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        .contacts-list {
            width: 30%;
            background-color: #f8f8f8;
            border-right: 1px solid #ddd;
            padding: 10px;
            overflow-y: auto;
        }

        .contacts-list h2 {
            text-align: center;
        }

        .contacts-list ul {
            list-style: none;
            padding: 0;
        }

        .contacts-list li {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .contacts-list li a {
            text-decoration: none;
            color: #333;
        }

        .contacts-list li a:hover {
            text-decoration: underline;
        }

        .chat-box {
            width: 70%;
            display: flex;
            flex-direction: column;
            padding: 10px;
        }

        .chat-box-header {
            padding: 10px;
            background-color: #f1f1f1;
            border-bottom: 1px solid #ddd;
        }

        .chat-box-header span {
            font-size: 1.2em;
            font-weight: bold;
        }

        .messages {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
            background-color: #fff;
        }

        .input-container {
            display: flex;
            padding: 10px;
            background-color: #f1f1f1;
            border-top: 1px solid #ddd;
        }

        .input-container input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }

        .input-container button {
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            color: #fff;
            cursor: pointer;
            border-radius: 4px;
        }

        .input-container button:hover {
            background-color: #0056b3;
        }

        .message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 4px;
            position: relative;
        }

        .message.user {
            background-color: #e1ffc7;
            align-self: flex-end;
        }

        .message.other {
            background-color: #f1f1f1;
            align-self: flex-start;
        }

        .message .timestamp {
            font-size: 0.8em;
            color: #888;
            position: absolute;
            bottom: 2px;
            right: 5px;
        }
        .user-profile {
            display: flex;
            align-items: center;
            padding: 20px;
            position: absolute;
            top: 20;
            left: 0;
            width: 100%;
            max-width: 300px;
        }
        .user-profile img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
            margin-left: 1px;
        }

        .user-profile .name {
            font-size: 1.2em;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <!-- Logout button -->
        <button id="logout-button" onclick="window.location.href='logout.php';">Logout</button>

        <div class="user-profile">
            <img src="../images/userphoto.jpeg" alt="User Photo">
            <div class="name"><?php echo "Hi, ".htmlspecialchars($username); ?></div>
        </div>

        <div class="header">Chat Application</div>
        <div class="chat-content">
            <div class="contacts-list">
                <h2>Contacts</h2>
                <ul>
                    <?php
                    $stmt = $pdo->query("SELECT username FROM users WHERE username != '$username'");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<li><a href='chat.php?with=" . htmlspecialchars($row['username']) . "'>" . htmlspecialchars($row['username']) . "</a></li>";
                    }
                    ?>
                </ul>
            </div>
            <div class="chat-box">
                <div class="chat-box-header">
                    <span>Chat with <?php echo htmlspecialchars($chat_with); ?></span>
                </div>
                <div class="messages" id="chat-box">
                    <?php
                    $messages = getMessages($pdo, $username, $chat_with);
                    foreach ($messages as $msg) {
                        $messageClass = $msg['sender'] === $username ? 'user' : 'other';
                        echo "<div class='message $messageClass'>" . htmlspecialchars($msg['message']) . "<span class='timestamp'>" . date('H:i:s', strtotime($msg['created_at'])) . "</span></div>";
                    }
                    ?>
                </div>
                <div class="input-container">
                    <input type="text" id="message-input" placeholder="Type a message...">
                    <button id="send-button">Send</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var ws = new WebSocket('ws://localhost:8080');

        ws.onopen = function() {
            console.log('WebSocket connection opened.');
            ws.send(JSON.stringify({ user: "<?php echo $username; ?>" }));
        };

        ws.onmessage = function(event) {
            var chatBox = document.getElementById('chat-box');
            var data = JSON.parse(event.data);
            if (data.to === "<?php echo $username; ?>" || data.from === "<?php echo $username; ?>") {
                var messageElement = document.createElement('div');
                messageElement.classList.add('message', data.from === "<?php echo $username; ?>" ? 'user' : 'other');
                messageElement.innerHTML = data.message + '<span class="timestamp">' + new Date().toLocaleTimeString() + '</span>';
                chatBox.appendChild(messageElement);
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        };

        ws.onerror = function(error) {
            console.error('WebSocket Error: ', error);
        };

        document.getElementById('send-button').addEventListener('click', function() {
            var messageInput = document.getElementById('message-input');
            var message = messageInput.value.trim();
            var chatWith = "<?php echo htmlspecialchars($chat_with); ?>";

            if (message) {
                var data = JSON.stringify({ message: message, from: "<?php echo $username; ?>", to: chatWith });
                console.log('Sending message:', data); 
                ws.send(data);
                messageInput.value = '';
            }
        });
    </script>
</body>
</html>

<?php
$pdo = null;
?>