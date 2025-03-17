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
$error = '';
$success = '';

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

// Обработка формы редактирования проекта
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $short_description = $_POST['short_description'] ?? '';
    $full_description = $_POST['full_description'] ?? '';
    $technologies = $_POST['technologies'] ?? '';
    
    // Проверяем обязательные поля
    if (empty($title) || empty($short_description) || empty($full_description) || empty($technologies)) {
        $error = 'Пожалуйста, заполните все обязательные поля';
    } else {
        // Обработка загрузки нового основного изображения (если загружено)
        $main_image = $project['main_image']; // По умолчанию оставляем текущее изображение
        
        if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] == 0) {
            $upload_dir = '../uploads/projects/';
            
            // Создаем директорию, если она не существует
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_name = time() . '_' . basename($_FILES['main_image']['name']);
            $target_file = $upload_dir . $file_name;
            
            // Проверяем тип файла
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($_FILES['main_image']['type'], $allowed_types)) {
                $error = 'Разрешены только изображения форматов JPG, PNG, GIF и WEBP';
            } else {
                // Загружаем файл
                if (move_uploaded_file($_FILES['main_image']['tmp_name'], $target_file)) {
                    $main_image = $target_file;
                    
                    // Удаляем старое изображение, если оно существует и не используется в других проектах
                    if (!empty($project['main_image']) && file_exists($project['main_image'])) {
                        // Проверяем, используется ли изображение в других проектах
                        $stmt = $db->prepare('SELECT COUNT(*) as count FROM projects WHERE main_image = :image_path AND id != :project_id');
                        $stmt->bindValue(':image_path', $project['main_image'], SQLITE3_TEXT);
                        $stmt->bindValue(':project_id', $project_id, SQLITE3_INTEGER);
                        $result = $stmt->execute();
                        $count = $result->fetchArray(SQLITE3_ASSOC)['count'];
                        
                        if ($count == 0) {
                            unlink($project['main_image']);
                        }
                    }
                } else {
                    $error = 'Ошибка при загрузке изображения';
                }
            }
        }
        
        // Если нет ошибок, обновляем проект в базе данных
        if (empty($error)) {
            $stmt = $db->prepare('
                UPDATE projects 
                SET title = :title, 
                    short_description = :short_description, 
                    full_description = :full_description, 
                    technologies = :technologies, 
                    main_image = :main_image
                WHERE id = :id
            ');
            
            $stmt->bindValue(':title', $title, SQLITE3_TEXT);
            $stmt->bindValue(':short_description', $short_description, SQLITE3_TEXT);
            $stmt->bindValue(':full_description', $full_description, SQLITE3_TEXT);
            $stmt->bindValue(':technologies', $technologies, SQLITE3_TEXT);
            $stmt->bindValue(':main_image', $main_image, SQLITE3_TEXT);
            $stmt->bindValue(':id', $project_id, SQLITE3_INTEGER);
            
            $result = $stmt->execute();
            
            if ($result) {
                // Обработка дополнительных изображений
                if (isset($_FILES['project_images'])) {
                    // Проверяем, есть ли загруженные файлы
                    $hasUploadedFiles = false;
                    foreach ($_FILES['project_images']['error'] as $error) {
                        if ($error === UPLOAD_ERR_OK) {
                            $hasUploadedFiles = true;
                            break;
                        }
                    }
                    
                    if ($hasUploadedFiles) {
                        // Создаем директорию для загрузки, если она не существует
                        $upload_dir = '../uploads/projects/' . $project_id . '/';
                        if (!file_exists($upload_dir)) {
                            mkdir($upload_dir, 0777, true);
                        }
                        
                        // Получаем максимальный порядок сортировки
                        $stmt = $db->prepare('SELECT MAX(sort_order) as max_order FROM project_images WHERE project_id = :project_id');
                        $stmt->bindValue(':project_id', $project_id, SQLITE3_INTEGER);
                        $result = $stmt->execute();
                        $max_order = $result->fetchArray(SQLITE3_ASSOC)['max_order'] ?? 0;
                        
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
                                        
                                        $relative_path = 'uploads/projects/' . $project_id . '/' . $file_name;
                                        $stmt->bindValue(':project_id', $project_id, SQLITE3_INTEGER);
                                        $stmt->bindValue(':image_path', $relative_path, SQLITE3_TEXT);
                                        $stmt->bindValue(':sort_order', $max_order + $i + 1, SQLITE3_INTEGER);
                                        
                                        $stmt->execute();
                                    }
                                }
                            }
                        }
                    }
                }
                
                // Обработка удаления изображений
                if (isset($_POST['delete_images']) && is_array($_POST['delete_images'])) {
                    foreach ($_POST['delete_images'] as $image_id) {
                        // Получаем информацию об изображении
                        $stmt = $db->prepare('SELECT image_path FROM project_images WHERE id = :id');
                        $stmt->bindValue(':id', $image_id, SQLITE3_INTEGER);
                        $result = $stmt->execute();
                        $image = $result->fetchArray(SQLITE3_ASSOC);
                        
                        if ($image) {
                            // Удаляем файл
                            $file_path = '../' . $image['image_path'];
                            if (file_exists($file_path)) {
                                unlink($file_path);
                            }
                            
                            // Удаляем запись из базы данных
                            $stmt = $db->prepare('DELETE FROM project_images WHERE id = :id');
                            $stmt->bindValue(':id', $image_id, SQLITE3_INTEGER);
                            $stmt->execute();
                        }
                    }
                }
                
                // Обработка изменения порядка изображений
                if (isset($_POST['image_order']) && is_array($_POST['image_order'])) {
                    foreach ($_POST['image_order'] as $image_id => $order) {
                        $stmt = $db->prepare('UPDATE project_images SET sort_order = :sort_order WHERE id = :id');
                        $stmt->bindValue(':sort_order', $order, SQLITE3_INTEGER);
                        $stmt->bindValue(':id', $image_id, SQLITE3_INTEGER);
                        $stmt->execute();
                    }
                }
                
                $success = 'Проект успешно обновлен';
                
                // Обновляем данные проекта для отображения в форме
                $stmt = $db->prepare('SELECT * FROM projects WHERE id = :id');
                $stmt->bindValue(':id', $project_id, SQLITE3_INTEGER);
                $result = $stmt->execute();
                $project = $result->fetchArray(SQLITE3_ASSOC);
            } else {
                $error = 'Ошибка при обновлении проекта';
            }
        }
    }
}

