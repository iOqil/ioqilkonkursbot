from fastapi import APIRouter, Request, Depends, Form
from fastapi.responses import RedirectResponse
from fastapi.templating import Jinja2Templates
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select, desc
from core.database import get_db
from core.models import User, Quiz, Season

from core.security import verify_init_data, is_admin

router = APIRouter()
templates = Jinja2Templates(directory="web/templates")

@router.get("/")
async def index(request: Request, db: AsyncSession = Depends(get_db)):
    result = await db.execute(select(User).order_by(desc(User.score)).limit(10))
    top_users = result.scalars().all()
    return templates.TemplateResponse("index.html", {"request": request, "top_users": top_users})

@router.get("/admin")
async def admin_dashboard(
    request: Request, 
    tg_auth: str = None, 
    db: AsyncSession = Depends(get_db)
):
    # 1. Check if already has a session cookie
    session_admin_id = request.cookies.get("admin_session")
    
    user_id = None
    if session_admin_id and is_admin(int(session_admin_id)):
        user_id = int(session_admin_id)
    elif tg_auth:
        user_data = verify_init_data(tg_auth)
        if user_data and is_admin(user_data.get('id')):
            user_id = user_data.get('id')
    
    if not user_id:
        if not tg_auth:
            return templates.TemplateResponse("loader.html", {"request": request})
        return templates.TemplateResponse("error.html", {
            "request": request, 
            "message": "Kirish taqiqlangan! Siz admin emassiz."
        })

    # Prepare response
    from sqlalchemy import func
    user_count_res = await db.execute(select(func.count(User.id)))
    user_count = user_count_res.scalar() or 0
    
    quiz_res = await db.execute(select(Quiz).order_by(desc(Quiz.id)))
    quizzes = quiz_res.scalars().all()
    
    response = templates.TemplateResponse("admin.html", {
        "request": request, 
        "user_count": user_count, 
        "quizzes": quizzes,
        "tg_auth": tg_auth or ""
    })
    
    # Set session cookie if verified via tg_auth
    if tg_auth and user_id:
        response.set_cookie(key="admin_session", value=str(user_id), httponly=True, max_age=3600*24)
        
    return response

@router.post("/admin/quiz/create")
async def create_quiz(
    request: Request,
    question: str = Form(...),
    option_a: str = Form(...),
    option_b: str = Form(...),
    option_c: str = Form(...),
    option_d: str = Form(...),
    correct_option: str = Form(...),
    points: int = Form(10),
    db: AsyncSession = Depends(get_db)
):
    # Verification via cookie
    session_admin_id = request.cookies.get("admin_session")
    if not session_admin_id or not is_admin(int(session_admin_id)):
        return RedirectResponse(url="/web/admin", status_code=303)

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
    # Redirect back to admin without tokens in URL
    return RedirectResponse(url="/web/admin", status_code=303)
