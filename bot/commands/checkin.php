<?php
// bot/commands/checkin.php

function handleCheckin($message, $telegram, $db)
{
    $chatId = $message['chat']['id'];
    $today = date('Y-m-d');

    // 1. Check last checkin
    $lastCheckin = $db->fetch("SELECT * FROM checkins WHERE user_id = :uid ORDER BY checkin_date DESC LIMIT 1", [':uid' => $chatId]);

    if ($lastCheckin && $lastCheckin['checkin_date'] === $today) {
        $telegram->sendMessage($chatId, "✅ You have already checked in today! Come back tomorrow.");
        return;
    }

    $streak = 1;
    if ($lastCheckin) {
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        if ($lastCheckin['checkin_date'] === $yesterday) {
            $streak = $lastCheckin['streak'] + 1;
        }
    }

    // 2. Add points (e.g., 10 base + streak bonus)
    $points = 10 + (min($streak, 7) * 5); // Max streak bonus for 7 days

    $db->query("INSERT INTO checkins (user_id, checkin_date, streak) VALUES (:uid, :date, :streak)", [
        ':uid' => $chatId,
        ':date' => $today,
        ':streak' => $streak
    ]);

    $db->query("UPDATE users SET score = score + :p WHERE telegram_id = :uid", [
        ':p' => $points,
        ':uid' => $chatId
    ]);

    $messageText = "📅 Daily Check-in Successful!\n\n";
    $messageText .= "💰 Points earned: +$points\n";
    $messageText .= "🔥 Current Streak: $streak days\n\n";
    $messageText .= "Keep it up to earn more bonus points!";

    $telegram->sendMessage($chatId, $messageText);
}
