<?php
// app/services/AuthService.php

namespace App\Services;

class AuthService
{
    private $botToken;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/telegram.php';
        $this->botToken = $config['bot_token'];
    }

    /**
     * Validates data received from Telegram WebApp
     * @param string $initData The query string from Telegram.WebApp.initData
     * @return array|false Returns user data array if valid, false otherwise
     */
    public function validateWebAppData($initData)
    {
        parse_str($initData, $data);

        if (!isset($data['hash'])) {
            return false;
        }

        $hash = $data['hash'];
        unset($data['hash']);

        // Sort keys alphabetically
        ksort($data);

        $dataCheckString = "";
        foreach ($data as $key => $value) {
            $dataCheckString .= "$key=$value\n";
        }
        $dataCheckString = rtrim($dataCheckString, "\n");

        $secretKey = hash_hmac('sha256', $this->botToken, "WebAppData", true);
        $checkHash = hash_hmac('sha256', $dataCheckString, $secretKey);

        if (hash_equals($checkHash, $hash)) {
            return json_decode($data['user'], true);
        }

        return false;
    }
}
