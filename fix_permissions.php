<?php
// Скрипт для проверки и исправления прав доступа к базе данных

// Путь к директории базы данных
$dbDir = __DIR__ . '/phpServers/database';
$dbPath = $dbDir . '/projects.db';

echo "<h1>Проверка и исправление прав доступа</h1>";

// Проверяем существование директории
if (!file_exists($dbDir)) {
    echo "<p>Директория для базы данных не существует. Создаем...</p>";
    
    // Создаем директорию с полными правами
    if (mkdir($dbDir, 0777, true)) {
        echo "<p style='color:green'>✓ Директория успешно создана: $dbDir</p>";
    } else {
        echo "<p style='color:red'>✗ Не удалось создать директорию: $dbDir</p>";
        echo "<p>Пожалуйста, создайте директорию вручную и установите права 777:</p>";
        echo "<code>mkdir -p " . $dbDir . "</code><br>";
        echo "<code>chmod -R 777 " . $dbDir . "</code>";
    }
} else {
    echo "<p>✓ Директория базы данных существует: $dbDir</p>";
}

// Проверяем права на директорию
if (is_writable($dbDir)) {
    echo "<p style='color:green'>✓ Директория доступна для записи</p>";
} else {
    echo "<p style='color:red'>✗ Директория НЕ доступна для записи!</p>";
    
    // Пытаемся установить права
    if (chmod($dbDir, 0777)) {
        echo "<p style='color:green'>✓ Права успешно установлены на директорию</p>";
    } else {
        echo "<p style='color:red'>✗ Не удалось установить права на директорию</p>";
        echo "<p>Пожалуйста, установите права вручную:</p>";
        echo "<code>chmod -R 777 " . $dbDir . "</code>";
    }
}

// Проверяем существование файла базы данных
if (file_exists($dbPath)) {
    echo "<p>✓ Файл базы данных существует: $dbPath</p>";
    
    // Проверяем права на файл
    if (is_writable($dbPath)) {
        echo "<p style='color:green'>✓ Файл базы данных доступен для записи</p>";
    } else {
        echo "<p style='color:red'>✗ Файл базы данных НЕ доступен для записи!</p>";
        
        // Пытаемся установить права
        if (chmod($dbPath, 0666)) {
            echo "<p style='color:green'>✓ Права успешно установлены на файл базы данных</p>";
        } else {
            echo "<p style='color:red'>✗ Не удалось установить права на файл базы данных</p>";
            echo "<p>Пожалуйста, установите права вручную:</p>";
            echo "<code>chmod 666 " . $dbPath . "</code>";
        }
    }
} else {
    echo "<p>✗ Файл базы данных не существует. Запустите <a href='init_database.php'>init_database.php</a> для создания базы данных.</p>";
}

// Информация о пользователе веб-сервера
echo "<h2>Информация о пользователе веб-сервера:</h2>";
if (function_exists('posix_getpwuid') && function_exists('posix_geteuid')) {
    $user = posix_getpwuid(posix_geteuid());
    echo "<p>Пользователь: " . $user['name'] . "</p>";
} else {
    echo "<p>Невозможно определить пользователя веб-сервера (функции posix недоступны)</p>";
}

// Проверка расширения SQLite3
echo "<h2>Проверка расширения SQLite3:</h2>";
if (extension_loaded('sqlite3')) {
    echo "<p style='color:green'>✓ Расширение SQLite3 загружено</p>";
} else {
    echo "<p style='color:red'>✗ Расширение SQLite3 НЕ загружено!</p>";
    echo "<p>Вам необходимо установить расширение SQLite3 для PHP.</p>";
}

echo "<p><a href='init_database.php' class='button'>Перейти к инициализации базы данных</a></p>";
?>

<style>
body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    margin: 20px;
    padding: 20px;
    background-color: #f5f5f5;
}
h1, h2 {
    color: #333;
}
p {
    margin: 10px 0;
}
code {
    background-color: #f1f1f1;
    padding: 2px 5px;
    border-radius: 3px;
    font-family: monospace;
    display: block;
    margin: 10px 0;
    padding: 10px;
}
.button {
    display: inline-block;
    background-color: #4CAF50;
    color: white;
    padding: 10px 15px;
    text-decoration: none;
    border-radius: 4px;
    margin-top: 20px;
}
.button:hover {
    background-color: #45a049;
}
</style> 