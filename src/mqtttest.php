<?php

require "vendor/autoload.php";
// $server   = '192.168.35.94';
$server   = '192.168.1.105';
$port     = 1883;
$clientId = 'test-publisher';

$mqtt = new \PhpMqtt\Client\MqttClient($server, $port, $clientId);

// $connectionSettings = (new \PhpMqtt\Client\ConnectionSettings)
//     ->setUsername("admin")
//     ->setPassword("admin")
//     ->setUseTls(false);
$mqtt->connect();
$mqtt->publish('php-mqtt/client/test', 'Hello World!', 0);
$mqtt->disconnect();
