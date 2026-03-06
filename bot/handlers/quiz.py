import random
from aiogram import Router, types, F
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select, func
from core.models import Quiz, User, QuizAttempt

router = Router()

@router.message(F.text == "🧩 Viktorina")
async def cmd_quiz(message: types.Message, db: AsyncSession):
    user_id = message.from_user.id
    
    # Fetch random quiz that user hasn't attempted yet
    # select q from Quiz q left join QuizAttempt a on q.id = a.quiz_id and a.user_id = :user_id where a.id is null
    res = await db.execute(
        select(Quiz)
        .outerjoin(QuizAttempt, (Quiz.id == QuizAttempt.quiz_id) & (QuizAttempt.user_id == user_id))
        .where(QuizAttempt.id == None)
        .order_by(func.rand())
        .limit(1)
    )
    quiz = res.scalar_one_or_none()
    
    if not quiz:
        return await message.answer("😔 Siz barcha mavjud viktorinalarni yechib bo'ldingiz! Yangi savollar qo'shilishini kuting.")
    
    kb = types.InlineKeyboardMarkup(inline_keyboard=[
        [types.InlineKeyboardButton(text=f"A) {quiz.option_a}", callback_data=f"quiz_{quiz.id}_a")],
        [types.InlineKeyboardButton(text=f"B) {quiz.option_b}", callback_data=f"quiz_{quiz.id}_b")],
        [types.InlineKeyboardButton(text=f"C) {quiz.option_c}", callback_data=f"quiz_{quiz.id}_c")],
        [types.InlineKeyboardButton(text=f"D) {quiz.option_d}", callback_data=f"quiz_{quiz.id}_d")],
    ])
    
    await message.answer(f"<b>Viktorina!</b>\n\n{quiz.question}", reply_markup=kb, parse_mode="HTML")

@router.callback_query(F.data.startswith("quiz_"))
async def quiz_callback(callback: types.CallbackQuery, db: AsyncSession):
    _, quiz_id, answer = callback.data.split("_")
    user_id = callback.from_user.id
    
    # Double check if already attempted (to prevent double clicks)
    check_res = await db.execute(select(QuizAttempt).where(QuizAttempt.user_id == user_id, QuizAttempt.quiz_id == int(quiz_id)))
    if check_res.scalar_one_or_none():
        await callback.message.edit_reply_markup(reply_markup=None)
        return await callback.answer("Siz bu savolga allaqachon javob bergansiz!", show_alert=True)

    res = await db.execute(select(Quiz).where(Quiz.id == int(quiz_id)))
    quiz = res.scalar_one_or_none()
    
    await callback.message.edit_reply_markup(reply_markup=None) # Remove keyboard
    
    if not quiz:
        return await callback.answer("Xato: Viktorina topilmadi.")
    
    is_correct = answer == quiz.correct_option.lower()
    
    # Record attempt
    db.add(QuizAttempt(user_id=user_id, quiz_id=quiz.id, is_correct=is_correct))
    
    if is_correct:
        user_res = await db.execute(select(User).where(User.telegram_id == user_id))
        user = user_res.scalar_one()
        user.score += quiz.points
        await db.commit()
        await callback.message.answer(f"✅ To'g'ri! Siz <b>{quiz.points}</b> ball qozondingiz.\nUmumiy ball: <b>{user.score}</b>", parse_mode="HTML")
    else:
        await db.commit()
        await callback.message.answer(f"❌ Noto'g'ri! To'g'ri javob <b>{quiz.correct_option.upper()}</b> edi.", parse_mode="HTML")
    
    await callback.answer()
