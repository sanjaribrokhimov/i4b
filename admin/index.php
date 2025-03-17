<?php
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: login.php');
    exit;
}

try {
    // Подключаемся к базе данных с абсолютным путем
    $dbPath = __DIR__ . '/../phpServers/database/projects.db';
    
    // Проверяем существование файла базы данных
    if (!file_exists($dbPath)) {
        throw new Exception("База данных не найдена. Пожалуйста, запустите скрипт ../init_database.php для создания базы данных.");
    }
    
    $db = new SQLite3($dbPath);
    
    // Получаем все проекты, отсортированные по дате создания (новые сначала)
    $projects = $db->query('SELECT * FROM projects ORDER BY created_at DESC');
    
} catch (Exception $e) {
    // Выводим сообщение об ошибке
    echo '<div style="color: red; padding: 20px; background-color: #ffe6e6; border: 1px solid #ff0000; margin: 20px;">';
    echo '<h3>Ошибка базы данных:</h3>';
    echo '<p>' . $e->getMessage() . '</p>';
    echo '</div>';
    
    // Создаем пустой объект для избежания ошибок в шаблоне
    $projects = new class {
        public function fetchArray() {
            return false;
        }
        
        public function numColumns() {
            return 0;
        }
    };
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель - I4B AGENCY</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <div class="admin-sidebar">
            <div class="admin-logo">
                <img src="../img/I4Blogo.png" alt="I4B AGENCY">
                <h2>Админ-панель</h2>
            </div>
            <nav class="admin-nav">
                <ul>
                    <li class="active"><a href="index.php"><i class="fas fa-project-diagram"></i> Проекты</a></li>
                    <li><a href="add_project.php" class="btn-add-project"><i class="fas fa-plus-circle"></i> Добавить проект</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Выйти</a></li>
                </ul>
            </nav>
        </div>
        
        <div class="admin-content">
            <div class="admin-header">
                <h1>Управление проектами</h1>
                <a href="add_project.php" class="add-btn"><i class="fas fa-plus"></i> Добавить проект</a>
            </div>
            
            <div class="projects-table-container">
                <table class="projects-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Изображение</th>
                            <th>Название</th>
                            <th>Краткое описание</th>
                            <th>Технологии</th>
                            <th>Дата создания</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($project = $projects->fetchArray(SQLITE3_ASSOC)): ?>
                        <tr>
                            <td><?php echo $project['id']; ?></td>
                            <td>
                                <img src="<?php echo htmlspecialchars($project['main_image']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>" class="project-thumbnail">
                            </td>
                            <td><?php echo htmlspecialchars($project['title']); ?></td>
                            <td><?php echo htmlspecialchars(substr($project['short_description'], 0, 100)) . (strlen($project['short_description']) > 100 ? '...' : ''); ?></td>
                            <td><?php echo htmlspecialchars(substr($project['technologies'], 0, 50)) . (strlen($project['technologies']) > 50 ? '...' : ''); ?></td>
                            <td><?php echo date('d.m.Y H:i', strtotime($project['created_at'])); ?></td>
                            <td class="actions">
                                <a href="edit_project.php?id=<?php echo $project['id']; ?>" class="edit-btn" title="Редактировать"><i class="fas fa-edit"></i></a>
                                <a href="manage_images.php?id=<?php echo $project['id']; ?>" class="images-btn" title="Управление изображениями"><i class="fas fa-images"></i></a>
                                <a href="delete_project.php?id=<?php echo $project['id']; ?>" class="delete-btn" title="Удалить" onclick="return confirm('Вы уверены, что хотите удалить этот проект?');"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        
                        <?php if ($projects->numColumns() == 0): ?>
                        <tr>
                            <td colspan="7" class="no-projects">Проекты не найдены. <a href="add_project.php">Добавить проект</a></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
<?php
// Закрываем соединение с базой данных
if (isset($db)) {
    $db->close();
}
?> 