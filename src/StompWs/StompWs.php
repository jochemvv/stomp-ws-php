<?php

namespace StompWs;

use Wrench\Client;
use FuseSource\Stomp\Stomp;
use FuseSource\Stomp\Frame;
use FuseSource\Stomp\Message\Map;

class StompWs extends Stomp
{
    protected $webSocket;

    public function __construct($url, $origin)
    {
        parent::__construct($url);

        $this->webSocket = new Client($url, $origin);
        $this->webSocket->addRequestHeader("Sec-WebSocket-Protocol", "v10.stomp, v11.stomp");
    }

    protected function _makeConnection()
    {
        $this->webSocket->connect();
    }

    protected function _writeFrame(Frame $stompFrame)
    {
        if (!$this->webSocket->isConnected()) {
            throw new StompException('Socket connection hasn\'t been established');
        }

        $data = $stompFrame->__toString();
        $this->webSocket->sendData($data);
    }

    public function readFrame()
    {
        if (!empty($this->_waitbuf)) {
            return array_shift($this->_waitbuf);
        }

        if (!$this->webSocket->isConnected()) {
            throw new StompException('Socket connection hasn\'t been established');
        }

        $data = $this->webSocket->receive();

        if (!is_string($data) && count($data) > 0) {
            $message = reset($data)->getPayload();

            list ($header, $body) = explode("\n\n", $message, 2);
            $header = explode("\n", $header);
            $headers = array();
            $command = null;
            foreach ($header as $v) {
                if (isset($command)) {
                    list ($name, $value) = explode(':', $v, 2);
                    $headers[$name] = $value;
                } else {
                    $command = $v;
                }
            }
            $frame = new Frame($command, $headers, trim($body));
            if (isset($frame->headers['transformation']) && $frame->headers['transformation'] == 'jms-map-json') {
                return new Map($frame);
            } else {
                return $frame;
            }
        } else {
            return false;
        }
    }

    public function disconnect()
    {
        parent::disconnect();
        $this->webSocket->disconnect();
    }

    public function isConnected ()
    {
        return !empty($this->_sessionId) && $this->webSocket->isConnected();
    }

    public function hasFrameToRead()
    {
        return false;
    }

    public function __destruct()
    {
        $this->disconnect();
    }
}
