<?php
session_start();

// Если пользователь уже авторизован, перенаправляем в админ-панель
if (isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
    header('Location: index.php');
    exit;
}

$error = '';
$dbError = false;

// Обработка формы входа
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Пожалуйста, заполните все поля';
    } else {
        try {
            // Подключаемся к базе данных с абсолютным путем
            $dbPath = __DIR__ . '/../phpServers/database/projects.db';
            
            // Проверяем существование файла базы данных
            if (!file_exists($dbPath)) {
                throw new Exception("База данных не найдена. Пожалуйста, запустите скрипт ../init_database.php для создания базы данных.");
            }
            
            $db = new SQLite3($dbPath);
            
            // Ищем пользователя
            $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
            $stmt->bindValue(':username', $username, SQLITE3_TEXT);
            $result = $stmt->execute();
            $user = $result->fetchArray(SQLITE3_ASSOC);
            
            if ($user && password_verify($password, $user['password'])) {
                // Авторизация успешна
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['is_admin'] = 1;
                $_SESSION['admin_logged_in'] = true;
                
                header('Location: index.php');
                exit;
            } else {
                $error = 'Неверное имя пользователя или пароль';
            }
            
            $db->close();
        } catch (Exception $e) {
            $error = 'Ошибка базы данных: ' . $e->getMessage();
            $dbError = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в админ-панель - I4B AGENCY</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-logo">
            <img src="../img/I4Blogo.png" alt="I4B AGENCY">
            <h1>Админ-панель</h1>
        </div>
        
        <form class="login-form" method="post" action="">
            <?php if (!empty($error)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
                <?php if ($dbError): ?>
                <p>Пожалуйста, запустите скрипт <a href="../init_database.php" style="color: #fff; text-decoration: underline;">init_database.php</a> для создания базы данных.</p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="username"><i class="fas fa-user"></i></label>
                <input type="text" id="username" name="username" placeholder="Имя пользователя" required>
            </div>
            
            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i></label>
                <input type="password" id="password" name="password" placeholder="Пароль" required>
            </div>
            
            <button type="submit" class="login-btn">Войти</button>
        </form>
        
        <div class="back-to-site">
            <a href="../index.php"><i class="fas fa-arrow-left"></i> Вернуться на сайт</a>
        </div>
    </div>
</body>
</html> 