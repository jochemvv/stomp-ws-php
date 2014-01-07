<?php

require __DIR__.'/../vendor/autoload.php';

$subscriber = new \StompWs\StompWs("ws://localhost:61618/", "http://localhost");
$subscriber->connect();
$subscriber->subscribe("/topic/test");

$publisher = new \StompWs\StompWs("ws://localhost:61618/", "http://localhost");
$publisher->connect();
$publisher->send("test message", "/topic/test");
$publisher->disconnect();

$message = $subscriber->receive();

echo "received message:\n".$message;

$subscriber->disconnect();