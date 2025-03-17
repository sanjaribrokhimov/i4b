// Добавьте этот код в обработчик формы проекта

// Обработка дополнительных изображений
if (isset($_FILES['project_images']) && is_array($_FILES['project_images']['name'])) {
    $uploadDir = '../uploads/projects/' . $projectId . '/gallery/';
    
    // Создаем директорию, если она не существует
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    // Получаем максимальный порядок сортировки
    $maxSortOrder = 0;
    $stmt = $db->prepare('SELECT MAX(sort_order) as max_order FROM project_images WHERE project_id = :project_id');
    $stmt->bindValue(':project_id', $projectId, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    if ($row && $row['max_order'] !== null) {
        $maxSortOrder = (int)$row['max_order'];
    }
    
    // Обрабатываем каждое загруженное изображение
    foreach ($_FILES['project_images']['name'] as $key => $name) {
        if ($_FILES['project_images']['error'][$key] === UPLOAD_ERR_OK && !empty($_FILES['project_images']['name'][$key])) {
            $tmpName = $_FILES['project_images']['tmp_name'][$key];
            $fileName = time() . '_' . basename($name);
            $filePath = $uploadDir . $fileName;
            
            // Проверяем тип файла
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            $fileType = $_FILES['project_images']['type'][$key];
            
            if (in_array($fileType, $allowedTypes)) {
                // Перемещаем файл в директорию загрузки
                if (move_uploaded_file($tmpName, $filePath)) {
                    // Увеличиваем порядок сортировки
                    $maxSortOrder++;
                    
                    // Сохраняем информацию о файле в базе данных
                    $relativePath = 'uploads/projects/' . $projectId . '/gallery/' . $fileName;
                    $stmt = $db->prepare('INSERT INTO project_images (project_id, image_path, sort_order) VALUES (:project_id, :image_path, :sort_order)');
                    $stmt->bindValue(':project_id', $projectId, SQLITE3_INTEGER);
                    $stmt->bindValue(':image_path', $relativePath, SQLITE3_TEXT);
                    $stmt->bindValue(':sort_order', $maxSortOrder, SQLITE3_INTEGER);
                    $stmt->execute();
                }
            }
        }
    }
}

// Обработка удаления изображений
if (isset($_POST['delete_images']) && is_array($_POST['delete_images'])) {
    foreach ($_POST['delete_images'] as $imageId) {
        // Получаем путь к файлу перед удалением
        $stmt = $db->prepare('SELECT image_path FROM project_images WHERE id = :id');
        $stmt->bindValue(':id', $imageId, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $image = $result->fetchArray(SQLITE3_ASSOC);
        
        if ($image) {
            // Удаляем файл
            $filePath = '../' . $image['image_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            // Удаляем запись из базы данных
            $stmt = $db->prepare('DELETE FROM project_images WHERE id = :id');
            $stmt->bindValue(':id', $imageId, SQLITE3_INTEGER);
            $stmt->execute();
        }
    }
    
    // Пересортировка оставшихся изображений
    $stmt = $db->prepare('SELECT id FROM project_images WHERE project_id = :project_id ORDER BY sort_order ASC');
    $stmt->bindValue(':project_id', $projectId, SQLITE3_INTEGER);
    $result = $stmt->execute();
    
    $sortOrder = 1;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $updateStmt = $db->prepare('UPDATE project_images SET sort_order = :sort_order WHERE id = :id');
        $updateStmt->bindValue(':sort_order', $sortOrder, SQLITE3_INTEGER);
        $updateStmt->bindValue(':id', $row['id'], SQLITE3_INTEGER);
        $updateStmt->execute();
        $sortOrder++;
    }
} 