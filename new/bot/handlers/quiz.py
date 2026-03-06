import random
from aiogram import Router, types, F
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select, func
from core.models import Quiz, User

router = Router()

@router.message(F.text == "🧩 Solve Quiz")
async def cmd_quiz(message: types.Message, db: AsyncSession):
    # Fetch random quiz
    res = await db.execute(select(Quiz).order_by(func.rand()).limit(1))
    quiz = res.scalar_one_or_none()
    
    if not quiz:
        return await message.answer("😔 No quizzes available right now.")
    
    kb = types.InlineKeyboardMarkup(inline_keyboard=[
        [types.InlineKeyboardButton(text=f"A) {quiz.option_a}", callback_data=f"quiz_{quiz.id}_a")],
        [types.InlineKeyboardButton(text=f"B) {quiz.option_b}", callback_data=f"quiz_{quiz.id}_b")],
        [types.InlineKeyboardButton(text=f"C) {quiz.option_c}", callback_data=f"quiz_{quiz.id}_c")],
        [types.InlineKeyboardButton(text=f"D) {quiz.option_d}", callback_data=f"quiz_{quiz.id}_d")],
    ])
    
    await message.answer(f"<b>Quiz Time!</b>\n\n{quiz.question}", reply_markup=kb, parse_mode="HTML")

@router.callback_query(F.data.startswith("quiz_"))
async def quiz_callback(callback: types.CallbackQuery, db: AsyncSession):
    _, quiz_id, answer = callback.data.split("_")
    
    res = await db.execute(select(Quiz).where(Quiz.id == int(quiz_id)))
    quiz = res.scalar_one_or_none()
    
    await callback.message.edit_reply_markup(reply_markup=None) # Remove keyboard
    
    if not quiz:
        return await callback.answer("Error: Quiz not found.")
    
    if answer == quiz.correct_option.lower():
        user_res = await db.execute(select(User).where(User.telegram_id == callback.from_user.id))
        user = user_res.scalar_one()
        user.score += quiz.points
        await db.commit()
        await callback.message.answer(f"✅ Correct! You earned <b>{quiz.points}</b> pts.\nTotal score: <b>{user.score}</b>", parse_mode="HTML")
    else:
        await callback.message.answer(f"❌ Wrong! The correct answer was <b>{quiz.correct_option.upper()}</b>.", parse_mode="HTML")
    
    await callback.answer()
