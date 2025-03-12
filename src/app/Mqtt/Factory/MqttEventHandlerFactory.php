<?php

namespace App\Mqtt\Factory;

use App\Contracts\TopicHandlerInterface;
use App\DataTransferObject\MqttAirMessage;
use App\DataTransferObject\MqttBatteryMessage;
use App\DataTransferObject\MqttSoilMessage;
use App\Enums\NodeType;
use App\Models\Node;
use App\Mqtt\Handlers\AirHandler;
use App\Mqtt\Handlers\BatteryHandler;
use App\Mqtt\Handlers\SoilMoistureHandler;
use Exception;

abstract class MqttEventHandlerFactory
{
    abstract public function getHandler(): TopicHandlerInterface;

    abstract public function getNodeType(): ?NodeType;

    public function handle(array $message): bool
    {
        $handler = $this->getHandler();
        $nodeId = $message['node_id'];
        unset($message['node_id']);

        if ($handler instanceof BatteryHandler and $this->nodeExists($nodeId)) {
            return $handler->save(new MqttBatteryMessage($nodeId, $message['batteryLevel']));
        }

        if ($handler instanceof AirHandler) {
            try {
                $message = new MqttAirMessage($nodeId, $message['temperature'], $message['humidity'], $message['pressure']);
            } catch (\Throwable $th) {
                throw $th;
            }
        }

        if ($handler instanceof SoilMoistureHandler) {
            try {
                if (count($message) === 0) {
                    throw new Exception;
                }
                $message = new MqttSoilMessage($nodeId, $message);
            } catch (\Throwable $th) {
                throw $th;
            }
        }
        $handler = $this->getHandler();
        $this->createNodeIfNotExists($nodeId, $this->getNodeType());

        return $handler->save($message);
    }

    public function createNodeIfNotExists(int $nodeId, NodeType $nodeType): Node
    {
        return Node::firstOrCreate(['id' => $nodeId, 'type' => $nodeType]);
    }

    public function nodeExists(int $nodeId): ?Node
    {
        return Node::where('id', $nodeId)->first();
    }
}
