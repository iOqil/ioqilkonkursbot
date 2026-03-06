<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Quizzes - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0088cc;
            --bg: #0f172a;
            --sidebar-bg: #1e293b;
            --card-bg: #1e293b;
            --text: #f1f5f9;
            --text-dim: #94a3b8;
            --accent: #38bdf8;
            --border: rgba(255, 255, 255, 0.05);
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
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border);
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .nav-link {
            padding: 12px 16px;
            border-radius: 10px;
            text-decoration: none;
            color: var(--text-dim);
            font-weight: 600;
        }

        .nav-link.active {
            background: rgba(56, 189, 248, 0.1);
            color: var(--accent);
        }

        .main {
            flex: 1;
            padding: 40px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .btn-add {
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }

        .card {
            background: var(--card-bg);
            border-radius: 16px;
            border: 1px solid var(--border);
            padding: 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            color: var(--text-dim);
            font-size: 13px;
            text-transform: uppercase;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
        }

        td {
            padding: 16px;
            border-bottom: 1px solid var(--border);
        }

        .badge-correct {
            color: #22c55e;
            font-weight: 700;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="logo" style="font-size: 20px; font-weight: 700; margin-bottom: 40px; color: var(--accent);">
            Ovozyigish Admin</div>
        <a href="users.php" class="nav-link">Users</a>
        <a href="seasons.php" class="nav-link">Seasons</a>
        <a href="quiz.php" class="nav-link active">Quizzes</a>
        <a href="codes.php" class="nav-link">Reward Codes</a>
        <a href="analytics.php" class="nav-link">Analytics</a>
    </div>

    <div class="main">
        <div class="header">
            <h1>Quiz Management</h1>
            <button class="btn-add">+ New Question</button>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Options (Correct)</th>
                        <th>Points</th>
                        <th>Season</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>O'zbekistonning poytaxti qaysi?</td>
                        <td>A, B, C, <span class="badge-correct">D (Toshkent)</span></td>
                        <td>10</td>
                        <td>Season One</td>
                        <td>
                            <button
                                style="color:var(--accent); background:none; border:none; cursor:pointer;">Edit</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>