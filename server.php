<?php
require __DIR__ . '/vendor/autoload.php';
require 'config/config.php'; // Include your database connection

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Server\IoServer;

class Chat implements MessageComponentInterface {
    protected $clients;
    protected $users;
    protected $pdo;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->users = [];
        global $pdo;
        $this->pdo = $pdo;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);

        // Handle new user connections
        if (isset($data['user'])) {
            $this->users[$data['user']] = $from;
            return;
        }

        $message = htmlspecialchars($data['message']);
        $fromUser = htmlspecialchars($data['from']);
        $toUser = htmlspecialchars($data['to']);

        // Save the message to the database
        $stmt = $this->pdo->prepare("INSERT INTO messages (sender, receiver, message) VALUES (?, ?, ?)");
        $stmt->execute([$fromUser, $toUser, $message]);

        if (isset($this->users[$toUser])) {
            $toClient = $this->users[$toUser];
            $toClient->send(json_encode(['message' => $message, 'from' => $fromUser, 'to' => $toUser]));
        }

        // Send the message to the sender to update their own chat window
        $from->send(json_encode(['message' => $message, 'from' => $fromUser, 'to' => $toUser]));
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    8080
);

$server->run();