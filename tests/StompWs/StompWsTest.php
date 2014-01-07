<?php

namespace StompWs;

class StompWsTest extends \PHPUnit_Framework_TestCase
{
    public function testConnect()
    {
        $exception = null;

        try {
            $client = new \StompWs\StompWs("ws://localhost:61618/", "http://localhost");
            $client->connect();
            $client->disconnect();
        } catch (Exception $e) {
            $exception = $e;
        }

        $this->assertNull($exception);
    }
}