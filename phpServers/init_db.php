<?php
// Путь к файлу базы данных
$dbPath = __DIR__ . '/database/projects.db';

// Создаем директорию для базы данных, если она не существует
if (!file_exists(dirname($dbPath))) {
    mkdir(dirname($dbPath), 0777, true);
}

// Подключаемся к базе данных (создаст файл, если он не существует)
$db = new SQLite3($dbPath);

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

// Создаем таблицу для дополнительных изображений проектов
$db->exec('
CREATE TABLE IF NOT EXISTS project_images (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    project_id INTEGER NOT NULL,
    image_path TEXT NOT NULL,
    sort_order INTEGER DEFAULT 0,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
)');

// Создаем таблицу пользователей для админки
$db->exec('
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    is_admin INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)');

// Добавляем администратора по умолчанию (логин: admin, пароль: admin123)
$checkAdmin = $db->query("SELECT COUNT(*) as count FROM users WHERE username = 'admin'");
$adminExists = $checkAdmin->fetchArray(SQLITE3_ASSOC)['count'] > 0;

if (!$adminExists) {
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $db->exec("INSERT INTO users (username, password, is_admin) VALUES ('admin', '$password', 1)");
    echo "Администратор создан успешно.\n";
}

echo "База данных инициализирована успешно.\n";

// Закрываем соединение
$db->close();
?> 