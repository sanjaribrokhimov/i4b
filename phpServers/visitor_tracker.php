<?php
// Функция для получения информации о посетителе
function getVisitorInfo() {
    // Получаем IP-адрес
    $ip = $_SERVER['REMOTE_ADDR'];
    if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    
    // Получаем информацию о браузере и ОС
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    
    // Получаем страницу, с которой пришел посетитель
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Прямой переход';
    
    // Получаем текущую страницу
    $currentPage = $_SERVER['REQUEST_URI'];
    
    // Получаем дату и время
    $dateTime = date('Y-m-d H:i:s');
    
    // Получаем информацию о регионе через API (опционально)
    $regionInfo = "Неизвестно";
    try {
        $ipInfo = json_decode(file_get_contents("http://ip-api.com/json/{$ip}"), true);
        if ($ipInfo && $ipInfo['status'] == 'success') {
            $regionInfo = $ipInfo['country'] . ", " . $ipInfo['regionName'] . ", " . $ipInfo['city'];
        }
    } catch (Exception $e) {
        // Если API недоступен, продолжаем без информации о регионе
    }
    
    return [
        'ip' => $ip,
        'user_agent' => $userAgent,
        'referer' => $referer,
        'current_page' => $currentPage,
        'date_time' => $dateTime,
        'region' => $regionInfo
    ];
}

// Функция для отправки уведомления в Telegram
function sendTelegramNotification($visitorInfo) {
    // Токен бота и ID чата (используем те же, что и в send_telegram.php)
    $botToken = '6910997638:AAFrYa7t7QojNxC406LEcsnmEJ3Xny2D0wQ';
    $chatId = '-1002546780570';
    
    // Формируем сообщение
    $message = "🔍 *Новый посетитель на сайте!*\n\n";
    $message .= "📅 *Дата и время*: " . $visitorInfo['date_time'] . "\n";
    $message .= "🌐 *IP-адрес*: " . $visitorInfo['ip'] . "\n";
    $message .= "📍 *Регион*: " . $visitorInfo['region'] . "\n";
    $message .= "🖥️ *Устройство/браузер*: \n" . $visitorInfo['user_agent'] . "\n";
    $message .= "🔗 *Источник перехода*: " . $visitorInfo['referer'] . "\n";
    $message .= "📄 *Текущая страница*: " . $visitorInfo['current_page'] . "\n";
    
    // URL для отправки сообщения через Telegram Bot API
    $url = "https://api.telegram.org/bot{$botToken}/sendMessage";
    
    // Параметры запроса
    $params = [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'Markdown'
    ];
    
    // Отправляем запрос к Telegram Bot API
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
}

// Проверяем, не является ли посетитель ботом
function isBot($userAgent) {
    $botKeywords = ['bot', 'crawler', 'spider', 'slurp', 'search', 'fetch', 'nutch'];
    
    foreach ($botKeywords as $keyword) {
        if (stripos($userAgent, $keyword) !== false) {
            return true;
        }
    }
    
    return false;
}

// Основной код
// Получаем информацию о посетителе
$visitorInfo = getVisitorInfo();

// Проверяем, не является ли посетитель ботом
if (!isBot($visitorInfo['user_agent'])) {
    // Отправляем уведомление в Telegram
    // Используем сессию, чтобы не отправлять уведомление при каждом обновлении страницы
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    $sessionTimeout = 30 * 60; // 30 минут
    
    if (!isset($_SESSION['last_visit']) || (time() - $_SESSION['last_visit']) > $sessionTimeout) {
        sendTelegramNotification($visitorInfo);
        $_SESSION['last_visit'] = time();
    }
}
?> 