<?php

namespace StompWs;

class StompWsTestCase extends \PHPUnit_Framework_TestCase
{
    protected $url = "ws://localhost:61618/";
    protected $origin = "http://localhost";
    protected $defaultDestination = "/topic/test";

    /**
     * adding to get rid of "No tests found in class "StompWs\StompWsTestCase"." error
     */
    public function testCreate()
    {
        $client = new \StompWs\StompWs($this->url, $this->origin);
        $client = null;
    }
}