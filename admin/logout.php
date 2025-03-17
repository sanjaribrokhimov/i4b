<?php
session_start();

// Очищаем все данные сессии
$_SESSION = array();

// Уничтожаем сессию
session_destroy();

// Перенаправляем на страницу входа
header('Location: login.php');
exit;
?> 