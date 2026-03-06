<?php
// app/helpers/Telegram.php

namespace App\Helpers;

class Telegram
{
    private $token;
    private $apiUrl;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/telegram.php';
        $this->token = $config['bot_token'];
        $this->apiUrl = "https://api.telegram.org/bot{$this->token}/";
    }

    public function sendRequest($method, $params = [])
    {
        $url = $this->apiUrl . $method;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }

    public function sendMessage($chatId, $text, $keyboard = null, $parseMode = 'HTML')
    {
        $params = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => $parseMode
        ];
        if ($keyboard) {
            $params['reply_markup'] = json_encode($keyboard);
        }
        return $this->sendRequest('sendMessage', $params);
    }

    public function getChatMember($chatId, $userId)
    {
        return $this->sendRequest('getChatMember', [
            'chat_id' => $chatId,
            'user_id' => $userId
        ]);
    }

    public function isJoined($userId, $channelId)
    {
        $response = $this->getChatMember($channelId, $userId);
        if (isset($response['ok']) && $response['ok']) {
            $status = $response['result']['status'];
            return in_array($status, ['creator', 'administrator', 'member']);
        }
        return false;
    }

    public function answerCallbackQuery($callbackQueryId, $text = null, $showAlert = false)
    {
        $params = ['callback_query_id' => $callbackQueryId];
        if ($text) {
            $params['text'] = $text;
            $params['show_alert'] = $showAlert;
        }
        return $this->sendRequest('answerCallbackQuery', $params);
    }
}
