<?php

require __DIR__.'/../vendor/autoload.php';

$subscriber = new \StompWs\StompWs("ws://localhost:61618/", "http://localhost");
$subscriber->clientId = "test";
$subscriber->connect();
$subscriber->subscribe("/topic/test", array('activemq.subscriptionName' => 'test'));
$subscriber->disconnect();

$publisher = new \StompWs\StompWs("ws://localhost:61618/", "http://localhost");
$publisher->connect();
$publisher->send("/topic/test", "test message");
$publisher->disconnect();

$subscriber = new \StompWs\StompWs("ws://localhost:61618/", "http://localhost");
$subscriber->clientId = "test";
$subscriber->connect();
$subscriber->subscribe("/topic/test", array('activemq.subscriptionName' => 'test'));
$message = $subscriber->readFrame();

echo "received message:\n".$message->body;

$subscriber->disconnect();