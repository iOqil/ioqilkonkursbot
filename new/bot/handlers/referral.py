from aiogram import Router, types, F
from core.config import settings

router = Router()

@router.message(F.text == "👥 Referrals")
async def cmd_referrals(message: types.Message):
    bot_info = await message.bot.get_me()
    ref_link = f"https://t.me/{bot_info.username}?start={message.from_user.id}"
    
    text = (
        "👥 <b>Referral Program</b>\n\n"
        "Invite your friends and earn <b>50 pts</b> for each referral!\n"
        "<i>Note: Referral limit is 10 friends per day.</i>\n\n"
        f"Your referral link:\n<code>{ref_link}</code>"
    )
    
    await message.answer(text, parse_mode="HTML")
