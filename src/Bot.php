<?php

namespace NanoGram\NanoGram;

use NanoGram\NanoGram\Client;
use NanoGram\NanoGram\Logger;

class Bot {

    private ?string $bot_token;
    private ?string $base_url;
    
    public function __construct(string $bot_token, string $base_url = "https://api.telegram.org/bot") {
        $base_url = rtrim($base_url, '/');
        $this->bot_token = $bot_token;
        $this->base_url  = "{$base_url}{$bot_token}/";
        Client::init($this->base_url);
        Logger::init();
    }
    
}
