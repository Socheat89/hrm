<?php
require 'vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$token = \Illuminate\Support\Str::random(10);
$chatId = '7372079283';
$botToken = '7704406393:AAF27v7soy5S-hlnWrRTiURCT8Bk_lhALjE';

$message = "Test message";

$keyboard = [
    'inline_keyboard' => [
        [
            ['text' => '✅ Approve', 'callback_data' => 'approve_'.$token],
            ['text' => '❌ Reject', 'callback_data' => 'reject_'.$token]
        ]
    ]
];

$url = "https://api.telegram.org/bot{$botToken}/sendMessage";

$res = \Illuminate\Support\Facades\Http::post($url, [
    'chat_id' => $chatId,
    'text' => $message,
    'parse_mode' => 'Markdown',
    'reply_markup' => json_encode($keyboard)
]);

echo "With json_encode:\n";
print_r($res->json());

$res2 = \Illuminate\Support\Facades\Http::post($url, [
    'chat_id' => $chatId,
    'text' => "Test message 2",
    'parse_mode' => 'Markdown',
    'reply_markup' => $keyboard
]);

echo "\nWithout json_encode:\n";
print_r($res2->json());

