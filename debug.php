// debug.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/app/helpers/Env.php';
\App\Helpers\Env::load(__DIR__ . '/.env');

use \App\Helpers\Env;

header('Content-Type: text/plain');

echo "--- DEPLOYMENT DIAGNOSTICS ---\n\n";

// 1. Check .env file
echo "[1] Checking .env file...\n";
if (file_exists(__DIR__ . '/.env')) {
    echo "    OK: .env exists.\n";
    $token = Env::get('TELEGRAM_BOT_TOKEN');
    if ($token === 'YOUR_BOT_TOKEN_HERE' || empty($token)) {
        echo "    WARNING: TELEGRAM_BOT_TOKEN is not set or still has the placeholder value!\n";
    } else {
        echo "    OK: Bot token is set.\n";
    }
} else {
    echo "    ERROR: .env not found! Please create it from .env.example.\n";
}

// 2. Check Database Connection
echo "\n[2] Checking Database Connectivity...\n";
require_once __DIR__ . '/app/helpers/Database.php';
use App\Helpers\Database;

try {
    $db = Database::getInstance();
    $pdo = $db->getPdo();
    echo "    OK: Database connected successfully.\n";

    // Check if tables exist
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "    INFO: Tables found: " . implode(', ', $tables) . "\n";
    if (empty($tables)) {
        echo "    WARNING: No tables found! Did you run schema.sql?\n";
    }
} catch (Exception $e) {
    echo "    ERROR: Database connection failed: " . $e->getMessage() . "\n";
}

// 3. Check Telegram API
echo "\n[3] Checking Telegram API Connectivity...\n";
require_once __DIR__ . '/app/helpers/Telegram.php';
use App\Helpers\Telegram;

$telegram = new Telegram();
$response = $telegram->sendRequest('getMe');

if ($response && isset($response['ok']) && $response['ok']) {
    echo "    OK: Successfully connected to Telegram API.\n";
    echo "    INFO: Bot ID: " . $response['result']['id'] . "\n";
    echo "    INFO: Bot Username: @" . $response['result']['username'] . "\n";
} else {
    echo "    ERROR: Failed to connect to Telegram API or invalid token.\n";
    echo "    DEBUG: " . json_encode($response) . "\n";
}

// 4. Webhook Info
echo "\n[4] Webhook Information...\n";
$webhookUrl = Env::get('TELEGRAM_WEBHOOK_URL');
echo "    INFO: Configured Webhook URL: $webhookUrl\n";

$webhookInfo = $telegram->sendRequest('getWebhookInfo');
if ($webhookInfo && isset($webhookInfo['ok']) && $webhookInfo['ok']) {
    $currentUrl = $webhookInfo['result']['url'] ?? 'NOT SET';
    echo "    INFO: Current Webhook URL on Telegram: $currentUrl\n";

    if ($currentUrl !== $webhookUrl) {
        echo "    WARNING: Webhook URL mismatch! Would you like to set it now? (Add ?set_webhook=1 to the URL)\n";
    }
} else {
    echo "    ERROR: Could not get webhook info.\n";
}

// Action: Set Webhook
if (isset($_GET['set_webhook'])) {
    echo "\n--- SETTING WEBHOOK ---\n";
    $setResponse = $telegram->sendRequest('setWebhook', ['url' => $webhookUrl]);
    echo "    RESULT: " . json_encode($setResponse) . "\n";
}

echo "\n--- END OF DIAGNOSTICS ---\n";
