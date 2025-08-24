<?php
require dirname(__DIR__) . '/Youmanitarian/vendor/autoload.php';
require 'config.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class ChatServer implements MessageComponentInterface {
    protected $clients;
    private $db;
    private $userConnections = [];

    public function __construct() {
        $this->clients = new \SplObjectStorage();
        $this->db = new mysqli($_ENV['DB_HOST'], 
                            $_ENV['DB_USERNAME'], 
                            $_ENV['DB_PASSWORD'], 
                            $_ENV['DB_DATABASE']);

        if ($this->db->connect_error) {
            die("Database connection failed: " . $this->db->connect_error);
        }
    }

    public function onOpen(ConnectionInterface $conn) {
        if (!$this->clients) {
            $this->clients = new \SplObjectStorage();
        }
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);
    
        if (isset($data['type']) && $data['type'] === 'message') {
            $sender_id = (int) $data['sender_id'];
            $receiver_id = (int) $data['receiver_id'];
            $message = $data['message'];
            $message_id = $data['message_id']; // Get message ID from AJAX response
    
            // Broadcast message to all connected clients
            foreach ($this->clients as $client) {
                $client->send(json_encode([
                    'type' => 'message',
                    'sender_id' => $sender_id,
                    'receiver_id' => $receiver_id,
                    'message' => $message,
                    'message_id' => $message_id,
                    "created_at" => date("Y-m-d H:i:s")
                ]));
            }
        }
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

// Start the WebSocket server
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new ChatServer()
        )
    ),
    8080
);

echo "WebSocket server started on ws://localhost:8080\n";
$server->run(); 