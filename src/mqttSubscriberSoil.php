<?php

require "vendor/autoload.php";
$server   = '192.168.1.105';
$port     = 1883;
$clientId = 'test-publisher';

$mqtt = new \PhpMqtt\Client\MqttClient($server, $port, $clientId);
$connectionSettings = (new \PhpMqtt\Client\ConnectionSettings)
    ->setUsername("admin")
    ->setPassword("admin")
    ->setUseTls(false);
$mqtt->connect();
$mqtt->subscribe('sensors/soil', function ($topic, $message, $retained, $matchedWildcards) {
    echo sprintf("Received message on topic [%s]: %s\n", $topic, $message);
}, 0);
$mqtt->loop(true);
$mqtt->disconnect();