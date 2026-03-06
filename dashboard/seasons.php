<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Seasons - Admin Dashboard</title>
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
            --success: #22c55e;
            --warning: #fbbf24;
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

        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-active {
            background: rgba(34, 197, 94, 0.1);
            color: var(--success);
        }

        .status-upcoming {
            background: rgba(251, 191, 36, 0.1);
            color: var(--warning);
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="logo" style="font-size: 20px; font-weight: 700; margin-bottom: 40px; color: var(--accent);">
            Ovozyigish Admin</div>
        <a href="users.php" class="nav-link">Users</a>
        <a href="seasons.php" class="nav-link active">Seasons</a>
        <a href="quiz.php" class="nav-link">Quizzes</a>
        <a href="codes.php" class="nav-link">Reward Codes</a>
        <a href="analytics.php" class="nav-link">Analytics</a>
    </div>

    <div class="main">
        <div class="header">
            <h1>Season Management</h1>
            <button
                style="background:var(--primary); color:white; border:none; padding:10px 20px; border-radius:8px; font-weight:600; cursor:pointer;">+
                Create Season</button>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Season Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Prizes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Spring Contest 2026</td>
                        <td>2026-03-01</td>
                        <td>2026-03-31</td>
                        <td><span class="status-badge status-active">Active</span></td>
                        <td>Teddy, Powerbank...</td>
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