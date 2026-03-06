<?php
// config/telegram.php

require_once __DIR__ . '/../app/helpers/Env.php';
\App\Helpers\Env::load(__DIR__ . '/../.env');

use \App\Helpers\Env;

$adminIdsRaw = Env::get('TELEGRAM_ADMIN_IDS', '');
$channelIdsRaw = Env::get('TELEGRAM_CHANNEL_IDS', '');

$adminIds = $adminIdsRaw ? explode(',', $adminIdsRaw) : [];
$channelIds = $channelIdsRaw ? explode(',', $channelIdsRaw) : [];

return [
    'bot_token' => Env::get('TELEGRAM_BOT_TOKEN', ''),
    'admin_ids' => array_map('trim', $adminIds),
    'channel_ids' => array_map('trim', $channelIds),
    'webhook_url' => Env::get('TELEGRAM_WEBHOOK_URL', ''),
];
