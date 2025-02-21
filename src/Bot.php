<?php

namespace NanoGram\NanoGram;

class Bot {

    private ?string $bot_token;
    private ?string $base_url;
    
    public function __construct(string $bot_token, string $base_url = "https://api.telegram.org/bot") {
        $this->bot_token = $bot_token;
        $this->base_url  = "{$base_url}/{$bot_token}";
    }

}