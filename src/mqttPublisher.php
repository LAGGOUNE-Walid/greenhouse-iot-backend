<?php

require 'vendor/autoload.php';
$server = 'mosquitto-server';
$port = 1883;
$clientId = 'test-publisher-php';

$mqtt = new \PhpMqtt\Client\MqttClient($server, $port, $clientId);
$mqtt->connect();
$data = [
    'node_id' => 1,
    'temperature' => 1,
    'humidity' => 2,
    'pressure' => 3,
];
$mqtt->publish('sensors/air', json_encode($data), 0);
$mqtt->disconnect();
