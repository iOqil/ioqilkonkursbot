import asyncio
import logging
import sys
from aiogram import Bot, Dispatcher
from core.config import settings
from core.database import AsyncSessionLocal
from bot.handlers import start, leaderboard, checkin, quiz, referral
from bot.middlewares.db import DbSessionMiddleware
from fastapi import FastAPI
from fastapi.responses import RedirectResponse
import uvicorn

# 1. Setup Logging
logging.basicConfig(level=logging.INFO, stream=sys.stdout)

# 2. Setup Bot
bot = Bot(token=settings.TELEGRAM_BOT_TOKEN)
dp = Dispatcher()

# Register Middlewares
dp.update.middleware(DbSessionMiddleware(AsyncSessionLocal))

# Register Routers
dp.include_router(start.router)
dp.include_router(leaderboard.router)
dp.include_router(checkin.router)
dp.include_router(quiz.router)
dp.include_router(referral.router)

# 3. Setup FastAPI
from fastapi.staticfiles import StaticFiles
from web.api.routes import router as web_router

app = FastAPI(title="Contest Platform API")
app.mount("/static", StaticFiles(directory="web/static"), name="static")
app.include_router(web_router, prefix="/web")

@app.get("/")
async def root():
    return RedirectResponse(url="/web/")

async def main():
    # Setup Uvicorn for FastAPI on port 80
    config = uvicorn.Config(app, host="0.0.0.0", port=80, loop="asyncio")
    server = uvicorn.Server(config)
    
    logging.info("Starting Bot and Web Server...")
    
    # IMPORTANT: Delete webhook to avoid conflict with polling
    await bot.delete_webhook(drop_pending_updates=True)
    
    # Run both Bot Polling and Web Server
    await asyncio.gather(
        dp.start_polling(bot),
        server.serve()
    )

if __name__ == "__main__":
    asyncio.run(main())
