User opens referral link
        │
        ▼
Bot receives /start ref_123
        │
        ▼
Check if user exists
        │
        ├─ yes → ignore referral
        │
        └─ no
            │
            ▼
Save referrer_id
            │
            ▼
User joins channels
            │
            ▼
Add referral points