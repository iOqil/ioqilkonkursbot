<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Global Leaderboard</title>
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
            --gold: #fbbf24;
            --silver: #94a3b8;
            --bronze: #b45309;
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

        .leaderboard-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .leaderboard-item {
            background: var(--card-bg);
            padding: 16px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .rank-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .rank {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
        }

        .rank-1 {
            background: var(--gold);
            color: var(--bg);
        }

        .rank-2 {
            background: var(--silver);
            color: var(--bg);
        }

        .rank-3 {
            background: var(--bronze);
            color: var(--bg);
        }

        .name {
            font-weight: 600;
        }

        .score {
            font-weight: 700;
            color: var(--accent);
        }

        .user-rank {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            background: var(--primary);
            padding: 16px;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 -10px 25px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>

<body>
    <div class="header">
        <button class="back-btn" onclick="history.back()">←</button>
        <h1>Leaderboard</h1>
    </div>

    <div class="leaderboard-list">
        <!-- Mock Data for Display -->
        <div class="leaderboard-item">
            <div class="rank-info">
                <div class="rank rank-1">1</div>
                <div class="name">Oqilbek</div>
            </div>
            <div class="score">12,450</div>
        </div>
        <div class="leaderboard-item">
            <div class="rank-info">
                <div class="rank rank-2">2</div>
                <div class="name">Doston</div>
            </div>
            <div class="score">10,200</div>
        </div>
        <div class="leaderboard-item">
            <div class="rank-info">
                <div class="rank rank-3">3</div>
                <div class="name">Jasur</div>
            </div>
            <div class="score">9,800</div>
        </div>
        <div class="leaderboard-item">
            <div class="rank-info">
                <div class="rank">4</div>
                <div class="name">Sardor</div>
            </div>
            <div class="score">8,150</div>
        </div>
    </div>

    <div class="user-rank">
        <span>Your Current Rank: <b>#12</b></span>
        <span><b>2,450 pts</b></span>
    </div>

    <script>
        const tg = window.Telegram.WebApp;
        tg.ready();
    </script>
</body>

</html>