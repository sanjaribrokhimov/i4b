<?php
// Этот скрипт нужно запустить один раз для создания базы данных

echo "<h1>Инициализация базы данных</h1>";

// Путь к файлу базы данных
$dbPath = __DIR__ . '/phpServers/database/projects.db';
$dbDir = dirname($dbPath);

// Создаем директорию для базы данных, если она не существует
if (!file_exists($dbDir)) {
    if (mkdir($dbDir, 0777, true)) {
        echo "<p style='color:green'>✓ Директория для базы данных создана: $dbDir</p>";
    } else {
        echo "<p style='color:red'>✗ Не удалось создать директорию: $dbDir</p>";
        echo "<p>Пожалуйста, запустите сначала <a href='fix_permissions.php'>fix_permissions.php</a></p>";
        exit;
    }
} else {
    echo "<p>✓ Директория базы данных существует: $dbDir</p>";
}

// Проверяем права на директорию
if (!is_writable($dbDir)) {
    echo "<p style='color:red'>✗ Директория НЕ доступна для записи!</p>";
    echo "<p>Пожалуйста, запустите сначала <a href='fix_permissions.php'>fix_permissions.php</a></p>";
    exit;
}

try {
    // Подключаемся к базе данных (создаст файл, если он не существует)
    $db = new SQLite3($dbPath);
    echo "<p style='color:green'>✓ База данных успешно создана по пути: $dbPath</p>";
    
    // Создаем таблицу проектов
    $db->exec('
    CREATE TABLE IF NOT EXISTS projects (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        short_description TEXT NOT NULL,
        full_description TEXT NOT NULL,
        technologies TEXT NOT NULL,
        main_image TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )');
    echo "<p style='color:green'>✓ Таблица projects создана</p>";
    
    // Создаем таблицу для дополнительных изображений проектов
    $db->exec('
    CREATE TABLE IF NOT EXISTS project_images (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        project_id INTEGER NOT NULL,
        image_path TEXT NOT NULL,
        sort_order INTEGER DEFAULT 0,
        FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
    )');
    echo "<p style='color:green'>✓ Таблица project_images создана</p>";
    
    // Создаем таблицу пользователей для админки
    $db->exec('
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        is_admin INTEGER DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )');
    echo "<p style='color:green'>✓ Таблица users создана</p>";
    
    // Добавляем администратора по умолчанию (логин: admin, пароль: admin123)
    $checkAdmin = $db->query("SELECT COUNT(*) as count FROM users WHERE username = 'admin'");
    $adminExists = $checkAdmin->fetchArray(SQLITE3_ASSOC)['count'] > 0;
    
    if (!$adminExists) {
        $password = password_hash('admin123', PASSWORD_DEFAULT);
        $db->exec("INSERT INTO users (username, password, is_admin) VALUES ('admin', '$password', 1)");
        echo "<p style='color:green'>✓ Администратор создан успешно (логин: admin, пароль: admin123)</p>";
    } else {
        echo "<p>✓ Администратор уже существует</p>";
    }
    
    // Устанавливаем права на файл базы данных
    if (chmod($dbPath, 0666)) {
        echo "<p style='color:green'>✓ Права на файл базы данных установлены (666)</p>";
    } else {
        echo "<p style='color:orange'>⚠ Не удалось установить права на файл базы данных</p>";
    }
    
    // Закрываем соединение
    $db->close();
    echo "<h2 style='color:green'>База данных инициализирована успешно!</h2>";
    echo "<p><a href='projects.php' class='button'>Перейти к проектам</a></p>";
    
} catch (Exception $e) {
    echo "<h2 style='color:red'>Ошибка: " . $e->getMessage() . "</h2>";
    echo "<p>Проверьте права доступа к директории: $dbDir</p>";
    
    // Проверяем права доступа
    if (is_writable($dbDir)) {
        echo "<p>✓ Директория доступна для записи</p>";
    } else {
        echo "<p style='color:red'>✗ Директория НЕ доступна для записи. Установите права 777 на директорию</p>";
    }
    
    echo "<p>Пожалуйста, запустите <a href='fix_permissions.php'>fix_permissions.php</a> для исправления прав доступа</p>";
}
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