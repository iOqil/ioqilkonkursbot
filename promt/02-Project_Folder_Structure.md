2. Project Folder Structure
contest-platform
│
├── bot
│   ├── webhook.php
│   ├── commands
│   │   ├── start.php
│   │   ├── checkin.php
│   │   ├── leaderboard.php
│   │   ├── referral.php
│   │   ├── quiz.php
│   │   └── code.php
│
├── app
│   ├── controllers
│   ├── models
│   ├── services
│   └── helpers
│
├── api
│   ├── user.php
│   ├── leaderboard.php
│   └── tasks.php
│
├── dashboard
│   ├── login.php
│   ├── users.php
│   ├── seasons.php
│   ├── quiz.php
│   ├── codes.php
│   └── analytics.php
│
├── user-panel
│   ├── index.php
│   ├── leaderboard.php
│   └── tasks.php
│
├── config
│   ├── database.php
│   └── telegram.php
│
└── database
    └── schema.sql