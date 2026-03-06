<?php
// bot/webhook.php

require_once __DIR__ . '/../app/helpers/Database.php';
require_once __DIR__ . '/../app/helpers/Telegram.php';

use App\Helpers\Database;
use App\Helpers\Telegram;

$telegram = new Telegram();
$db = Database::getInstance();

$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (!$update) {
    exit;
}

if (isset($update['message'])) {
    $message = $update['message'];
    $chatId = $message['chat']['id'];
    $text = $message['text'] ?? '';
    $from = $message['from'];

    // Basic Command Router
    if (strpos($text, '/start') === 0) {
        require_once __DIR__ . '/commands/start.php';
        handleStart($message, $telegram, $db);
    } elseif ($text === '/checkin') {
        require_once __DIR__ . '/commands/checkin.php';
        handleCheckin($message, $telegram, $db);
    } elseif (strpos($text, '/code') === 0) {
        require_once __DIR__ . '/commands/code.php';
        handleCode($message, $telegram, $db);
    } elseif ($text === '/leaderboard') {
        require_once __DIR__ . '/commands/leaderboard.php';
        handleLeaderboard($message, $telegram, $db);
    } elseif ($text === '/quiz') {
        require_once __DIR__ . '/commands/quiz.php';
        handleQuiz($message, $telegram, $db);
    }
} elseif (isset($update['callback_query'])) {
    $callbackQuery = $update['callback_query'];
    $data = $callbackQuery['data'];
    $message = $callbackQuery['message'];
    $chatId = $message['chat']['id'];

    if (strpos($data, 'quiz_') === 0) {
        require_once __DIR__ . '/commands/quiz.php';
        handleQuizCallback($callbackQuery, $telegram, $db);
    }
}
