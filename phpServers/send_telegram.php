<?php
// Разрешаем кросс-доменные запросы
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Логирование для отладки
$logFile = 'telegram_log.txt';
file_put_contents($logFile, date('Y-m-d H:i:s') . " - Запрос получен\n", FILE_APPEND);

// Получаем данные из POST запроса
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - Ошибка: данные не получены\n", FILE_APPEND);
    echo json_encode(['ok' => false, 'description' => 'Данные не получены']);
    exit;
}

// Логируем полученные данные
file_put_contents($logFile, date('Y-m-d H:i:s') . " - Данные: " . json_encode($data, JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND);

// Токен бота и ID чата
$botToken = '6910997638:AAFrYa7t7QojNxC406LEcsnmEJ3Xny2D0wQ';
$chatId = '-1002546780570';

// Формируем сообщение
$message = "📝 *Новая заявка с сайта!*\n\n";
$message .= "👤 *Имя*: " . $data['name'] . "\n";
if (!empty($data['company'])) {
    $message .= "🏢 *Компания*: " . $data['company'] . "\n";
}
$message .= "📱 *Telegram*: " . $data['telegram'] . "\n";
if (!empty($data['phone'])) {
    $message .= "☎️ *Телефон*: " . $data['phone'] . "\n";
}
$message .= "💬 *Сообщение*: \n" . $data['message'];

// URL для отправки сообщения через Telegram Bot API
$url = "https://api.telegram.org/bot{$botToken}/sendMessage";

// Параметры запроса
$params = [
    'chat_id' => $chatId,
    'text' => $message,
    'parse_mode' => 'Markdown'
];

// Логируем отправляемые данные
file_put_contents($logFile, date('Y-m-d H:i:s') . " - Отправка в Telegram: " . json_encode($params, JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND);

// Отправляем запрос к Telegram Bot API
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

// Логируем ответ
if ($error) {
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - CURL ошибка: " . $error . "\n", FILE_APPEND);
    echo json_encode(['ok' => false, 'description' => 'CURL Error: ' . $error]);
} else {
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - Ответ Telegram: " . $response . "\n", FILE_APPEND);
    echo $response;
}
?> 