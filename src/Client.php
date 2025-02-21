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
                'base_uri' => "$url/",
                'timeout' => 10,
                'connect_timeout' => 5,
                'http_errors' => false,
            ]);
        }
    }

    public static function endpoint(string $method, array $data = []) {
        if (!self::$client) {
            throw new \RuntimeException("Client is not initialized. Call Client::init() first.");
        }
    
        try {
            $response = self::$client->post($method, ['json' => $data]);
            $statusCode = $response->getStatusCode();
            $body = (string) $response->getBody();
            $decodedBody = json_decode($body, true);
    
            if ($statusCode !== 200 || (isset($decodedBody['ok']) && !$decodedBody['ok'])) {
                $errorMessage = $decodedBody['description'] ?? "Unexpected error occurred.";
                Logger::logError("Request failed - Status: {$statusCode}, Error: {$errorMessage}");
                throw new ("Request failed with status code {$statusCode}: {$errorMessage}");
            }
    
            return $decodedBody;
            
        } catch (RequestException $e) {
            $errorResponse = $e->getResponse();
            $errorMessage = $errorResponse ? (string) $errorResponse->getBody() : $e->getMessage();
            Logger::logError("HTTP Request Exception: {$errorMessage}");
            throw new \Exception("HTTP Request Exception: {$errorMessage}", 0, $e);
        } catch (\Exception $e) {
            Logger::logError("General Exception: " . $e->getMessage());
            throw $e;
        }
    }
}
