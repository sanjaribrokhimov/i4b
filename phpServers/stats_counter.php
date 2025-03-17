<?php
// Файл для хранения счетчика
$counterFile = 'counter.txt';

// Функция для увеличения счетчика
function incrementCounter() {
    global $counterFile;
    
    // Если файл не существует, создаем его с начальным значением
    if (!file_exists($counterFile)) {
        file_put_contents($counterFile, '1');
        return 1;
    }
    
    // Читаем текущее значение
    $count = (int)file_get_contents($counterFile);
    
    // Увеличиваем счетчик
    $count++;
    
    // Записываем новое значение
    file_put_contents($counterFile, (string)$count);
    
    return $count;
}

// Функция для получения текущего значения счетчика
function getCounter() {
    global $counterFile;
    
    if (!file_exists($counterFile)) {
        return 0;
    }
    
    return (int)file_get_contents($counterFile);
}

// Увеличиваем счетчик только если посетитель не бот
if (!isBot($_SERVER['HTTP_USER_AGENT'])) {
    // Проверяем, не запущена ли уже сессия
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    $sessionTimeout = 24 * 60 * 60; // 24 часа
    
    if (!isset($_SESSION['counted']) || (time() - $_SESSION['counted']) > $sessionTimeout) {
        incrementCounter();
        $_SESSION['counted'] = time();
    }
}
?> 