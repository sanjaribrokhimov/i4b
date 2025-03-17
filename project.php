<?php
// Включаем кэширование страницы
header("Cache-Control: max-age=3600, public"); // Кэширование на 1 час

// Подключаем трекер посещений и счетчик
include_once 'phpServers/visitor_tracker.php';
include_once 'phpServers/stats_counter.php';

// Получаем текущее количество посещений
$visitorCount = getCounter();

// Проверяем, передан ли ID проекта
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: projects.php');
    exit;
}

$projectId = (int)$_GET['id'];

try {
    // Подключаемся к базе данных с абсолютным путем
    $dbPath = __DIR__ . '/phpServers/database/projects.db';
    
    // Проверяем существование файла базы данных
    if (!file_exists($dbPath)) {
        throw new Exception("База данных не найдена. Пожалуйста, запустите скрипт init_database.php для создания базы данных.");
    }
    
    $db = new SQLite3($dbPath);
    // Включаем кэширование для SQLite
    $db->exec('PRAGMA cache_size = 10000');
    $db->exec('PRAGMA temp_store = MEMORY');
    
    // Получаем данные проекта
    $stmt = $db->prepare('SELECT id, title, short_description, full_description, technologies, main_image FROM projects WHERE id = :id');
    $stmt->bindValue(':id', $projectId, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $project = $result->fetchArray(SQLITE3_ASSOC);
    
    // Если проект не найден, перенаправляем на страницу проектов
    if (!$project) {
        header('Location: projects.php');
        exit;
    }
    
    // Получаем только необходимые поля изображений и ограничиваем количество
    $stmt = $db->prepare('SELECT image_path FROM project_images WHERE project_id = :project_id ORDER BY sort_order ASC LIMIT 3');
    $stmt->bindValue(':project_id', $projectId, SQLITE3_INTEGER);
    $imagesResult = $stmt->execute();
    
    // Сохраняем изображения в массив для использования в шаблоне
    $images = [];
    while ($image = $imagesResult->fetchArray(SQLITE3_ASSOC)) {
        $images[] = $image;
    }
    
    // Закрываем соединение с базой данных сразу после получения данных
    $db->close();
    
} catch (Exception $e) {
    // Выводим сообщение об ошибке
    echo '<div style="color: red; padding: 20px; background-color: #ffe6e6; border: 1px solid #ff0000; margin: 20px;">';
    echo '<h3>Ошибка базы данных:</h3>';
    echo '<p>' . $e->getMessage() . '</p>';
    echo '<p>Если вы видите это сообщение, значит база данных не была инициализирована. ';
    echo 'Пожалуйста, запустите скрипт <a href="init_database.php">init_database.php</a> для создания базы данных.</p>';
    echo '</div>';
    
    // Перенаправляем на страницу проектов
    header('Refresh: 5; URL=projects.php');
    exit;
}

// Разбиваем технологии на массив
$technologies = explode(',', $project['technologies']);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($project['title']); ?> - I4B AGENCY</title>
    
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/project-details.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Загружаем Swiper только если есть изображения -->
    <?php if (count($images) > 0): ?>
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
    <?php endif; ?>
    
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Добавляем метатеги для SEO -->
    <meta name="description" content="<?php echo htmlspecialchars(substr($project['short_description'], 0, 160)); ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($project['title']); ?> - I4B AGENCY">
    <meta property="og:description" content="<?php echo htmlspecialchars(substr($project['short_description'], 0, 160)); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($project['main_image']); ?>">
</head>
<body>
    <?php include 'components/header.php'; ?>

    <section class="project-hero" style="background-image: url('<?php echo htmlspecialchars($project['main_image']); ?>')">
        <div class="project-hero-overlay"></div>
        <div class="left-border"></div>
        <div class="right-border"></div>
        <div class="hero-diagonal-lines">
            <div class="diagonal-line" style="--rotation: 30deg;"></div>
            <div class="diagonal-line" style="--rotation: -25deg;"></div>
            <div class="diagonal-line" style="--rotation: 20deg;"></div>
        </div>
        <div class="container">
            <h1 class="project-title"><?php echo htmlspecialchars($project['title']); ?></h1>
            <p class="project-hero-description"><?php echo htmlspecialchars($project['short_description']); ?></p>
            <div class="project-tags">
                <?php 
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
        </div>
    </section>

    <section class="project-content-section">
        <div class="container">
            <div class="project-description">
                <h2>О проекте</h2>
                <div class="service-divider"></div>
                <div class="project-full-description">
                    <?php echo nl2br(htmlspecialchars($project['full_description'])); ?>
                </div>
            </div>
            
            <?php if (count($images) > 0): ?>
            <div class="project-gallery">
                <h2 class="section-title">Галерея проекта</h2>
                
                <div class="project-slider swiper-container">
                    <div class="swiper-wrapper">
                        <?php foreach ($images as $image): ?>
                        <div class="swiper-slide">
                            <div class="image-container">
                                <img src="<?php echo htmlspecialchars($image['image_path']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>" loading="lazy">
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
            <?php else: ?>
            <div class="project-gallery">
                <h2 class="section-title">Галерея проекта</h2>
                <p class="no-images">Для этого проекта нет дополнительных изображений.</p>
            </div>
            <?php endif; ?>
            
            <div class="project-technologies">
                <h2>Использованные технологии</h2>
                <div class="service-divider"></div>
                <div class="tech-tags">
                    <?php 
                    foreach ($technologies as $tech): 
                        $tech = trim($tech);
                        if (!empty($tech)):
                    ?>
                        <div class="tech-tag">
                            <i class="fas fa-code"></i>
                            <span><?php echo htmlspecialchars($tech); ?></span>
                        </div>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>
            </div>

            <div class="back-to-projects">
                <a href="projects.php" class="back-btn"><i class="fas fa-arrow-left"></i> Вернуться к проектам</a>
            </div>
        </div>
    </section>

    <?php include 'components/footer.php'; ?>

    <!-- Загружаем скрипты в конце страницы -->
    <script src="js/script.js" defer></script>
    
    <!-- Загружаем Swiper только если есть изображения -->
    <?php if (count($images) > 0): ?>
    <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var swiper = new Swiper('.project-slider', {
                slidesPerView: 1,
                spaceBetween: 30,
                centeredSlides: true,
                grabCursor: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                autoplay: false,
                loop: true
            });
        });
    </script>
    <?php endif; ?>
    
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                once: true,
                disable: window.innerWidth < 768
            });
        });
    </script>
</body>
</html> 