<?php
// This controller handles chat room functionalities if needed

// Example function to get users for chat
function getUsers($pdo) {
    $stmt = $pdo->query("SELECT username FROM users");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
