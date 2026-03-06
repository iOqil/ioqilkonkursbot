<?php
// bot/commands/code.php

function handleCode($message, $telegram, $db)
{
    $chatId = $message['chat']['id'];
    $text = $message['text'];

    // Parse code
    $parts = explode(' ', $text);
    if (count($parts) < 2) {
        $telegram->sendMessage($chatId, "❌ Usage: /code YOUR_CODE_HERE");
        return;
    }
    $codeStr = trim($parts[1]);

    // 1. Find code in database
    $code = $db->fetch("SELECT * FROM codes WHERE code = :code", [':code' => $codeStr]);

    if (!$code) {
        $telegram->sendMessage($chatId, "❌ Invalid reward code.");
        return;
    }

    // 2. Check if expired
    if ($code['expires_at'] && strtotime($code['expires_at']) < time()) {
        $telegram->sendMessage($chatId, "❌ This code has expired.");
        return;
    }

    // 3. Check usage limit
    if ($code['max_uses'] > 0) {
        $usageCount = $db->fetch("SELECT COUNT(*) as count FROM code_claims WHERE code_id = :cid", [':cid' => $code['id']]);
        if ($usageCount['count'] >= $code['max_uses']) {
            $telegram->sendMessage($chatId, "❌ This code has reached its maximum usage limit.");
            return;
        }
    }

    // 4. Check if user already claimed
    $claim = $db->fetch("SELECT * FROM code_claims WHERE user_id = :uid AND code_id = :cid", [
        ':uid' => $chatId,
        ':cid' => $code['id']
    ]);

    if ($claim) {
        $telegram->sendMessage($chatId, "❌ You have already redeemed this code.");
        return;
    }

    // 5. Success - Add points and record claim
    $db->query("INSERT INTO code_claims (user_id, code_id) VALUES (:uid, :cid)", [
        ':uid' => $chatId,
        ':cid' => $code['id']
    ]);

    $db->query("UPDATE users SET score = score + :p WHERE telegram_id = :uid", [
        ':p' => $code['points'],
        ':uid' => $chatId
    ]);

    $telegram->sendMessage($chatId, "🎁 Success! You redeemed the code <b>{$codeStr}</b> and earned <b>{$code['points']}</b> points.");
}
