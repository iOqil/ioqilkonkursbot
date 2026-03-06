from fastapi import APIRouter, Request, Depends
from fastapi.templating import Jinja2Templates
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select, desc
from core.database import get_db
from core.models import User, Quiz, Season

router = APIRouter()
templates = Jinja2Templates(directory="web/templates")

@router.get("/")
async def index(request: Request, db: AsyncSession = Depends(get_db)):
    # Fetch some stats for the dashboard
    result = await db.execute(select(User).order_by(desc(User.score)).limit(10))
    top_users = result.scalars().all()
    return templates.TemplateResponse("index.html", {"request": request, "top_users": top_users})

@router.get("/leaderboard")
async def leaderboard(request: Request, db: AsyncSession = Depends(get_db)):
    result = await db.execute(select(User).order_by(desc(User.score)).limit(100))
    users = result.scalars().all()
    return templates.TemplateResponse("leaderboard.html", {"request": request, "users": users})

@router.get("/admin")
async def admin_dashboard(request: Request, db: AsyncSession = Depends(get_db)):
    # Admin stats
    user_count = await db.scalar(select(User.id).order_by(desc(User.id)).limit(1)) or 0
    quiz_count = await db.scalar(select(Quiz.id).order_by(desc(Quiz.id)).limit(1)) or 0
    return templates.TemplateResponse("admin.html", {
        "request": request, 
        "user_count": user_count, 
        "quiz_count": quiz_count
    })
