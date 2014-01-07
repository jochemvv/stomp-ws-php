<?php

namespace StompWs;

class StompWsTestCase extends \PHPUnit_Framework_TestCase
{
    protected $url = "ws://localhost:61618/";
    protected $origin = "http://localhost";
    protected $defaultDestination = "/topic/test";
}