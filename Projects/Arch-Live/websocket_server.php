<?php
error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();

$host = "0.0.0.0";  // Listen on all interfaces
$port = 8080;
$clients = [];

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
socket_bind($socket, $host, $port);
socket_listen($socket);

echo "WebSocket Server started on port $port\n";

while (true) {
    $changed = $clients;
    $changed[] = $socket;
    
    socket_select($changed, $null, $null, 0, 10);

    if (in_array($socket, $changed)) {
        $client = socket_accept($socket);
        $clients[] = $client;
        $header = socket_read($client, 1024);
        perform_handshake($client, $header, $host, $port);
        
        socket_getpeername($client, $ip);
        echo "New client connected: $ip\n";
        
        $found_socket = array_search($socket, $changed);
        unset($changed[$found_socket]);
    }

    foreach ($changed as $client) {
        $data = @socket_read($client, 1024, PHP_NORMAL_READ);
        if ($data === false) {
            $found_socket = array_search($client, $clients);
            unset($clients[$found_socket]);
            socket_close($client);
            continue;
        }

        $data = trim($data);
        if (!empty($data)) {
            echo "Received: $data\n";
            broadcast($data, $clients);
        }
    }
}

socket_close($socket);

function perform_handshake($client, $header, $host, $port) {
    $headers = [];
    preg_match("/Sec-WebSocket-Key: (.*)\r\n/", $header, $matches);
    $secKey = $matches[1];
    $secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));

    $response = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n"
              . "Upgrade: websocket\r\n"
              . "Connection: Upgrade\r\n"
              . "Sec-WebSocket-Accept: $secAccept\r\n\r\n";
              
    socket_write($client, $response, strlen($response));
}

function broadcast($message, $clients) {
    foreach ($clients as $client) {
        socket_write($client, $message, strlen($message));
    }
}
