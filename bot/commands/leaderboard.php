<?php
// bot/commands/leaderboard.php

function handleLeaderboard($message, $telegram, $db)
{
    $chatId = $message['chat']['id'];

    // 1. Fetch top 10 users
    $topUsers = $db->fetchAll("SELECT first_name, username, score FROM users ORDER BY score DESC LIMIT 10");

    $text = "🏆 <b>Top 10 Global Leaderboard</b>\n\n";
    $i = 1;
    foreach ($topUsers as $user) {
        $name = htmlspecialchars($user['first_name']);
        $username = $user['username'] ? " (@" . htmlspecialchars($user['username']) . ")" : "";
        $score = number_format($user['score']);

        $medal = "";
        if ($i == 1)
            $medal = "🥇 ";
        elseif ($i == 2)
            $medal = "🥈 ";
        elseif ($i == 3)
            $medal = "🥉 ";
        else
            $medal = "$i. ";

        $text .= "{$medal}{$name}{$username}: <b>$score</b> pts\n";
        $i++;
    }

    // 2. Fetch current user rank
    $userRank = $db->fetch("SELECT COUNT(*) + 1 as rank FROM users WHERE score > (SELECT score FROM users WHERE telegram_id = :tid)", [':tid' => $chatId]);
    $userScore = $db->fetch("SELECT score FROM users WHERE telegram_id = :tid", [':tid' => $chatId]);

    $text .= "\n------------------\n";
    $text .= "👤 Your Rank: <b>#{$userRank['rank']}</b>\n";
    $text .= "💰 Your Score: <b>" . number_format($userScore['score']) . "</b> pts";

    $keyboard = [
        'inline_keyboard' => [
            [['text' => "📱 Open Full Leaderboard", 'web_app' => ['url' => "https://yourdomain.com/user-panel/leaderboard.php"]]]
        ]
    ];

    $telegram->sendMessage($chatId, $text, $keyboard);
}
