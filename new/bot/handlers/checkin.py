from aiogram import Router, types, F
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select, func
from core.models import User, Checkin
from datetime import date, timedelta

router = Router()

@router.message(F.text == "🎁 Daily Check-in")
async def cmd_checkin(message: types.Message, db: AsyncSession):
    user_id = message.from_user.id
    today = date.today()
    
    # Check if already checked in today
    res = await db.execute(select(Checkin).where(Checkin.user_id == user_id, Checkin.checkin_date == today))
    if res.scalar_one_or_none():
        return await message.answer("✅ You are already checked in for today! Come back tomorrow.")

    # Get yesterday's checkin for streak
    yesterday = today - timedelta(days=1)
    res_y = await db.execute(select(Checkin).where(Checkin.user_id == user_id, Checkin.checkin_date == yesterday))
    last_checkin = res_y.scalar_one_or_none()
    
    streak = (last_checkin.streak + 1) if last_checkin else 1
    points = 10 + (min(streak, 7) * 5) # Base 10 + bonus up to day 7
    
    # Update DB
    db.add(Checkin(user_id=user_id, checkin_date=today, streak=streak))
    user_res = await db.execute(select(User).where(User.telegram_id == user_id))
    user = user_res.scalar_one()
    user.score += points
    await db.commit()
    
    await message.answer(f"🎉 Check-in successful!\nPoints earned: <b>{points}</b>\nCurrent streak: <b>{streak}</b> days\nTotal score: <b>{user.score}</b> pts", parse_mode="HTML")
