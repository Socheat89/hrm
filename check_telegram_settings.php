<?php
/**
 * Check & Fix Telegram settings in company_settings table
 * 
 * Usage: php check_telegram_settings.php [fix]
 */

require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$setting = \App\Models\CompanySetting::query()->first();

if (!$setting) {
    echo "❌ No CompanySetting record found!\n";
    exit(1);
}

echo "========================================\n";
echo "  Telegram Settings - Database Check\n";
echo "========================================\n";
echo "Company ID   : {$setting->id}\n";
echo "Company Name : {$setting->company_name}\n";
echo "Scan Enabled : " . ($setting->telegram_scan_enabled ? '✅ YES' : '❌ NO') . "\n";
echo "Bot Token    : " . ($setting->telegram_bot_token ? substr($setting->telegram_bot_token, 0, 15) . '...' : '❌ EMPTY') . "\n";
echo "Chat ID      : " . ($setting->telegram_chat_id ?: '❌ EMPTY') . "\n";
echo "----------------------------------------\n";

$envToken = env('TELEGRAM_BOT_TOKEN');
$envChatId = env('TELEGRAM_CHAT_ID');
echo "\n.env Fallback:\n";
echo "TELEGRAM_BOT_TOKEN : " . ($envToken ? substr($envToken, 0, 15) . '...' : '❌ NOT SET') . "\n";
echo "TELEGRAM_CHAT_ID   : " . ($envChatId ?: '❌ NOT SET') . "\n";

echo "\nconfig/services.php:\n";
echo "bot_token : " . (config('services.telegram.bot_token') ? substr(config('services.telegram.bot_token'), 0, 15) . '...' : '❌ NOT SET') . "\n";
echo "chat_id   : " . (config('services.telegram.chat_id') ?: '❌ NOT SET') . "\n";

echo "\nconfig/telegram.php:\n";
echo "bot_token : " . (config('telegram.bot_token') ? substr(config('telegram.bot_token'), 0, 15) . '...' : '❌ NOT SET') . "\n";
echo "chat_id   : " . (config('telegram.chat_id') ?: '❌ NOT SET') . "\n";

echo "\n----------------------------------------\n";

// Check if the stored token is the old compromised one
$oldTokens = [
    '7704406393:AAF27v7soy5S-hlnWrRTiURCT8Bk_lhALjE',
    '8544896901:AAFM6SoCr_g5SoIOJIR7l6ukHQsXbTelqA8',
];

$currentToken = $setting->telegram_bot_token;
if (in_array($currentToken, $oldTokens)) {
    echo "⚠️  WARNING: Database has OLD/COMPROMISED token!\n";
} elseif ($currentToken) {
    echo "✅ Database token is new (not an old known token).\n";
}

// Check if token matches expected production token
$expectedToken = '8645264006:AAFN-kuDf_yfyC8DjUUwiYufxluRRc-Ftjs';
$expectedChatId = '-1003516849055'; // Chat ID from your screenshot

if ($currentToken === $expectedToken) {
    echo "✅ Database token matches expected new token.\n";
} else {
    echo "⚠️  Database token DIFFERS from expected new token.\n";
}

echo "\n========================================\n";

// Fix mode
$command = $argv[1] ?? '';
if ($command === 'fix') {
    echo "\n🔧 FIXING Telegram settings...\n";
    
    $setting->telegram_scan_enabled = true;
    $setting->telegram_bot_token = env('TELEGRAM_BOT_TOKEN', config('telegram.bot_token'));
    $setting->telegram_chat_id = env('TELEGRAM_CHAT_ID', config('telegram.chat_id'));
    $setting->save();
    
    echo "✅ Updated:\n";
    echo "   telegram_scan_enabled = true\n";
    echo "   telegram_bot_token    = " . substr($setting->telegram_bot_token, 0, 15) . "...\n";
    echo "   telegram_chat_id      = {$setting->telegram_chat_id}\n";
    
    // Clear cache
    \Illuminate\Support\Facades\Cache::forget('ui_company_setting');
    echo "✅ Cache cleared.\n";
    echo "\n========================================\n";
    echo "Done! Try scanning attendance now.\n";
} else {
    echo "\nRun with 'fix' to update database: php check_telegram_settings.php fix\n";
}
