<?php
/**
 * Telegram Webhook Callback Handler
 * 
 * Receives incoming updates from Telegram when users interact with the bot.
 * Validates the secret token for security.
 */

// Load Laravel bootstrap
require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Verify secret token (if configured)
$secretToken = env('TELEGRAM_WEBHOOK_SECRET');
if ($secretToken) {
    $receivedToken = $_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN'] ?? '';
    if (!hash_equals($secretToken, $receivedToken)) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
}

// Read the incoming update
$input = file_get_contents('php://input');
$update = json_decode($input, true);

if (!$update) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid payload']);
    exit;
}

// Log incoming update for debugging
\Illuminate\Support\Facades\Log::info('Telegram webhook received', ['update' => $update]);

// Handle different types of updates
try {
    // Handle callback queries (button clicks from inline keyboards)
    if (isset($update['callback_query'])) {
        handleCallbackQuery($update['callback_query']);
    }

    // Handle messages
    if (isset($update['message'])) {
        handleMessage($update['message']);
    }

    // Handle chat join requests (bot added to group)
    if (isset($update['my_chat_member'])) {
        handleChatMemberUpdate($update['my_chat_member']);
    }

} catch (\Throwable $e) {
    \Illuminate\Support\Facades\Log::error('Telegram webhook error', [
        'error' => $e->getMessage(),
        'update' => $update
    ]);
}

// Always return 200 OK to acknowledge receipt
http_response_code(200);
echo json_encode(['ok' => true]);

// ============================================================
// Helper Functions
// ============================================================

function handleCallbackQuery(array $callbackQuery): void
{
    $botToken = config('telegram.bot_token') ?: env('TELEGRAM_BOT_TOKEN');
    $data = $callbackQuery['data'] ?? '';
    $callbackId = $callbackQuery['id'] ?? '';

    // Handle approve/reject from registration payment
    $parts = explode('_', $data, 2);
    if (count($parts) === 2) {
        $action = $parts[0];
        $token = $parts[1];

        if (in_array($action, ['approve', 'reject'])) {
            $paymentRequest = \App\Models\PaymentRequest::where('access_token', $token)->first();
            if ($paymentRequest && $paymentRequest->status === 'pending') {
                $newStatus = ($action === 'approve') ? 'approved' : 'rejected';
                $paymentRequest->update(['status' => $newStatus]);

                // Answer the callback
                \Illuminate\Support\Facades\Http::withoutVerifying()
                    ->post("https://api.telegram.org/bot{$botToken}/answerCallbackQuery", [
                        'callback_query_id' => $callbackId,
                        'text' => "Request " . ucfirst($newStatus) . "! ✅"
                    ]);
            }
        }
    }
}

function handleMessage(array $message): void
{
    $botToken = config('telegram.bot_token') ?: env('TELEGRAM_BOT_TOKEN');
    $chatId = $message['chat']['id'] ?? null;
    $text = $message['text'] ?? '';
    $chatType = $message['chat']['type'] ?? 'private';

    // Respond to /start command
    if ($text === '/start' && $chatId) {
        $companyName = config('app.name', 'HRM');
        $response = "👋 Welcome to {$companyName} Attendance Bot!\n\n"
                  . "This bot sends real-time attendance notifications.\n"
                  . "Chat ID: `{$chatId}`";

        \Illuminate\Support\Facades\Http::withoutVerifying()
            ->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $response,
                'parse_mode' => 'Markdown'
            ]);
    }

    // Log new group chat IDs for admin reference
    if (in_array($chatType, ['group', 'supergroup'])) {
        \Illuminate\Support\Facades\Log::info('Telegram bot in group', [
            'chat_id' => $chatId,
            'chat_title' => $message['chat']['title'] ?? 'Unknown'
        ]);
    }
}

function handleChatMemberUpdate(array $chatMember): void
{
    $newStatus = $chatMember['new_chat_member']['status'] ?? '';
    $chatId = $chatMember['chat']['id'] ?? null;
    $chatTitle = $chatMember['chat']['title'] ?? 'Unknown';

    if ($newStatus === 'member' || $newStatus === 'administrator') {
        \Illuminate\Support\Facades\Log::info('Telegram bot added to chat', [
            'chat_id' => $chatId,
            'chat_title' => $chatTitle,
            'status' => $newStatus
        ]);
    }
}
