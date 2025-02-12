<?php 
namespace App\Contracts;

use App\Models\Measurement;

interface TopicHandlerInterface
{
    public function save(array $message): Measurement|array;
}