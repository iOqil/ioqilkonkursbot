from aiogram import Router, types
from aiogram.filters import Command
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select, func, desc
from core.models import User

router = Router()

@router.message(Command("leaderboard"))
@router.message(lambda m: m.text == "🏆 Leaderboard")
async def cmd_leaderboard(message: types.Message, db: AsyncSession):
    # 1. Top 10
    result = await db.execute(select(User).order_by(desc(User.score)).limit(10))
    top_users = result.scalars().all()
    
    text = "🏆 <b>Top 10 Global Leaderboard</b>\n\n"
    for i, user in enumerate(top_users, 1):
        medal = "🥇" if i == 1 else "🥈" if i == 2 else "🥉" if i == 3 else f"{i}."
        username = f" (@{user.username})" if user.username else ""
        text += f"{medal} {user.first_name}{username}: <b>{user.score:,}</b> pts\n"
    
    # 2. Current User Rank
    user_score_res = await db.execute(select(User.score).where(User.telegram_id == message.from_user.id))
    user_score = user_score_res.scalar()
    
    rank_res = await db.execute(select(func.count(User.id)).where(User.score > user_score))
    rank = rank_res.scalar() + 1
    
    text += f"\n------------------\n👤 Your Rank: <b>#{rank}</b>\n💰 Your Score: <b>{user_score:,}</b> pts"
    
    await message.answer(text, parse_mode="HTML")
