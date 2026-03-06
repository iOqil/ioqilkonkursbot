import os
from pydantic_settings import BaseSettings, SettingsConfigDict

class Settings(BaseSettings):
    # Database
    DB_HOST: str = "localhost"
    DB_PORT: int = 33061
    DB_NAME: str = "konkurs_bot"
    DB_USER: str = "konkurs_bot"
    DB_PASS: str = ""
    DB_CHARSET: str = "utf8mb4"

    # Telegram
    TELEGRAM_BOT_TOKEN: str = ""
    TELEGRAM_ADMIN_IDS: str = ""  # Comma separated
    TELEGRAM_CHANNEL_IDS: str = "" # Comma separated
    TELEGRAM_WEBHOOK_URL: str = ""

    @property
    def admin_list(self) -> list[int]:
        return [int(x.strip()) for x in self.TELEGRAM_ADMIN_IDS.split(",") if x.strip()]

    @property
    def channel_list(self) -> list[str]:
        return [x.strip() for x in self.TELEGRAM_CHANNEL_IDS.split(",") if x.strip()]

    @property
    def database_url(self) -> str:
        return f"mysql+aiomysql://{self.DB_USER}:{self.DB_PASS}@{self.DB_HOST}:{self.DB_PORT}/{self.DB_NAME}?charset={self.DB_CHARSET}"

    model_config = SettingsConfigDict(env_file=".env", env_file_encoding="utf-8", extra="ignore")

settings = Settings()
