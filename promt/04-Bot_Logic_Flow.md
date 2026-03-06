User botga kirganda ishlaydigan flow.

User presses /start
        │
        ▼
Check user in database
        │
        ├─ Exists → Continue
        │
        └─ New user
             │
             ▼
        Save user
             │
             ▼
Check mandatory channels
             │
             ├─ Not joined
             │     │
             │     ▼
             │  Show join buttons
             │
             └─ Joined
                    │
                    ▼
              Show menu