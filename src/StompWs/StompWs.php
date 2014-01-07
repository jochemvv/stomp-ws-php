<?php

namespace StompWs;

use Wrench\Client;

class StompWs
{
    protected $webSocket;

    public function __construct($url, $origin)
    {
        $this->webSocket = new Client($url, $origin);
        $this->webSocket->addRequestHeader("Sec-WebSocket-Protocol", "v10.stomp, v11.stomp");
    }

    public function connect()
    {
        $this->webSocket->connect();

        $this->webSocket->sendData("CONNECT\naccept-version:1.2\nhost:localhost\n\n\x00");
        $data = $this->webSocket->receive();
        $message = $data[0]->getPayload();

        if (!(strpos($message, "CONNECTED") === 0)) {
            throw new Exception("Unexpected response: \n".$message);
        }
    }

    public function disconnect()
    {
        $this->webSocket->disconnect();
    }

    public function subscribe($destination)
    {
        $this->webSocket->sendData("SUBSCRIBE\ndestination:".$destination."\nack:auto\n\n\x00");
    }

    public function send($message, $destination)
    {
        $this->webSocket->sendData("SEND\ndestination:".$destination."\n\n".$message."\n\x00");
    }

    public function receive()
    {
        $data = $this->webSocket->receive();
        if (!is_string($data)) {
            return $data[1]->getPayload();
        } else {
            return null;
        }
    }
}
