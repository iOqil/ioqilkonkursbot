3. Database Schema
users
id
telegram_id
username
first_name
score
level
referrer_id
created_at

Index:

INDEX telegram_id
INDEX score
referrals
id
referrer_id
referred_user_id
created_at
seasons
id
name
start_date
end_date
status

status:

active
finished
upcoming
checkins
id
user_id
date
streak
created_at
quizzes
id
question
option_a
option_b
option_c
option_d
correct_option
points
season_id
created_at
quiz_answers
id
user_id
quiz_id
answer
is_correct
created_at

Index:

INDEX user_id
INDEX quiz_id
riddles
id
question
answer
points
season_id
created_at
riddle_answers
id
user_id
riddle_id
answer
is_correct
created_at
codes
id
code
points
max_uses
expires_at
created_at
code_claims
id
user_id
code_id
created_at
prizes
id
season_id
rank_position
title
description

Example:

Rank 1 → Teddy Bear
Rank 2 → Powerbank
Rank 3 → Flash Drive
tasks

Universal task system.

id
type
title
points
season_id
created_at

types:

quiz
checkin
reaction
referral
code
riddle