// Получаем дополнительные изображения проекта
$stmt = $db->prepare('SELECT * FROM project_images WHERE project_id = :project_id ORDER BY sort_order ASC');
$stmt->bindValue(':project_id', $project_id, SQLITE3_INTEGER);
$imagesResult = $stmt->execute();
$images = [];
while ($image = $imagesResult->fetchArray(SQLITE3_ASSOC)) {
    $images[] = $image;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование проекта - I4B AGENCY</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/image-upload.css">
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
                    <li><a href="add_project.php"><i class="fas fa-plus-circle"></i> Добавить проект</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Выйти</a></li>
                </ul>
            </nav>
        </div>
        
        <div class="admin-content">
            <div class="admin-header">
                <h1>Редактирование проекта</h1>
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
                <form action="edit_project.php?id=<?php echo $project_id; ?>" method="post" enctype="multipart/form-data" class="project-form">
                    <div class="form-group">
                        <label for="title">Название проекта <span class="required">*</span></label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($project['title']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="short_description">Краткое описание <span class="required">*</span></label>
                        <textarea id="short_description" name="short_description" rows="3" required><?php echo htmlspecialchars($project['short_description']); ?></textarea>
                        <small>Краткое описание проекта для отображения в списке проектов (до 200 символов)</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="full_description">Полное описание <span class="required">*</span></label>
                        <textarea id="full_description" name="full_description" rows="10" required><?php echo htmlspecialchars($project['full_description']); ?></textarea>
                        <small>Подробное описание проекта для страницы детального просмотра</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="technologies">Технологии <span class="required">*</span></label>
                        <input type="text" id="technologies" name="technologies" value="<?php echo htmlspecialchars($project['technologies']); ?>" required>
                        <small>Список технологий, использованных в проекте (через запятую)</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Текущее основное изображение</label>
                        <div class="current-image">
                            <img src="<?php echo htmlspecialchars($project['main_image']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="main_image">Новое основное изображение</label>
                        <input type="file" id="main_image" name="main_image" accept="image/*">
                        <small>Загрузите новое изображение, если хотите заменить текущее</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="project_images">Дополнительные изображения (до 5 фото)</label>
                        <div class="image-upload-container">
                            <div class="image-preview-container">
                                <?php
                                // Получаем существующие изображения проекта
                                $stmt = $db->prepare('SELECT * FROM project_images WHERE project_id = :project_id ORDER BY sort_order ASC');
                                $stmt->bindValue(':project_id', $project_id, SQLITE3_INTEGER);
                                $imagesResult = $stmt->execute();
                                $existingImages = [];
                                $imageCount = 0;
                                
                                while ($image = $imagesResult->fetchArray(SQLITE3_ASSOC)) {
                                    $existingImages[] = $image;
                                    $imageCount++;
                                    ?>
                                    <div class="image-preview-item" data-id="<?php echo $image['id']; ?>">
                                        <img src="<?php echo '../' . $image['image_path']; ?>" alt="Изображение проекта">
                                        <div class="image-preview-actions">
                                            <button type="button" class="btn-delete-image" data-id="<?php echo $image['id']; ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button type="button" class="btn-move-up" <?php echo ($image['sort_order'] == 1) ? 'disabled' : ''; ?>>
                                                <i class="fas fa-arrow-up"></i>
                                            </button>
                                            <button type="button" class="btn-move-down" <?php echo ($image['sort_order'] == $imageCount) ? 'disabled' : ''; ?>>
                                                <i class="fas fa-arrow-down"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <?php
                                }
                                
                                // Добавляем пустые слоты для новых изображений
                                for ($i = $imageCount; $i < 5; $i++) {
                                    ?>
                                    <div class="image-upload-slot">
                                        <input type="file" name="project_images[]" id="project_image_<?php echo $i; ?>" class="image-upload" accept="image/*">
                                        <label for="project_image_<?php echo $i; ?>" class="image-upload-label">
                                            <i class="fas fa-plus"></i>
                                            <span>Добавить фото</span>
                                        </label>
                                        <div class="image-preview"></div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <p class="form-hint">Загрузите до 5 дополнительных изображений проекта. Поддерживаемые форматы: JPG, PNG, WebP.</p>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Сохранить изменения</button>
                        <a href="index.php" class="btn-secondary"><i class="fas fa-times"></i> Отмена</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="js/image-upload.js"></script>
</body>
</html>
<?php
// Закрываем соединение с базой данных
$db->close();
?> 