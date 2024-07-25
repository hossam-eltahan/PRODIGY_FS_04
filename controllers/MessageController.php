<?php
function saveMessage($pdo, $from, $to, $message) {
    $stmt = $pdo->prepare("INSERT INTO messages (content, sender, receiver, created_at) VALUES (?, ?, ?, NOW())");
    if ($stmt === false) {
        die("Failed to prepare SQL statement: " . print_r($pdo->errorInfo(), true));
    }
    $stmt->execute([$message, $from, $to]);
}


function getMessages($pdo, $username, $chat_with) {
    $stmt = $pdo->prepare("SELECT * FROM messages WHERE (sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?) ORDER BY created_at ASC");
    $stmt->execute([$username, $chat_with, $chat_with, $username]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
