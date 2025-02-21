<?php

namespace NanoGram\NanoGram;

use GuzzleHttp\Exception\RequestException;
use NanoGram\NanoGram\Logger;
use GuzzleHttp\Client as GuzzleClient;

class Client {
    protected static ?GuzzleClient $client = null;

    public static function init(string $url): void {
        if (self::$client === null) {
            self::$client = new GuzzleClient([
                'base_uri' => $url,
                'timeout' => 10,
                'connect_timeout' => 5,
                'http_errors' => false,
            ]);
        }
    }

    public static function endpoint(string $method, array $data = []) {
        try {
            $response = self::$client->post($method, [
                'json' => $data
            ]);
    
            if ($response->getStatusCode() != 200) {
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
}
