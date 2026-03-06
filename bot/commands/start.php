<?php
// bot/commands/start.php

function handleStart($message, $telegram, $db)
{
    $chatId = $message['chat']['id'];
    $from = $message['from'];
    $text = $message['text'];
    $username = $from['username'] ?? null;
    $firstName = $from['first_name'] ?? 'User';

    // 1. Check if user exists
    $user = $db->fetch("SELECT * FROM users WHERE telegram_id = :tid", [':tid' => $chatId]);

    $isNewUser = false;
    if (!$user) {
        $isNewUser = true;
        // Parse referral ID
        $referrerId = null;
        if (preg_match('/\/start ref_(\d+)/', $text, $matches)) {
            $referrerId = $matches[1];
        }

        // Save new user
        $db->query("INSERT INTO users (telegram_id, username, first_name, referrer_id) VALUES (:tid, :un, :fn, :rid)", [
            ':tid' => $chatId,
            ':un' => $username,
            ':fn' => $firstName,
            ':rid' => $referrerId
        ]);

        $user = $db->fetch("SELECT * FROM users WHERE telegram_id = :tid", [':tid' => $chatId]);
    }

    // 2. Check channel membership
    $config = require __DIR__ . '/../../config/telegram.php';
    $notJoined = [];
    foreach ($config['channel_ids'] as $channel) {
        if (!$telegram->isJoined($chatId, $channel)) {
            $notJoined[] = $channel;
        }
    }

    if (!empty($notJoined)) {
        $keyboard = ['inline_keyboard' => []];
        foreach ($notJoined as $channel) {
            $keyboard['inline_keyboard'][] = [['text' => "Join $channel", 'url' => "https://t.me/" . str_replace('@', '', $channel)]];
        }
        $keyboard['inline_keyboard'][] = [['text' => "Check Membership", 'callback_data' => "check_membership"]];

        $telegram->sendMessage($chatId, "Welcome! To participate in the contest, please join our channels first:", $keyboard);
        return;
    }

    // 3. If new user joined channels, add referral points to referrer
    if ($isNewUser && $user['referrer_id']) {
        // Anti-cheat: Check daily limit
        $today = date('Y-m-d');
        $refCount = $db->fetch("SELECT COUNT(*) as count FROM referrals WHERE referrer_id = :rid AND DATE(created_at) = :today", [
            ':rid' => $user['referrer_id'],
            ':today' => $today
        ]);

        if ($refCount['count'] < 10) { // Default limit from 13-Anti_Cheat_System.md
            $db->query("INSERT INTO referrals (referrer_id, referred_user_id) VALUES (:rid, :ruid)", [
                ':rid' => $user['referrer_id'],
                ':ruid' => $chatId
            ]);
            $db->query("UPDATE users SET score = score + 50 WHERE telegram_id = :rid", [':rid' => $user['referrer_id']]);
            $telegram->sendMessage($user['referrer_id'], "🎉 Someone joined using your link! You earned 50 points.");
        }
    }

    // 4. Show Main Menu
    showMainMenu($chatId, $telegram, $user);
}

function showMainMenu($chatId, $telegram, $user)
{
    $text = "Hello, {$user['first_name']}!\n\n";
    $text .= "🏆 Your Score: {$user['score']}\n";
    $text .= "🔥 Level: {$user['level']}\n\n";
    $text .= "Use the menu below to earn points:";

    $keyboard = [
        'keyboard' => [
            [['text' => "📅 Daily Check-in"], ['text' => "🧠 Quiz"]],
            [['text' => "📊 Leaderboard"], ['text' => "👥 Referrals"]],
            [['text' => "🎁 Redeem Code"], ['text' => "📱 Open Web Panel"]]
        ],
        'resize_keyboard' => true
    ];

    $telegram->sendMessage($chatId, $text, $keyboard);
}
