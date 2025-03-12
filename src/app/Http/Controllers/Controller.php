<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class Controller
{
    public function sseResponse(callable $handler): StreamedResponse
    {
        $response = new StreamedResponse(function () use ($handler) {
            while (true) {
                $data = ['data' => call_user_func($handler)];
                echo 'data: '.json_encode($data)."\n\n";

                if (ob_get_contents()) {
                    ob_end_flush();
                }
                flush();

                sleep(3);
                if (connection_aborted()) {
                    break;
                }
            }
        });
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('X-Accel-Buffering', 'no');

        return $response;
    }
}
