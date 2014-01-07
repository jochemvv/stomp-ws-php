<?php

namespace StompWs;

require_once("StompWsTestCase.php");

class StompWsTest extends StompWsTestCase
{
    public function testConnect()
    {
        $exception = null;

        try {
            $client = new \StompWs\StompWs($this->url, $this->origin);
            $client->connect();
            $client->disconnect();
        } catch (Exception $e) {
            $exception = $e;
        }

        $this->assertNull($exception);
    }

    public function testSubscribeWithOneMessage()
    {
        $testMessage = "test message";

        $subscriber = new \StompWs\StompWs($this->url, $this->origin);
        $subscriber->connect();
        $subscriber->subscribe($this->defaultDestination);

        $publisher = new \StompWs\StompWs($this->url, $this->origin);
        $publisher->connect();
        $publisher->send($testMessage, $this->defaultDestination);
        $publisher->disconnect();

        $message = $subscriber->receive();
        $subscriber->disconnect();

        $this->assertContains($testMessage, $message);
    }
}