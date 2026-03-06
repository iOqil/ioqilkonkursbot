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
            [types.InlineKeyboardButton(text="Kanalga a'zo bo'lish", url=f"https://t.me/{c.replace('@','')}") for c in settings.channel_list],
            [types.InlineKeyboardButton(text="✅ Obunani tekshirish", callback_data="check_sub")]
        ])
        return await message.answer("Tanlovda ishtirok etish uchun kanallarimizga a'zo bo'ling:", reply_markup=kb)

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
                    await bot.send_message(user.referrer_id, f"🎉 Yangi taklif! Sizga {message.from_user.first_name} orqali 50 ball berildi.")
                except: pass

    # 3. Add Admin Panel button if user is admin
    kb = main_menu_kb(user_id)
    # 3. Handle Admin Panel
    if user_id in settings.admin_list:
        admin_kb = types.InlineKeyboardMarkup(inline_keyboard=[
            [types.InlineKeyboardButton(text="⚙️ Admin Panel (Boshqaruv)", web_app=types.WebAppInfo(url=f"{settings.TELEGRAM_WEBHOOK_URL.rstrip('/')}/web/admin"))]
        ])
        await message.answer(f"Siz adminsiz! Boshqaruv paneliga kirishingiz mumkin:", reply_markup=admin_kb)

    await message.answer(f"Konkurs platformasiga xush kelibsiz, {message.from_user.first_name}! 🚀\nSizning balingiz: {user.score} ball", reply_markup=main_menu_kb())

def main_menu_kb():
    buttons = [
        [types.KeyboardButton(text="🎁 Kundalik ro'yxatdan o'tish"), types.KeyboardButton(text="🧩 Viktorina")],
        [types.KeyboardButton(text="🏆 Yetakchilar jadvali"), types.KeyboardButton(text="👥 Taklif qilish")],
        [types.KeyboardButton(text="📱 Web ilovani ochish", web_app=types.WebAppInfo(url=f"{settings.TELEGRAM_WEBHOOK_URL.rstrip('/')}/web/"))]
    ]
    return types.ReplyKeyboardMarkup(keyboard=buttons, resize_keyboard=True)
