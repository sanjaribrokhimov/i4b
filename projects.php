<?php
// Подключаем трекер посещений и счетчик
include_once 'phpServers/visitor_tracker.php';
include_once 'phpServers/stats_counter.php';

// Получаем текущее количество посещений
$visitorCount = getCounter();

try {
    // Подключаемся к базе данных с абсолютным путем
    $dbPath = __DIR__ . '/phpServers/database/projects.db';
    
    // Проверяем существование файла базы данных
    if (!file_exists($dbPath)) {
        throw new Exception("База данных не найдена. Пожалуйста, запустите скрипт init_database.php для создания базы данных.");
    }
    
    $db = new SQLite3($dbPath);
    
    // Получаем все проекты, отсортированные по дате создания (новые сначала)
    $projects = $db->query('SELECT * FROM projects ORDER BY created_at DESC');
    
} catch (Exception $e) {
    // Выводим сообщение об ошибке
    echo '<div style="color: red; padding: 20px; background-color: #ffe6e6; border: 1px solid #ff0000; margin: 20px;">';
    echo '<h3>Ошибка базы данных:</h3>';
    echo '<p>' . $e->getMessage() . '</p>';
    echo '<p>Если вы видите это сообщение, значит база данных не была инициализирована. ';
    echo 'Пожалуйста, запустите скрипт <a href="init_database.php">init_database.php</a> для создания базы данных.</p>';
    echo '</div>';
    
    // Создаем пустой объект для избежания ошибок в шаблоне
    $projects = new class {
        public function fetchArray() {
            return false;
        }
    };
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>I4B AGENCY - Проекты</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/projects.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</head>
<body>
    <?php include 'components/header.php'; ?>

    <section class="projects-hero">
        <div class="left-border"></div>
        <div class="right-border"></div>
        <div class="hero-diagonal-lines">
            <div class="diagonal-line" style="--rotation: 30deg;"></div>
            <div class="diagonal-line" style="--rotation: -25deg;"></div>
            <div class="diagonal-line" style="--rotation: 20deg;"></div>
        </div>
        <div class="container">
            <h1 class="projects-title" data-aos="fade-up">Наши проекты</h1>
            <p class="projects-subtitle" data-aos="fade-up" data-aos-delay="100">Инновационные решения для бизнеса</p>
        </div>
    </section>

    <section class="projects-section">
        <div class="container">
            <div class="projects-grid">
                <?php 
                $hasProjects = false;
                while ($project = $projects->fetchArray(SQLITE3_ASSOC)): 
                    $hasProjects = true;
                ?>
                <div class="project-card" data-aos="fade-up">
                    <div class="card-glow"></div>
                    <div class="project-image">
                        <img src="<?php echo htmlspecialchars($project['main_image']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>">
                        <div class="project-overlay">
                            <a href="project.php?id=<?php echo $project['id']; ?>" class="view-project-btn">
                                <span>Подробнее</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="project-content">
                        <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                        <div class="project-divider"></div>
                        <p><?php echo htmlspecialchars(substr($project['short_description'], 0, 100)) . (strlen($project['short_description']) > 100 ? '...' : ''); ?></p>
                        <div class="project-tags">
                            <?php 
                            $technologies = explode(',', $project['technologies']);
                            foreach ($technologies as $tech): 
                                $tech = trim($tech);
                                if (!empty($tech)):
                            ?>
                                <span><?php echo htmlspecialchars($tech); ?></span>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                        <a href="project.php?id=<?php echo $project['id']; ?>" class="view-project-btn-mobile">
                        <span>Подробнее</span>
                        <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="project-hover-overlay"></div>
                </div>
                <?php endwhile; ?>
                
                <?php if (!$hasProjects): ?>
                <div class="no-projects" data-aos="fade-up">
                    <h3>Проекты пока не добавлены</h3>
                    <p>Скоро здесь появятся наши проекты. Вы можете <a href="admin/login.php">войти в админ-панель</a> и добавить проекты.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php include 'components/footer.php'; ?>

    <script src="js/script.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
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
