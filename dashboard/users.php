<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin Dashboard</title>
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

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border);
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .logo {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 40px;
            color: var(--accent);
        }

        .nav-link {
            padding: 12px 16px;
            border-radius: 10px;
            text-decoration: none;
            color: var(--text-dim);
            font-weight: 600;
            transition: all 0.2s;
        }

        .nav-link.active,
        .nav-link:hover {
            background: rgba(56, 189, 248, 0.1);
            color: var(--accent);
        }

        /* Main Content */
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
            overflow-x: auto;
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

        .btn-action {
            background: rgba(255, 255, 255, 0.05);
            border: none;
            color: var(--text);
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            margin-right: 8px;
        }

        .btn-edit {
            color: var(--accent);
        }

        .btn-delete {
            color: #f87171;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="logo">Ovozyigish Admin</div>
        <a href="users.php" class="nav-link active">Users</a>
        <a href="seasons.php" class="nav-link">Seasons</a>
        <a href="quiz.php" class="nav-link">Quizzes</a>
        <a href="codes.php" class="nav-link">Reward Codes</a>
        <a href="analytics.php" class="nav-link">Analytics</a>
    </div>

    <div class="main">
        <div class="header">
            <h1>Users Management</h1>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Telegram ID</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Score</th>
                        <th>Level</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>12345678</td>
                        <td>Oqilbek</td>
                        <td>@oqilbek</td>
                        <td>12,450</td>
                        <td>5</td>
                        <td>2026-03-01</td>
                        <td>
                            <button class="btn-action btn-edit">Edit</button>
                            <button class="btn-action btn-delete">Ban</button>
                        </td>
                    </tr>
                    <!-- More mock rows -->
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>