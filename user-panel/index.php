<?php
// user-panel/index.php
// Note: In production, this would use AuthService to verify the user.
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Contest Platform - User Panel</title>
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
            --gold: #fbbf24;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            line-height: 1.5;
            padding: 20px;
            overflow-x: hidden;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            animation: fadeInDown 0.8s ease-out;
        }

        .profile-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            gap: 20px;
            animation: fadeInUp 0.8s ease-out;
        }

        .avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 700;
            color: white;
            box-shadow: 0 0 20px rgba(0, 136, 204, 0.4);
        }

        .profile-info h2 {
            font-size: 20px;
            margin-bottom: 4px;
        }

        .profile-info p {
            color: var(--text-dim);
            font-size: 14px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: transform 0.3s ease;
        }

        .stat-card:active {
            transform: scale(0.95);
        }

        .stat-card .label {
            color: var(--text-dim);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 8px;
            display: block;
        }

        .stat-card .value {
            font-size: 24px;
            font-weight: 700;
            color: var(--accent);
        }

        .menu-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .menu-item {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            padding: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            color: var(--text);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: background 0.2s ease;
        }

        .menu-item:active {
            background: rgba(255, 255, 255, 0.08);
        }

        .menu-item .icon-box {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .badge {
            background: var(--gold);
            color: var(--bg);
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
            margin-left: 8px;
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Contest Central</h1>
        </div>

        <div class="profile-card">
            <div class="avatar" id="user-avatar">?</div>
            <div class="profile-info">
                <h2 id="user-name">Loading...</h2>
                <p id="user-username">@loading</p>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <span class="label">Total Score</span>
                <div class="value" id="user-score">0</div>
            </div>
            <div class="stat-card">
                <span class="label">Current Level</span>
                <div class="value" id="user-level">1</div>
            </div>
        </div>

        <div class="menu-grid">
            <a href="leaderboard.php" class="menu-item">
                <div class="icon-box">
                    <span>🏆</span>
                    <span>Global Leaderboard</span>
                </div>
                <span>→</span>
            </a>
            <a href="tasks.php" class="menu-item">
                <div class="icon-box">
                    <span>⚡</span>
                    <span>Available Tasks</span>
                    <span class="badge">NEW</span>
                </div>
                <span>→</span>
            </a>
            <a href="referrals.php" class="menu-item">
                <div class="icon-box">
                    <span>👥</span>
                    <span>Referral Program</span>
                </div>
                <span>→</span>
            </a>
            <a href="#" class="menu-item" onclick="Telegram.WebApp.close()">
                <div class="icon-box">
                    <span>🚪</span>
                    <span>Return to Bot</span>
                </div>
                <span>→</span>
            </a>
        </div>
    </div>

    <script>
        const tg = window.Telegram.WebApp;
        tg.expand();
        tg.ready();

        // Use WebApp user data
        if (tg.initDataUnsafe && tg.initDataUnsafe.user) {
            const user = tg.initDataUnsafe.user;
            document.getElementById('user-name').textContent = user.first_name + (user.last_name ? ' ' + user.last_name : '');
            document.getElementById('user-username').textContent = user.username ? '@' + user.username : 'User #' + user.id;
            document.getElementById('user-avatar').textContent = user.first_name.charAt(0).toUpperCase();

            // Notify user of active theme
            document.body.style.backgroundColor = tg.themeParams.bg_color || '#0f172a';
            document.body.style.color = tg.themeParams.text_color || '#f1f5f9';
        }

        // Apply theme colors if available
        if (tg.themeParams.button_color) {
            document.documentElement.style.setProperty('--primary', tg.themeParams.button_color);
        }
    </script>
</body>

</html>