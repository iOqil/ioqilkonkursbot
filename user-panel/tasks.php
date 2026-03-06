<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Earn Points - Tasks</title>
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0088cc;
            --bg: #0f172a;
            --card-bg: #1e293b;
            --text: #f1f5f9;
            --text-dim: #94a3b8;
            --accent: #38bdf8;
            --success: #22c55e;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            padding: 20px;
        }

        .header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
        }

        .back-btn {
            background: rgba(255, 255, 255, 0.05);
            border: none;
            color: var(--text);
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
        }

        .task-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .task-card {
            background: var(--card-bg);
            padding: 20px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: transform 0.2s ease;
        }

        .task-card:active {
            transform: scale(0.98);
        }

        .task-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .task-title {
            font-weight: 600;
            font-size: 16px;
        }

        .task-points {
            color: var(--accent);
            font-weight: 700;
            font-size: 14px;
        }

        .btn-claim {
            background: var(--primary);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }

        .btn-done {
            background: rgba(34, 197, 94, 0.1);
            color: var(--success);
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="header">
        <button class="back-btn" onclick="history.back()">←</button>
        <h1>Daily Tasks</h1>
    </div>

    <div class="task-list">
        <div class="task-card">
            <div class="task-info">
                <span class="task-title">Daily Check-in</span>
                <span class="task-points">+10 - 50 pts</span>
            </div>
            <span class="btn-done">Completed</span>
        </div>

        <div class="task-card">
            <div class="task-info">
                <span class="task-title">Solve Daily Quiz</span>
                <span class="task-points">+20 pts</span>
            </div>
            <button class="btn-claim" onclick="Telegram.WebApp.close()">Play in Bot</button>
        </div>

        <div class="task-card">
            <div class="task-info">
                <span class="task-title">Refer a Friend</span>
                <span class="task-points">+50 pts</span>
            </div>
            <button class="btn-claim">Invite</button>
        </div>

        <div class="task-card">
            <div class="task-info">
                <span class="task-title">Join Announcement Channel</span>
                <span class="task-points">+100 pts</span>
            </div>
            <button class="btn-claim">Join</button>
        </div>
    </div>

    <script>
        const tg = window.Telegram.WebApp;
        tg.ready();
    </script>
</body>

</html>