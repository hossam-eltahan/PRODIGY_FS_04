<?php
// This controller handles user-related functionalities

function getUser($pdo, $username) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getUsersList($pdo, $excludeUsername) {
    $stmt = $pdo->prepare("SELECT username FROM users WHERE username != ?");
    $stmt->execute([$excludeUsername]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
