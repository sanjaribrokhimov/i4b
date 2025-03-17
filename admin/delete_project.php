<?php
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: login.php');
    exit;
}

// Проверяем, передан ли ID проекта
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$project_id = (int)$_GET['id'];

// Подключаемся к базе данных
$db = new SQLite3('../phpServers/database/projects.db');

// Получаем данные проекта
$stmt = $db->prepare('SELECT * FROM projects WHERE id = :id');
$stmt->bindValue(':id', $project_id, SQLITE3_INTEGER);
$result = $stmt->execute();
$project = $result->fetchArray(SQLITE3_ASSOC);

// Если проект не найден, перенаправляем на список проектов
if (!$project) {
    header('Location: index.php');
    exit;
}

// Получаем все изображения проекта
$stmt = $db->prepare('SELECT * FROM project_images WHERE project_id = :project_id');
$stmt->bindValue(':project_id', $project_id, SQLITE3_INTEGER);
$imagesResult = $stmt->execute();
$images = [];
while ($image = $imagesResult->fetchArray(SQLITE3_ASSOC)) {
    $images[] = $image;
}

// Удаляем все изображения проекта
foreach ($images as $image) {
    // Удаляем файл изображения
    if (file_exists($image['image_path'])) {
        unlink($image['image_path']);
    }
}

// Удаляем основное изображение проекта
if (file_exists($project['main_image'])) {
    unlink($project['main_image']);
}

// Удаляем записи о дополнительных изображениях из базы данных
$stmt = $db->prepare('DELETE FROM project_images WHERE project_id = :project_id');
$stmt->bindValue(':project_id', $project_id, SQLITE3_INTEGER);
$stmt->execute();

// Удаляем проект из базы данных
$stmt = $db->prepare('DELETE FROM projects WHERE id = :id');
$stmt->bindValue(':id', $project_id, SQLITE3_INTEGER);
$result = $stmt->execute();

// Закрываем соединение с базой данных
$db->close();

// Перенаправляем на список проектов
header('Location: index.php');
exit;
?> 