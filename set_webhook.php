<?php
/**
 * Set Telegram Webhook for Attendance Bot
 * 
 * Usage: php set_webhook.php [set|delete|info]
 */

require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$botToken = config('telegram.bot_token') ?: env('TELEGRAM_BOT_TOKEN');
$webhookUrl = rtrim(env('APP_URL', 'https://besthrm.app'), '/') . '/api/telegram_callback.php';
$secretToken = env('TELEGRAM_WEBHOOK_SECRET', '');

$command = $argv[1] ?? 'info';

echo "========================================\n";
echo "  Telegram Webhook Setup\n";
echo "========================================\n";
echo "Bot Token   : " . substr($botToken, 0, 12) . "...\n";
echo "Webhook URL : {$webhookUrl}\n";
echo "Secret      : " . ($secretToken ? substr($secretToken, 0, 10) . '...' : 'NONE') . "\n";
echo "----------------------------------------\n\n";

$apiBase = "https://api.telegram.org/bot{$botToken}";

switch ($command) {
    case 'set':
        echo "Setting webhook...\n";
        
        $params = ['url' => $webhookUrl];
        if ($secretToken) {
            $params['secret_token'] = $secretToken;
        }
        
        $response = \Illuminate\Support\Facades\Http::withoutVerifying()
            ->post("{$apiBase}/setWebhook", $params);
        
        $result = $response->json();
        
        if ($result['ok'] ?? false) {
            echo "✅ Webhook set successfully!\n";
            echo "   URL: {$webhookUrl}\n";
            if ($secretToken) {
                echo "   Secret token: configured\n";
            }
        } else {
            echo "❌ Failed to set webhook!\n";
            echo "   Error: " . ($result['description'] ?? 'Unknown') . "\n";
        }
        print_r($result);
        break;

    case 'delete':
        echo "Deleting webhook...\n";
        $response = \Illuminate\Support\Facades\Http::withoutVerifying()
            ->post("{$apiBase}/deleteWebhook", ['drop_pending_updates' => true]);
        
        $result = $response->json();
        if ($result['ok'] ?? false) {
            echo "✅ Webhook deleted successfully!\n";
        } else {
            echo "❌ Failed to delete webhook!\n";
        }
        print_r($result);
        break;

    case 'info':
    default:
        echo "Getting webhook info...\n";
        $response = \Illuminate\Support\Facades\Http::withoutVerifying()
            ->get("{$apiBase}/getWebhookInfo");
        
        $result = $response->json();
        if ($result['ok'] ?? false) {
            $info = $result['result'];
            echo "Status      : " . ($info['url'] ? '✅ Active' : '❌ Not set') . "\n";
            echo "URL         : " . ($info['url'] ?? 'N/A') . "\n";
            echo "Pending     : " . ($info['pending_update_count'] ?? 0) . "\n";
            echo "Last Error  : " . ($info['last_error_message'] ?? 'None') . "\n";
            echo "Max Connect : " . ($info['max_connections'] ?? 'N/A') . "\n";
        } else {
            echo "❌ Failed to get webhook info!\n";
        }
        echo "\n--- Raw Response ---\n";
        print_r($result);
        break;
}

echo "\n========================================\n";
