<?php

namespace NanoGram\NanoGram;

use NanoGram\NanoGram\Client;
use NanoGram\NanoGram\Logger;
use GuzzleHttp\Exception\RequestException;

class Request {

    private static $fetch;

    public static function __callStatic($method, $arguments)
    {
        $data = $arguments[0] ?? [];

        try {
            $response = Client::endpoint($method, $data);

            if ($response->getStatusCode() !== 200) {
                $errorMessage = "Request failed with status code " . $response->getStatusCode();
                Logger::logError($errorMessage);
                throw new \Exception($errorMessage);
            }

            return $response;
        } catch (RequestException $e) {
            Logger::logError($e->getMessage());
            throw $e;
        }
    }

    public static function fetch($response)
    {
        if ($response instanceof \GuzzleHttp\Psr7\Response) {
            return json_decode($response->getBody()->getContents())->result;
        }

        return null;
    }
}