from aiogram import Router, types, F
from aiogram.filters import CommandStart, Command
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select
from core.models import User, Referral
from core.config import settings
import time

router = Router()

async def check_channels(bot, user_id):
    for channel in settings.channel_list:
        try:
            member = await bot.get_chat_member(channel, user_id)
            if member.status in ["left", "kicked"]:
                return False
        except Exception:
            return False
    return True

@router.message(CommandStart())
async def cmd_start(message: types.Message, db: AsyncSession, bot):
    user_id = message.from_user.id
    args = message.text.split()[1:] if len(message.text.split()) > 1 else None
    
    # 1. Check/Register User
    result = await db.execute(select(User).where(User.telegram_id == user_id))
    user = result.scalar_one_or_none()
    
    is_new = False
    if not user:
        is_new = True
        referrer_id = int(args[0]) if args and args[0].isdigit() else None
        user = User(
            telegram_id=user_id,
            username=message.from_user.username,
            first_name=message.from_user.first_name,
            referrer_id=referrer_id
        )
        db.add(user)
        await db.commit()
    
    # 2. Check Channels
    subscribed = await check_channels(bot, user_id)
    
    if not subscribed:
        kb = types.InlineKeyboardMarkup(inline_keyboard=[
            [types.InlineKeyboardButton(text="Join Channel", url=f"https://t.me/{c.replace('@','')}") for c in settings.channel_list],
            [types.InlineKeyboardButton(text="✅ Check Subscription", callback_data="check_sub")]
        ])
        return await message.answer("Please join our channels to participate in the contest:", reply_markup=kb)

    # 3. Handle Referral Points (once)
    if is_new and user.referrer_id and subscribed:
        # Check daily limit (Simplified for now)
        from sqlalchemy import func
        today = types.DateTime.now().date()
        ref_count_res = await db.execute(select(func.count(Referral.id)).where(Referral.referrer_id == user.referrer_id))
        if ref_count_res.scalar() < 10:
            ref_user_res = await db.execute(select(User).where(User.telegram_id == user.referrer_id))
            ref_user = ref_user_res.scalar_one_or_none()
            if ref_user:
                ref_user.score += 50
                db.add(Referral(referrer_id=user.referrer_id, referred_user_id=user_id))
                await db.commit()
                try:
                    await bot.send_message(user.referrer_id, f"🎉 New referral! You earned 50 pts from {message.from_user.first_name}")
                except: pass

    await message.answer(f"Welcome to the Contest Platform, {message.from_user.first_name}! 🚀\nYour current score: {user.score} pts", reply_markup=main_menu_kb())

def main_menu_kb():
    return types.ReplyKeyboardMarkup(keyboard=[
        [types.KeyboardButton(text="🎁 Daily Check-in"), types.KeyboardButton(text="🧩 Solve Quiz")],
        [types.KeyboardButton(text="🏆 Leaderboard"), types.KeyboardButton(text="👥 Referrals")],
        [types.KeyboardButton(text="📱 Open Web App", web_app=types.WebAppInfo(url=settings.TELEGRAM_WEBHOOK_URL.replace('/bot/webhook.php', '/web/index')))]
    ], resize_keyboard=True)
