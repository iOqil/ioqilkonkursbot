import hmac
import hashlib
import parse
from operator import itemgetter
from urllib.parse import parse_qsl
from .config import settings

def verify_init_data(init_data: str) -> dict | bool:
    """
    Verifies the data received from the Telegram Web App.
    Returns the user dict if valid, False otherwise.
    """
    if not init_data:
        return False

    try:
        vals = dict(parse_qsl(init_data))
        hash_str = vals.pop('hash')
        data_check_string = "\n".join([f"{k}={v}" for k, v in sorted(vals.items(), key=itemgetter(0))])

        secret_key = hmac.new(b"WebAppData", settings.TELEGRAM_BOT_TOKEN.encode(), hashlib.sha256).digest()
        h = hmac.new(secret_key, data_check_string.encode(), hashlib.sha256).hexdigest()

        if h == hash_str:
            # Data is valid, parse user field
            import json
            user_data = json.loads(vals.get('user', '{}'))
            return user_data
        return False
    except Exception:
        return False

def is_admin(user_id: int) -> bool:
    return user_id in settings.admin_list
