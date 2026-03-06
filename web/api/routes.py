from fastapi import APIRouter, Request, Depends, Form
from fastapi.responses import RedirectResponse
from fastapi.templating import Jinja2Templates
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select, desc
from core.database import get_db
from core.models import User, Quiz, Season

router = APIRouter()
templates = Jinja2Templates(directory="web/templates")

@router.get("/")
async def index(request: Request, db: AsyncSession = Depends(get_db)):
    result = await db.execute(select(User).order_by(desc(User.score)).limit(10))
    top_users = result.scalars().all()
    return templates.TemplateResponse("index.html", {"request": request, "top_users": top_users})

@router.get("/admin")
async def admin_dashboard(request: Request, db: AsyncSession = Depends(get_db)):
    user_count_res = await db.execute(select(User).order_by(desc(User.id)).limit(1))
    user_count = user_count_res.scalar() or 0
    
    quiz_res = await db.execute(select(Quiz).order_by(desc(Quiz.id)))
    quizzes = quiz_res.scalars().all()
    
    return templates.TemplateResponse("admin.html", {
        "request": request, 
        "user_count": user_count, 
        "quizzes": quizzes
    })

@router.post("/admin/quiz/create")
async def create_quiz(
    question: str = Form(...),
    option_a: str = Form(...),
    option_b: str = Form(...),
    option_c: str = Form(...),
    option_d: str = Form(...),
    correct_option: str = Form(...),
    points: int = Form(10),
    db: AsyncSession = Depends(get_db)
):
    new_quiz = Quiz(
        question=question,
        option_a=option_a,
        option_b=option_b,
        option_c=option_c,
        option_d=option_d,
        correct_option=correct_option,
        points=points
    )
    db.add(new_quiz)
    await db.commit()
    return RedirectResponse(url="/web/admin", status_code=303)
