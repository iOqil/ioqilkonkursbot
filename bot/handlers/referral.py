from aiogram import Router, types, F
from core.config import settings

router = Router()

@router.message(F.text == "👥 Taklif qilish")
async def cmd_referrals(message: types.Message):
    bot_info = await message.bot.get_me()
    ref_link = f"https://t.me/{bot_info.username}?start={message.from_user.id}"
    
    text = (
        "👥 <b>Taklif qilish</b>\n\n"
        "Do'stlaringizni taklif qiling va har bir do'st uchun <b>50 ball</b> qozoning!\n"
        "<i>Eslatma: Do'stlarni taklif qilish limiti kuniga 10 ta.</i>\n\n"
        f"Sizning taklif havolangiz:\n<code>{ref_link}</code>"
    )

    share_text = "🚀 Konkursda ishtirok eting va sovg'alar yuting!"
    share_url = f"https://t.me/share/url?url={ref_link}&text={share_text}"
    
    kb = types.InlineKeyboardMarkup(inline_keyboard=[
        [types.InlineKeyboardButton(text="🚀 Do'stlarga yuborish", url=share_url)]
    ])
    
    await message.answer(text, reply_markup=kb, parse_mode="HTML")
