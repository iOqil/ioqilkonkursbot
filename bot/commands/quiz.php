<?php
// bot/commands/quiz.php

function handleQuiz($message, $telegram, $db)
{
    $chatId = $message['chat']['id'];

    // 1. Fetch a quiz the user hasn't answered yet
    $quiz = $db->fetch("
        SELECT q.* FROM quizzes q
        LEFT JOIN quiz_answers qa ON q.id = qa.quiz_id AND qa.user_id = :uid
        WHERE qa.id IS NULL
        ORDER BY RAND()
        LIMIT 1
    ", [':uid' => $chatId]);

    if (!$quiz) {
        $telegram->sendMessage($chatId, "🎉 You've answered all available quizzes! Great job!");
        return;
    }

    $text = "🧠 <b>Quiz Time!</b>\n\n";
    $text .= htmlspecialchars($quiz['question']);

    $keyboard = [
        'inline_keyboard' => [
            [['text' => "A: " . htmlspecialchars($quiz['option_a']), 'callback_data' => "quiz_{$quiz['id']}_A"]],
            [['text' => "B: " . htmlspecialchars($quiz['option_b']), 'callback_data' => "quiz_{$quiz['id']}_B"]],
            [['text' => "C: " . htmlspecialchars($quiz['option_c']), 'callback_data' => "quiz_{$quiz['id']}_C"]],
            [['text' => "D: " . htmlspecialchars($quiz['option_d']), 'callback_data' => "quiz_{$quiz['id']}_D"]]
        ]
    ];

    $telegram->sendMessage($chatId, $text, $keyboard);
}

function handleQuizCallback($callbackQuery, $telegram, $db)
{
    $chatId = $callbackQuery['message']['chat']['id'];
    $data = $callbackQuery['data'];
    $callbackQueryId = $callbackQuery['id'];

    // data format: quiz_{id}_{option}
    if (preg_match('/quiz_(\d+)_([A-D])/', $data, $matches)) {
        $quizId = $matches[1];
        $selectedOption = $matches[2];

        // 1. Check if already answered
        $alreadyAnswered = $db->fetch("SELECT * FROM quiz_answers WHERE user_id = :uid AND quiz_id = :qid", [
            ':uid' => $chatId,
            ':qid' => $quizId
        ]);

        if ($alreadyAnswered) {
            $telegram->answerCallbackQuery($callbackQueryId, "You already answered this quiz!");
            return;
        }

        // 2. Fetch quiz details
        $quiz = $db->fetch("SELECT * FROM quizzes WHERE id = :qid", [':qid' => $quizId]);
        if (!$quiz)
            return;

        $isCorrect = ($selectedOption === $quiz['correct_option']);
        $points = $isCorrect ? $quiz['points'] : 0;

        // 3. Save answer
        $db->query("INSERT INTO quiz_answers (user_id, quiz_id, answer, is_correct) VALUES (:uid, :qid, :ans, :isc)", [
            ':uid' => $chatId,
            ':qid' => $quizId,
            ':ans' => $selectedOption,
            ':isc' => $isCorrect
        ]);

        if ($isCorrect) {
            $db->query("UPDATE users SET score = score + :p WHERE telegram_id = :uid", [
                ':p' => $points,
                ':uid' => $chatId
            ]);
            $responseText = "✅ Correct! +{$points} points.";
        } else {
            $responseText = "❌ Wrong! The correct answer was {$quiz['correct_option']}.";
        }

        $telegram->answerCallbackQuery($callbackQueryId, $responseText);
        $telegram->sendMessage($chatId, $responseText);

        // Optionally edit the original message to remove buttons or show result
        // ... implementation for editMessageText could be added to Telegram helper
    }
}
