<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: login.php');
    exit;
}

// Подключаемся к базе данных
try {
    $dbPath = __DIR__ . '/../phpServers/database/projects.db';
    
    // Проверяем существование файла базы данных
    if (!file_exists($dbPath)) {
        throw new Exception("База данных не найдена. Пожалуйста, запустите скрипт ../init_database.php для создания базы данных.");
    }
    
    $db = new SQLite3($dbPath);
    
    $error = '';
    $success = '';
    
    // Обработка формы добавления проекта
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Получаем данные из формы
        $title = trim($_POST['title'] ?? '');
        $short_description = trim($_POST['short_description'] ?? '');
        $full_description = trim($_POST['full_description'] ?? '');
        $technologies = trim($_POST['technologies'] ?? '');
        
        // Проверяем обязательные поля
        if (empty($title) || empty($short_description) || empty($full_description) || empty($technologies)) {
            $error = 'Пожалуйста, заполните все обязательные поля';
        } else {
            // Обрабатываем загрузку основного изображения
            $main_image = '';
            if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] == 0) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
                $file_type = $_FILES['main_image']['type'];
                
                if (in_array($file_type, $allowed_types)) {
                    // Создаем директорию для загрузки, если она не существует
                    $upload_dir = __DIR__ . '/../uploads/projects/';
                    if (!file_exists($upload_dir)) {
                        mkdir($upload_dir, 0775, true);
                    }
                    
                    $file_name = time() . '_' . basename($_FILES['main_image']['name']);
                    $target_file = $upload_dir . $file_name;
                    
                    if (move_uploaded_file($_FILES['main_image']['tmp_name'], $target_file)) {
                        $main_image = 'uploads/projects/' . $file_name;
                    } else {
                        $error = 'Ошибка при загрузке изображения. Проверьте права доступа к директории ' . $upload_dir;
                    }
                } else {
                    $error = 'Недопустимый тип файла. Разрешены только JPG, PNG, WebP и GIF';
                }
            } else {
                $error = 'Пожалуйста, загрузите основное изображение проекта';
            }
            
            // Если нет ошибок, добавляем проект в базу данных
            if (empty($error)) {
                $stmt = $db->prepare('
                    INSERT INTO projects (title, short_description, full_description, technologies, main_image, created_at)
                    VALUES (:title, :short_description, :full_description, :technologies, :main_image, :created_at)
                ');
                
                $stmt->bindValue(':title', $title, SQLITE3_TEXT);
                $stmt->bindValue(':short_description', $short_description, SQLITE3_TEXT);
                $stmt->bindValue(':full_description', $full_description, SQLITE3_TEXT);
                $stmt->bindValue(':technologies', $technologies, SQLITE3_TEXT);
                $stmt->bindValue(':main_image', $main_image, SQLITE3_TEXT);
                $stmt->bindValue(':created_at', date('Y-m-d H:i:s'), SQLITE3_TEXT);
                
                $result = $stmt->execute();
                
                if ($result) {
                    // Получаем ID добавленного проекта
                    $project_id = $db->lastInsertRowID();
                    
                    // Обработка дополнительных изображений
                    if (isset($_FILES['project_images']) && is_array($_FILES['project_images']['name'])) {
                        // Создаем директорию для загрузки, если она не существует
                        $upload_dir = '../uploads/projects/' . $project_id . '/';
                        if (!file_exists($upload_dir)) {
                            mkdir($upload_dir, 0777, true);
                        }
                        
                        // Обрабатываем каждый загруженный файл
                        for ($i = 0; $i < count($_FILES['project_images']['name']); $i++) {
                            if ($_FILES['project_images']['error'][$i] === UPLOAD_ERR_OK && !empty($_FILES['project_images']['name'][$i])) {
                                $file_name = time() . '_' . $i . '_' . basename($_FILES['project_images']['name'][$i]);
                                $target_file = $upload_dir . $file_name;
                                
                                // Проверяем тип файла
                                $file_type = $_FILES['project_images']['type'][$i];
                                $allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
                                
                                if (in_array($file_type, $allowed_types)) {
                                    // Загружаем файл
                                    if (move_uploaded_file($_FILES['project_images']['tmp_name'][$i], $target_file)) {
                                        // Добавляем запись в базу данных
                                        $stmt = $db->prepare('
                                            INSERT INTO project_images (project_id, image_path, sort_order)
                                            VALUES (:project_id, :image_path, :sort_order)
                                        ');
                                        
                                        $stmt->bindValue(':project_id', $project_id, SQLITE3_INTEGER);
                                        $stmt->bindValue(':image_path', 'uploads/projects/' . $project_id . '/' . $file_name, SQLITE3_TEXT);
                                        $stmt->bindValue(':sort_order', $i + 1, SQLITE3_INTEGER);
                                        $stmt->execute();
                                    }
                                }
                            }
                        }
                    }
                    
                    $success = 'Проект успешно добавлен';
                    
                    // Очищаем форму после успешного добавления
                    $title = '';
                    $short_description = '';
                    $full_description = '';
                    $technologies = '';
                } else {
                    $error = 'Ошибка при добавлении проекта';
                }
            }
        }
    }
} catch (Exception $e) {
    $error = 'Ошибка базы данных: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавление проекта - I4B AGENCY</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="css/image-upload.css">
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
                    <li><a href="index.php"><i class="fas fa-project-diagram"></i> Проекты</a></li>
                    <li class="active"><a href="add_project.php"><i class="fas fa-plus-circle"></i> Добавить проект</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Выйти</a></li>
                </ul>
            </nav>
        </div>
        
        <div class="admin-content">
            <div class="admin-header">
                <h1>Добавление нового проекта</h1>
                <a href="index.php" class="back-btn"><i class="fas fa-arrow-left"></i> Вернуться к списку</a>
            </div>
            
            <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
            </div>
            <?php endif; ?>
            
            <div class="form-container">
                <form action="add_project.php" method="post" enctype="multipart/form-data" class="project-form">
                    <div class="form-group">
                        <label for="title">Название проекта <span class="required">*</span></label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="short_description">Краткое описание <span class="required">*</span></label>
                        <textarea id="short_description" name="short_description" rows="3" required><?php echo htmlspecialchars($short_description ?? ''); ?></textarea>
                        <small>Краткое описание проекта для отображения в списке проектов (до 200 символов)</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="full_description">Полное описание <span class="required">*</span></label>
                        <textarea id="full_description" name="full_description" rows="10" required><?php echo htmlspecialchars($full_description ?? ''); ?></textarea>
                        <small>Подробное описание проекта для страницы детального просмотра</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="technologies">Технологии <span class="required">*</span></label>
                        <input type="text" id="technologies" name="technologies" value="<?php echo htmlspecialchars($technologies ?? ''); ?>" required>
                        <small>Список технологий, использованных в проекте (через запятую)</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="main_image">Основное изображение <span class="required">*</span></label>
                        <input type="file" id="main_image" name="main_image" accept="image/*" required>
                        <small>Загрузите основное изображение проекта. Рекомендуемый размер: 1200x800 пикселей</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="project_images">Дополнительные изображения (до 5 фото)</label>
                        <div class="image-upload-container">
                            <div class="image-preview-container">
                                <?php for ($i = 0; $i < 5; $i++): ?>
                                <div class="image-upload-slot">
                                    <input type="file" name="project_images[]" id="project_image_<?php echo $i; ?>" class="image-upload" accept="image/*">
                                    <label for="project_image_<?php echo $i; ?>" class="image-upload-label">
                                        <i class="fas fa-plus"></i>
                                        <span>Добавить фото</span>
                                    </label>
                                    <div class="image-preview"></div>
                                </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <p class="form-hint">Загрузите до 5 дополнительных изображений проекта. Поддерживаемые форматы: JPG, PNG, WebP.</p>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-primary"><i class="fas fa-plus-circle"></i> Добавить проект</button>
                        <button type="reset" class="btn-secondary"><i class="fas fa-undo"></i> Сбросить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
    // Скрипт для предпросмотра загружаемых изображений
    document.addEventListener('DOMContentLoaded', function() {
        const imageUploads = document.querySelectorAll('.image-upload');
        
        imageUploads.forEach(input => {
            input.addEventListener('change', function() {
                const file = this.files[0];
                const preview = this.parentElement.querySelector('.image-preview');
                const label = this.parentElement.querySelector('.image-upload-label');
                
                if (file) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        preview.innerHTML = `<img src="${e.target.result}" alt="Предпросмотр">`;
                        preview.style.display = 'block';
                        label.style.display = 'none';
                    };
                    
                    reader.readAsDataURL(file);
                }
            });
        });
    });
    </script>
</body>
</html>
<?php
// Закрываем соединение с базой данных
if (isset($db)) {
    $db->close();
}
?> 