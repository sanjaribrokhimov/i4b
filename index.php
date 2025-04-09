<?php
// Подключаем трекер посещений и счетчик
include_once 'phpServers/visitor_tracker.php';
include_once 'phpServers/stats_counter.php';

// Получаем текущее количество посещений
$visitorCount = getCounter();

// Получаем последние 4 проекта из базы данных
try {
    // Подключаемся к базе данных с абсолютным путем
    $dbPath = __DIR__ . '/phpServers/database/projects.db';
    
    // Проверяем существование файла базы данных
    if (file_exists($dbPath)) {
        $db = new SQLite3($dbPath);
        
        // Получаем последние 4 проекта, отсортированные по дате создания (новые сначала)
        $latestProjects = $db->query('SELECT * FROM projects ORDER BY created_at DESC LIMIT 4');
    }
} catch (Exception $e) {
    // В случае ошибки просто продолжаем без проектов
    $latestProjects = null;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заказать разработку сайта, Telegram бота и мобильного приложения в Узбекистане | I4B AGENCY</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Заказать разработку сайта, Telegram бота и мобильного приложения в Узбекистане. Профессиональная разработка веб-сайтов, Telegram ботов и мобильных приложений в Ташкенте. Создание сайтов от 1,000,000 сум. Telegram боты от 500,000 сум. Мобильные приложения от 2,000,000 сум.">
    <meta name="keywords" content="заказать сайт Узбекистан, разработка сайта Ташкент, заказать telegram бот, разработка telegram бота, заказать мобильное приложение, разработка мобильного приложения, создание сайта Узбекистан, разработка сайтов Ташкент, telegram боты Узбекистан, мобильные приложения Узбекистан">
    <meta name="author" content="I4B AGENCY">
    <meta name="robots" content="index, follow">
    <meta name="geo.region" content="UZ">
    <meta name="geo.placename" content="Tashkent">
    
    <!-- Open Graph Meta Tags for Social Media -->
    <meta property="og:title" content="Заказать разработку сайта, Telegram бота и мобильного приложения в Узбекистане">
    <meta property="og:description" content="Профессиональная разработка веб-сайтов, Telegram ботов и мобильных приложений в Ташкенте. Создание сайтов от 1,000,000 сум. Telegram боты от 500,000 сум. Мобильные приложения от 2,000,000 сум.">
    <meta property="og:image" content="img/I4Blogo.png">
    <meta property="og:url" content="https://i4b.uz">
    <meta property="og:type" content="website">
    
    <!-- Favicon -->
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="https://i4b.uz">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/mobile.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .team-member .member-photo {
            width: 100%;
            height: 250px;
            overflow: hidden;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        
        .team-member .member-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: top;
        }
    </style>
</head>
<body>
<?php include 'components/header.php'; ?>
    <section class="hero-section">
        <div class="hero-grid"></div>
        <div class="hero-diagonal-lines">
            <div class="diagonal-line" style="--rotation: 30deg;"></div>
            <div class="diagonal-line" style="--rotation: -25deg;"></div>
            <div class="diagonal-line" style="--rotation: 20deg;"></div>
            <div class="diagonal-line" style="--rotation: -15deg;"></div>
            <div class="diagonal-line" style="--rotation: 10deg;"></div>
        </div>
        <div class="hero-dots">
            <div class="hero-dot"></div>
            <div class="hero-dot"></div>
            <div class="hero-dot"></div>
            <div class="hero-dot"></div>
            <div class="hero-dot"></div>
            <div class="hero-dot"></div>
        </div>
        <div class="left-border"></div>
        <div class="right-border"></div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-logo">
                    <div class="corner-top-right"></div>
                    <div class="corner-bottom-left"></div>
                    <img src="img/I4Blogo.png" alt="I4B AGENCY">
                    <div class="hologram-container">
                        <h2 class="hologram-text">IT FOR BUSINESS</h2>
                        <div class="hologram-overlay"></div>
                        <div class="hologram-scan-line"></div>
                    </div>
                </div>
                <div class="hero-text">
                    <h1><span>ЗАКАЗАТЬ РАЗРАБОТКУ</span>САЙТА, TELEGRAM БОТА И МОБИЛЬНОГО ПРИЛОЖЕНИЯ</h1>
                    <p class="hero-description">Профессиональная разработка в Узбекистане: сайты от 1,000,000 сум, Telegram боты от 500,000 сум, мобильные приложения от 2,000,000 сум</p>
                    <div class="hero-features">
                        <div class="hero-feature">
                            <span class="feature-icon">✓</span>
                            <span>Сайты для бизнеса</span>
                        </div>
                        <div class="hero-feature">
                            <span class="feature-icon">✓</span>
                            <span>Telegram боты</span>
                        </div>
                        <div class="hero-feature">
                            <span class="feature-icon">✓</span>
                            <span>Мобильные приложения</span>
                        </div>
                    </div>
                    <button class="order-btn">Заказать разработку</button>
                </div>
            </div>
            <div class="stats-container">
                <div class="stats-items">
                    <div class="stat-item">
                        <h2>4<span>+</span></h2>
                        <p>лет на рынке Узбекистана</p>
                    </div>
                    <div class="stat-item">
                        <h2>13<span>+</span></h2>
                        <p>успешных проектов в Узбекистане</p>
                    </div>
                    <div class="stat-item">
                        <h2>15<span>+</span></h2>
                        <p>современных технологий</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="projects-section classic enhanced">
        <div class="services-bg">
            <div class="bg-shape shape-1"></div>
            <div class="bg-shape shape-2"></div>
            <div class="bg-shape shape-3"></div>
        </div>
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Наши проекты в Узбекистане</h2>
            <p class="section-description" data-aos="fade-up">Успешно реализованные IT-проекты для бизнеса в Узбекистане</p>
            <div class="projects-grid">
                <?php 
                $hasProjects = false;
                if (isset($latestProjects)) {
                    while ($project = $latestProjects->fetchArray(SQLITE3_ASSOC)): 
                        $hasProjects = true;
                ?>
                <div class="project-card" data-aos="zoom-in" data-aos-delay="100">
                    <div class="card-glow"></div>
                    <div class="project-image">
                        <img src="<?php echo htmlspecialchars($project['main_image']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>">
                        <div class="project-overlay">
                            <span>Подробнее</span>
                        </div>
                    </div>
                    <div class="project-content">
                        <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                        <div class="service-divider"></div>
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
                    </div>
                    <div class="service-hover-overlay"></div>
                </div>
                <?php 
                    endwhile;
                }
                
                // Если проектов нет или произошла ошибка, показываем статические карточки
                if (!$hasProjects): 
                ?>
                <div class="project-card" data-aos="zoom-in" data-aos-delay="100">
                    <div class="card-glow"></div>
                    <div class="project-image">
                        <img src="img/project1.jpg" alt="Проект 1">
                        <div class="project-overlay">
                            <span>Подробнее</span>
                        </div>
                    </div>
                    <div class="project-content">
                        <h3>Система управления транспортом</h3>
                        <div class="service-divider"></div>
                        <p>Разработка комплексной системы мониторинга и управления транспортными средствами для крупной логистической компании.</p>
                        <div class="project-tags">
                            <span>Web</span>
                            <span>Mobile</span>
                            <span>IoT</span>
                        </div>
                    </div>
                    <div class="service-hover-overlay"></div>
                </div>
                
                <div class="project-card" data-aos="zoom-in" data-aos-delay="200">
                    <div class="card-glow"></div>
                    <div class="project-image">
                        <img src="img/project2.jpg" alt="Проект 2">
                        <div class="project-overlay">
                            <span>Подробнее</span>
                        </div>
                    </div>
                    <div class="project-content">
                        <h3>AI-ассистент для медицинских учреждений</h3>
                        <div class="service-divider"></div>
                        <p>Разработка интеллектуальной системы поддержки принятия решений для врачей на основе машинного обучения.</p>
                        <div class="project-tags">
                            <span>AI</span>
                            <span>ML</span>
                            <span>Healthcare</span>
                        </div>
                    </div>
                    <div class="service-hover-overlay"></div>
                </div>
                
                <div class="project-card" data-aos="zoom-in" data-aos-delay="300">
                    <div class="card-glow"></div>
                    <div class="project-image">
                        <img src="img/project3.jpg" alt="Проект 3">
                        <div class="project-overlay">
                            <span>Подробнее</span>
                        </div>
                    </div>
                    <div class="project-content">
                        <h3>Платформа для умного города</h3>
                        <div class="service-divider"></div>
                        <p>Создание единой цифровой платформы для управления городской инфраструктурой с интеграцией IoT-устройств.</p>
                        <div class="project-tags">
                            <span>Smart City</span>
                            <span>IoT</span>
                            <span>Big Data</span>
                        </div>
                    </div>
                    <div class="service-hover-overlay"></div>
                </div>
                
                <div class="project-card" data-aos="zoom-in" data-aos-delay="400">
                    <div class="card-glow"></div>
                    <div class="project-image">
                        <img src="img/project4.jpg" alt="Проект 4">
                        <div class="project-overlay">
                            <span>Подробнее</span>
                        </div>
                    </div>
                    <div class="project-content">
                        <h3>Система компьютерного зрения для ритейла</h3>
                        <div class="service-divider"></div>
                        <p>Разработка системы распознавания товаров на полках и анализа поведения покупателей для сети супермаркетов.</p>
                        <div class="project-tags">
                            <span>Computer Vision</span>
                            <span>Retail</span>
                            <span>Analytics</span>
                        </div>
                    </div>
                    <div class="service-hover-overlay"></div>
                </div>
                <?php endif; ?>
            </div>
            <div class="view-all-projects">
                <a href="projects.php" class="view-all-btn" data-aos="fade-up">Посмотреть все проекты</a>
            </div>
        </div>
    </section>
 
    <section id="services-section" class="services-section classic enhanced">
        <div class="left-border"></div>
        <div class="right-border"></div>
        <div class="services-bg">
            <div class="bg-shape shape-1"></div>
            <div class="bg-shape shape-2"></div>
            <div class="bg-shape shape-3"></div>
        </div>
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Заказать разработку сайта, Telegram бота и мобильного приложения в Узбекистане</h2>
            <p class="section-description" data-aos="fade-up">Профессиональная разработка с гарантией качества и поддержкой</p>
            <div class="services-grid compact">
                <div class="service-card featured" data-aos="zoom-in" data-aos-delay="100">
                    <div class="card-glow"></div>
                    <div class="service-badge">Популярная услуга</div>
                    <div class="service-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#b4ff46" stroke-width="1.5">
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                            <line x1="3" y1="8" x2="21" y2="8" />
                            <line x1="7" y1="5.5" x2="7" y2="5.5" stroke-linecap="round" />
                            <line x1="10" y1="5.5" x2="10" y2="5.5" stroke-linecap="round" />
                        </svg>
                    </div>
                    <h3>Заказать разработку сайта в Узбекистане</h3>
                    <div class="service-divider"></div>
                    <p>Создаем современные, адаптивные и высокопроизводительные веб-сайты для вашего бизнеса в Узбекистане. От лендингов до сложных веб-приложений с учетом местного рынка.</p>
                    <div class="service-price">
                        <span>от 1,000,000 сум</span>
                    </div>
                    <div class="service-features">
                        <ul>
                            <li>Адаптивный дизайн</li>
                            <li>SEO-оптимизация</li>
                            <li>Интеграция с платежными системами</li>
                            <li>Техническая поддержка</li>
                        </ul>
                    </div>
                   
                    <div class="service-hover-overlay"></div>
                </div>
                
                <div class="service-card featured" data-aos="zoom-in" data-aos-delay="200">
                    <div class="card-glow"></div>
                    <div class="service-badge">Популярная услуга</div>
                    <div class="service-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#b4ff46" stroke-width="1.5">
                            <rect x="6" y="3" width="12" height="18" rx="2" />
                            <rect x="9" y="7" width="6" height="10" stroke="#b4ff46" />
                            <rect x="18" y="12" width="3" height="3" stroke="#b4ff46" stroke-width="1" />
                        </svg>
                    </div>
                    <h3>Заказать разработку мобильного приложения в Узбекистане</h3>
                    <div class="service-divider"></div>
                    <p>Кроссплатформенная и нативная разработка под Android и iOS для узбекского рынка. Создаем мобильные приложения любой сложности с учетом местных особенностей.</p>
                    <div class="service-price">
                        <span>от 2,000,000 сум</span>
                    </div>
                    <div class="service-features">
                        <ul>
                            <li>iOS и Android разработка</li>
                            <li>Push-уведомления</li>
                            <li>Интеграция с API</li>
                            <li>Техническая поддержка</li>
                        </ul>
                    </div>
                   
                    <div class="service-hover-overlay"></div>
                </div>
                
                <div class="service-card" data-aos="zoom-in" data-aos-delay="300">
                    <div class="card-glow"></div>
                    <div class="service-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#b4ff46" stroke-width="1.5">
                            <path d="M12 2L2 7L12 12L22 7L12 2Z" />
                            <path d="M2 17L12 22L22 17" />
                            <path d="M2 12L12 17L22 12" />
                        </svg>
                    </div>
                    <h3>Автоматизация бизнеса в Узбекистане</h3>
                    <div class="service-divider"></div>
                    <p>Разрабатываем системы автоматизации бизнес-процессов, CRM, ERP и другие решения для повышения эффективности вашего бизнеса в Узбекистане с учетом местной специфики.</p>
                    <div class="service-price">
                        <span>от 3,000,000 сум</span>
                    </div>
                    <div class="service-features">
                        <ul>
                            <li>CRM системы</li>
                            <li>ERP решения</li>
                            <li>Автоматизация процессов</li>
                            <li>Техническая поддержка</li>
                        </ul>
                    </div>
                    <div class="service-hover-overlay"></div>
                </div>
                
                <div class="service-card featured" data-aos="zoom-in" data-aos-delay="400">
                    <div class="card-glow"></div>
                    <div class="service-badge">Популярная услуга</div>
                    <div class="service-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#b4ff46" stroke-width="1.5">
                            <path d="M8 12L11 15L16 9" />
                        </svg>
                    </div>
                    <h3>Заказать разработку Telegram бота в Узбекистане</h3>
                    <div class="service-divider"></div>
                    <p>Разрабатываем функциональных ботов для Telegram, которые автоматизируют коммуникацию с клиентами и оптимизируют бизнес-процессы с учетом особенностей узбекского рынка.</p>
                    <div class="service-price">
                        <span>от 500,000 сум</span>
                    </div>
                    <div class="service-features">
                        <ul>
                            <li>Автоматизация ответов</li>
                            <li>Интеграция с CRM</li>
                            <li>Обработка платежей</li>
                            <li>Техническая поддержка</li>
                        </ul>
                    </div>
                    
                    <div class="service-hover-overlay"></div>
                </div>
                
                <div class="service-card" data-aos="zoom-in" data-aos-delay="500">
                    <div class="card-glow"></div>
                    <div class="service-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#b4ff46" stroke-width="1.5">
                            <path d="M12 2L2 7L12 12L22 7L12 2Z" />
                            <path d="M2 17L12 22L22 17" />
                            <path d="M2 12L12 17L22 12" />
                        </svg>
                    </div>
                    <h3>AI-разработка в Узбекистане</h3>
                    <div class="service-divider"></div>
                    <p>Создаем решения на базе искусственного интеллекта и машинного обучения для автоматизации и оптимизации бизнес-процессов в Узбекистане с учетом местных данных и требований.</p>
                    <div class="service-hover-overlay"></div>
                </div>
                
                <div class="service-card" data-aos="zoom-in" data-aos-delay="600">
                    <div class="card-glow"></div>
                    <div class="service-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#b4ff46" stroke-width="1.5">
                            <path d="M3 9L12 2L21 9V20C21 20.5304 20.7893 21.0391 20.4142 21.4142C20.0391 21.7893 19.5304 22 19 22H5C4.46957 22 3.96086 21.7893 3.58579 21.4142C3.21071 21.0391 3 20.5304 3 20V9Z" />
                            <path d="M9 22V12H15V22" />
                        </svg>
                    </div>
                    <h3>SEO оптимизация и SMM продвижение в Узбекистане</h3>
                    <div class="service-divider"></div>
                    <p>Повышаем видимость вашего сайта в поисковых системах Узбекистана с помощью комплексной SEO-оптимизации. <br>
                    Развиваем ваш бренд в социальных сетях Узбекистана, создаем и управляем контентом, привлекаем новых клиентов с учетом местной аудитории.</p>
                    <div class="service-hover-overlay"></div>
                </div>
                
                <div class="service-card" data-aos="zoom-in" data-aos-delay="700">
                    <div class="card-glow"></div>
                    <div class="service-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#b4ff46" stroke-width="1.5">
                            <path d="M21 16V8C21 7.46957 20.7893 6.96086 20.4142 6.58579C20.0391 6.21071 19.5304 6 19 6H5C4.46957 6 3.96086 6.21071 3.58579 6.58579C3.21071 6.96086 3 7.46957 3 8V16C3 16.5304 3.21071 17.0391 3.58579 17.4142C3.96086 17.7893 4.46957 18 5 18H19C19.5304 18 20.0391 17.7893 20.4142 17.4142C20.7893 17.0391 21 16.5304 21 16Z" />
                            <path d="M7 10L12 14L17 10" />
                        </svg>
                    </div>
                    <h3>Анализ рынка и бизнес-идей</h3>
                    <div class="service-divider"></div>
                    <p>Проводим комплексный анализ рынка, конкурентов и целевой аудитории. Помогаем оценить и развить бизнес-идеи для успешного запуска.</p>
                    <div class="service-hover-overlay"></div>
                </div>
                
                <div class="service-card" data-aos="zoom-in" data-aos-delay="800">
                    <div class="card-glow"></div>
                    <div class="service-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#b4ff46" stroke-width="1.5">
                            <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z" />
                        </svg>
                    </div>
                    <h3>Телефония и телекоммуникации</h3>
                    <div class="service-divider"></div>
                    <p>Проектирование и внедрение систем телефонной связи, IP-телефонии, видеоконференцсвязи и других телекоммуникационных решений для бизнеса.</p>
                    <div class="service-hover-overlay"></div>
                </div>
            </div>
            
            <!-- Добавляем секцию с технологиями разработки -->
            <div class="technologies-section">
                <div class="container-fluid">
                    <h2 class="tech-title">ТЕХНОЛОГИИ РАЗРАБОТКИ</h2>
                    <div class="tech-logos">
                        <div class="tech-logo">
                            <img src="img/Vue.js.png" alt="Vue.js">
                            <span>Vue.js</span>
                        </div>
                        <div class="tech-logo">
                            <img src="img/Laravel.png" alt="Laravel">
                            <span>Laravel</span>
                        </div>
                        <div class="tech-logo">
                            <img src="img/Flutter.png" alt="Flutter">
                            <span>Flutter</span>
                        </div>
                        <div class="tech-logo">
                            <img src="img/React.png" alt="React">
                            <span>React</span>
                        </div>
                        <div class="tech-logo">
                            <img src="img/Django.png" alt="Django">
                            <span>Django</span>
                        </div>
                        <div class="tech-logo">
                            <img src="img/Go.png" alt="Golang">
                            <span>Golang</span>
                        </div>
                        <div class="tech-logo">
                            <img src="img/Java.png" alt="Java">
                            <span>Java</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="telecom-section classic enhanced">
        <div class="left-border"></div>
        <div class="right-border"></div>
        <div class="services-bg">
            <div class="bg-shape shape-1"></div>
            <div class="bg-shape shape-2"></div>
            <div class="bg-shape shape-3"></div>
        </div>
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Телекоммуникации и сети передачи данных</h2>
            <div class="telecom-description" data-aos="fade-up" data-aos-delay="100">
                <p>Мы выполняем работы по проектированию, монтажу, пуско-наладке и сервисному обслуживанию местных сетей телекоммуникаций.</p>
            </div>
            
            <div class="telecom-services">
                <div class="telecom-service-card" data-aos="zoom-in" data-aos-delay="200">
                    <div class="card-glow"></div>
                    <div class="telecom-service-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#b4ff46" stroke-width="1.5">
                            <path d="M2 9C2 8.44772 2.44772 8 3 8H21C21.5523 8 22 8.44772 22 9V20C22 20.5523 21.5523 21 21 21H3C2.44772 21 2 20.5523 2 20V9Z" />
                            <path d="M7 8V5C7 3.89543 7.89543 3 9 3H15C16.1046 3 17 3.89543 17 5V8" />
                            <circle cx="12" cy="15" r="3" />
                        </svg>
                    </div>
                    <h3>Проектирование и строительство местных сетей телекоммуникаций</h3>
                    <div class="service-divider"></div>
                    <ul class="telecom-service-list">
                        <li>Структурированные кабельные системы (СКС)</li>
                        <li>Волоконно-оптические лини связи (ВОЛС)</li>
                        <li>Локально-вычислительные сети (ЛВС)</li>
                    </ul>
                    <div class="service-hover-overlay"></div>
                </div>
                
                <div class="telecom-service-card" data-aos="zoom-in" data-aos-delay="300">
                    <div class="card-glow"></div>
                    <div class="telecom-service-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#b4ff46" stroke-width="1.5">
                            <path d="M5 12H19" />
                            <path d="M12 5L19 12L12 19" />
                        </svg>
                    </div>
                    <h3>Настройка и обслуживание инфраструктуры сети</h3>
                    <div class="service-divider"></div>
                    <p>Комплексное обслуживание и поддержка сетевой инфраструктуры любой сложности</p>
                    <div class="service-hover-overlay"></div>
                </div>
                
                <div class="telecom-service-card" data-aos="zoom-in" data-aos-delay="400">
                    <div class="card-glow"></div>
                    <div class="telecom-service-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#b4ff46" stroke-width="1.5">
                            <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z" />
                        </svg>
                    </div>
                    <h3>Системы телефонной связи</h3>
                    <div class="service-divider"></div>
                    <ul class="telecom-service-list">
                        <li>ATC, VOIP телефония</li>
                        <li>Системы видеоконференции</li>
                        <li>Системы аудиоконференции</li>
                    </ul>
                    <div class="service-hover-overlay"></div>
                </div>
                
                <div class="telecom-service-card" data-aos="zoom-in" data-aos-delay="500">
                    <div class="card-glow"></div>
                    <div class="telecom-service-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#b4ff46" stroke-width="1.5">
                            <path d="M12 5.69l5 4.5V18h-2v-6H9v6H7v-7.81l5-4.5M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3z" />
                            <path d="M18.5 13l2 2m0 0l2 2m-2-2l-2 2m2-2l2-2" />
                        </svg>
                    </div>
                    <h3>Беспроводные сети WI-FI</h3>
                    <div class="service-divider"></div>
                    <p>Проектирование и развертывание высокоскоростных беспроводных сетей для бизнеса и промышленных объектов</p>
                    <div class="service-hover-overlay"></div>
                </div>
                
                <div class="telecom-service-card" data-aos="zoom-in" data-aos-delay="600">
                    <div class="card-glow"></div>
                    <div class="telecom-service-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#b4ff46" stroke-width="1.5">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                            <path d="M8 11V7l6 5-6 5v-4H4v-2h4z"/>
                            <path d="M20 11h-4"/>
                        </svg>
                    </div>
                    <h3>Системы контроля доступа (СКУД)</h3>
                    <div class="service-divider"></div>
                    <p>Система контроля и управления доступом (СКУД) для обеспечения безопасности объектов любой сложности</p>
                    <div class="service-hover-overlay"></div>
                </div>
                
                <div class="telecom-service-card" data-aos="zoom-in" data-aos-delay="700">
                    <div class="card-glow"></div>
                    <div class="telecom-service-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#b4ff46" stroke-width="1.5">
                            <circle cx="12" cy="12" r="10"/>
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M12 5V9"/>
                            <path d="M12 15V19"/>
                            <path d="M5 12H9"/>
                            <path d="M15 12H19"/>
                        </svg>
                    </div>
                    <h3>Системы IP видеонаблюдения</h3>
                    <div class="service-divider"></div>
                    <p>Современные системы IP видеонаблюдения с возможностью удаленного мониторинга и аналитики</p>
                    <div class="service-hover-overlay"></div>
                </div>
                
                <div class="telecom-service-card" data-aos="zoom-in" data-aos-delay="800">
                    <div class="card-glow"></div>
                    <div class="telecom-service-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#b4ff46" stroke-width="1.5">
                            <path d="M12 2v6m0 0l3-3m-3 3L9 5"/>
                            <path d="M19 10v4a7 7 0 01-14 0v-4"/>
                            <path d="M12 18v4"/>
                        </svg>
                    </div>
                    <h3>Промышленная громко-говорящая связь (ПГС)</h3>
                    <div class="service-divider"></div>
                    <p>Промышленная громко-говорящая связь для обеспечения оперативной коммуникации на производственных объектах</p>
                    <div class="service-hover-overlay"></div>
                </div>
            </div>
            
            
        </div>
    </section>

    <section class="team-section">
        <div class="left-border"></div>
        <div class="right-border"></div>
        <div class="particles">
            <span class="particle"></span>
            <span class="particle"></span>
            <span class="particle"></span>
            <span class="particle"></span>
            <span class="particle"></span>
        </div>
        <div class="container">
            <div class="team-header">
                <div class="team-title">
                    <h2>Доверьтесь нам</h2>
                </div>
                <div class="team-description">
                    <p>Свяжитесь с нами! Мы поможем вам достичь результата и никто не пострадает :)</p>
                </div>
            </div>
            
            <div class="team-members">
                <div class="team-member">
                    <div class="member-photo">
                        <img src="img/sanjar.jpg" alt="Санжар Иброхимов">
                    </div>
                    <h3>Санжар Иброхимов</h3>
                    <p class="member-position">Основатель , Full-Stack разработчик</p>
                    <div class="member-divider"></div>
                    
                </div>
                
                <div class="team-member">
                    <div class="member-photo">
                        <img src="img/muhammadjon.JPG" alt="Бобоев Мухаммаджон">
                    </div>
                    <h3>Бобоев Мухаммаджон</h3>
                    <p class="member-position">Менеджер по продажам</p>
                    <div class="member-divider"></div>
                    
                </div>
                
                <div class="team-member">
                    <div class="member-photo">
                        <img src="img/dior.jpg" alt="Мухаммаддиер">
                    </div>
                    <h3>Мухаммаддиер</h3>
                    <p class="member-position">Разработчик</p>
                    <div class="member-divider"></div>
                </div>
                
                <div class="team-member">
                    <div class="member-photo">
                        <img src="img/sardor.jpg" alt="Каюмов Сардор">
                    </div>
                    <h3>Каюмов Сардор</h3>
                    <p class="member-position">Специалист по телекоммуникациям и сетям</p>
                    <div class="member-divider"></div>
                    
                </div>
                
                <div class="team-member">
                    <div class="member-photo">
                        <img src="img/sobit.jpg" alt="Станислав Карнахин">
                    </div>
                    <h3>Ибохимов Собит</h3>
                    <p class="member-position">Гафик дизайнер</p>
                    <div class="member-divider"></div>
                    
                </div>
                
                <div class="team-member">
                    <div class="member-photo">
                        <img src="img/sulton.jpg" alt="Анастасия Мадеева">
                    </div>
                    <h3>Рустамжонов Султан</h3>
                    <p class="member-position">Разработчик</p>
                    <div class="member-divider"></div>
                    
                </div>
            </div>
            
            
        </div>
    </section>
    
    <section class="news-section">
        <div class="left-border"></div>
        <div class="right-border"></div>
        <div class="container">
            <div class="news-header">
                <div class="news-title">
                    <h2>Новости</h2>
                </div>
                <div class="news-controls">
                    <button class="news-arrow prev-arrow">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M19 12H5" stroke="#333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 19L5 12L12 5" stroke="#333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div class="news-pagination">
                        <span class="dot active"></span>
                        <span class="dot"></span>
                        <span class="dot"></span>
                        <span class="dot"></span>
                        <span class="dot"></span>
                    </div>
                    <button class="news-arrow next-arrow">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12H19" stroke="#333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 5L19 12L12 19" stroke="#333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="news-grid">
                <div class="news-card">
                    <div class="news-image">
                        <img src="img/news/news1.jpg" alt="Тенденции развития ИИ в недвижимости">
                        <div class="news-tag">
                            <span>Тенденции развития</span>
                            <span>ИИ в недвижимости</span>
                        </div>
                    </div>
                    <div class="news-content">
                        <h3>Комментарий для журнала Компьютерра</h3>
                        <p>Недвижимость на автопилоте: роль ИИ в трансформации отрасли</p>
                        <div class="news-date">12.03.2023</div>
                    </div>
                </div>
                
                <div class="news-card">
                    <div class="news-image">
                        <img src="img/news/news2.jpg" alt="CodeInside в финале премии Цифровые вершины">
                        <div class="news-tag">
                            <span>CodeInside в финале премии</span>
                            <span>Цифровые вершины</span>
                        </div>
                    </div>
                    <div class="news-content">
                        <h3>Покоряем «Цифровые вершины»!</h3>
                        <p>Да, мы оказались в числе финалистов престижной премии «Цифровые вершины», отмечающей лучшие отечественные ИТ-решения для бизнеса и государственного управления на территории РФ и ЕАЭС.</p>
                        <div class="news-date">11.03.2023</div>
                    </div>
                </div>
                
                <div class="news-card">
                    <div class="news-image">
                        <img src="img/news/news3.jpg" alt="Тренды ИИ-разработки в 2025">
                        <div class="news-tag">
                            <span>Тренды ИИ-разработки</span>
                            <span>в 2025</span>
                        </div>
                    </div>
                    <div class="news-content">
                        <h3>Тренды ИИ-разработки в 2025 году: куда движется индустрия?</h3>
                        <p>Согласно исследованию ICT.Moscow, 46% российских разработчиков называют среди приоритетных задач интеграцию новых модальностей, развитие ИИ-агентов и использование Retrieval-Augmented Generation (RAG) для повышения точности генерации.</p>
                        <div class="news-date">11.03.2023</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="contact-section">
        <div class="particles">
            <span class="particle"></span>
            <span class="particle"></span>
            <span class="particle"></span>
            <span class="particle"></span>
            <span class="particle"></span>
        </div>
        <div class="container">
            <div class="contact-wrapper">
                <div class="contact-text">
                    <h2>ЕСТЬ ВОПРОС?</h2>
                    <h3>Напишите нам</h3>
                    <p>Заполните форму, и мы свяжемся с вами в ближайшее время</p>
                </div>
                <div class="contact-form-container">
                    <form id="contactForm" class="contact-form">
                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" id="name" name="name" placeholder="Имя" required>
                            </div>
                            <div class="form-group">
                                <input type="text" id="company" name="company" placeholder="Компания">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" id="telegram" name="telegram" placeholder="Telegram username" required>
                            </div>
                            <div class="form-group">
                                <input type="tel" id="phone" name="phone" placeholder="Телефон">
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea id="message" name="message" placeholder="Ваш вопрос, предложение" rows="5" required></textarea>
                        </div>
                        <div class="form-group submit-group">
                            <button type="submit" class="submit-btn">Отправить</button>
                        </div>
                        <div class="form-status" id="formStatus"></div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
<?php include 'components/footer.php'; ?>

    <div class="modal-overlay" id="contactModal">
        <div class="modal-container">
            <button class="modal-close"><i class="fas fa-times"></i></button>
            <div class="modal-header">
                <h2>Связаться с нами</h2>
                <p>Оставьте свои контактные данные, и мы свяжемся с вами в ближайшее время</p>
            </div>
            <form id="modalForm" class="modal-form">
                <div class="form-row">
                    <div class="form-group">
                        <input type="text" id="modal-name" name="name" placeholder="Ваше имя *" required>
                    </div>
                    <div class="form-group">
                        <input type="text" id="modal-company" name="company" placeholder="Компания">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <input type="text" id="modal-telegram" name="telegram" placeholder="Telegram">
                    </div>
                    <div class="form-group">
                        <input type="tel" id="modal-phone" name="phone" placeholder="Телефон *" required>
                    </div>
                </div>
                <div class="form-group">
                    <textarea id="modal-message" name="message" rows="4" placeholder="Ваше сообщение"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="submit-btn">Отправить</button>
                </div>
                <div class="form-status" id="modalFormStatus"></div>
            </form>
        </div>
    </div>
    <!-- Добавляем модальное окно для сообщения об успешной отправке -->
    <div class="modal-overlay" id="successModal">
        <div class="modal-container success-modal">
            <button class="modal-close" onclick="document.getElementById('successModal').classList.remove('active'); document.body.style.overflow = '';"><i class="fas fa-times"></i></button>
            <div class="modal-header">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2>Сообщение отправлено!</h2>
                <p>Ваше сообщение успешно отправлено. Наши менеджеры свяжутся с вами в ближайшее время.</p>
            </div>
            <div class="modal-footer">
                <button class="submit-btn" onclick="document.getElementById('successModal').classList.remove('active'); document.body.style.overflow = '';">Закрыть</button>
            </div>
        </div>
    </div>
    
    <style>
        /* Стили для индикаторов статуса формы */
        .form-status {
            margin-top: 15px;
            font-size: 16px;
            text-align: center;
            min-height: 60px; /* Фиксированная высота для предотвращения скачков */
        }
        
        .success-message {
            color: #4CAF50;
            background-color: rgba(76, 175, 80, 0.1);
            border: 1px solid #4CAF50;
            padding: 15px;
            border-radius: 4px;
            font-weight: 500;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .error-message {
            color: #F44336;
            background-color: rgba(244, 67, 54, 0.1);
            border: 1px solid #F44336;
            padding: 15px;
            border-radius: 4px;
            font-weight: 500;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .loading-message {
            color: #2196F3;
            background-color: rgba(33, 150, 243, 0.1);
            border: 1px solid #2196F3;
            padding: 15px;
            border-radius: 4px;
            font-weight: 500;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .success-message i, .error-message i, .loading-message i {
            margin-right: 8px;
            font-size: 18px;
        }
        
        /* Стили для модального окна успешной отправки */
        .success-modal {
            max-width: 500px;
            text-align: center;
            padding: 30px;
        }
        
        .success-icon {
            font-size: 60px;
            color: #4CAF50;
            margin-bottom: 20px;
        }
        
        .success-modal h2 {
            color: #4CAF50;
            margin-bottom: 15px;
        }
        
        .success-modal p {
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 25px;
        }
        
        .modal-footer {
            margin-top: 20px;
        }
        
        /* Показываем модальное окно */
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
    </style>
    
    <script src="js/script.js"></script>
    <script>
        // Функция для показа модального окна успешной отправки
        function showSuccessModal() {
            document.getElementById('successModal').classList.add('active');
            document.body.style.overflow = 'hidden'; // Блокируем прокрутку страницы
            
            // Автоматически закрываем через 8 секунд
            setTimeout(() => {
                document.getElementById('successModal').classList.remove('active');
                document.body.style.overflow = ''; // Возвращаем прокрутку страницы
            }, 8000);
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            // Добавляем обработчик для кнопки "Заказать разработку"
            const orderBtn = document.querySelector('.order-btn');
            if (orderBtn) {
                orderBtn.addEventListener('click', function() {
                    // Открываем модальное окно контактов
                    document.getElementById('contactModal').classList.add('active');
                    document.body.style.overflow = 'hidden'; // Блокируем прокрутку страницы
                });
            }
            
            // Удаляем все существующие обработчики событий с формы
            const contactForm = document.getElementById('contactForm');
            if (contactForm) {
                // Клонируем форму и заменяем оригинал, чтобы удалить все обработчики событий
                const newForm = contactForm.cloneNode(true);
                contactForm.parentNode.replaceChild(newForm, contactForm);
                
                // Добавляем новый обработчик событий
                newForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Показываем индикатор загрузки
                    const formStatus = document.getElementById('formStatus');
                    formStatus.innerHTML = '<div class="loading-message"><i class="fas fa-spinner fa-spin"></i> Отправка сообщения...</div>';
                    
                    // Собираем данные формы
                    const data = {
                        name: document.getElementById('name').value,
                        company: document.getElementById('company').value,
                        telegram: document.getElementById('telegram').value,
                        phone: document.getElementById('phone').value,
                        message: document.getElementById('message').value
                    };
                    
                    console.log('Отправляемые данные:', data);
                    
                    // Отправляем данные в формате JSON
                    fetch('phpServers/send_telegram.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Ошибка сети');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.ok) {
                            formStatus.innerHTML = '<div class="success-message"><i class="fas fa-check-circle"></i> Ваше сообщение успешно отправлено!</div>';
                            newForm.reset();
                            
                            // Показываем модальное окно успешной отправки
                            showSuccessModal();
                            
                            // Скрываем сообщение в форме через 3 секунды
                            setTimeout(() => {
                                formStatus.innerHTML = '';
                            }, 3000);
                        } else {
                            formStatus.innerHTML = '<div class="error-message"><i class="fas fa-exclamation-circle"></i> Ошибка при отправке сообщения: ' + (data.description || 'Неизвестная ошибка') + '</div>';
                            console.error('Server error:', data);
                        }
                    })
                    .catch(error => {
                        formStatus.innerHTML = '<div class="error-message"><i class="fas fa-exclamation-triangle"></i> Произошла ошибка. Пожалуйста, попробуйте еще раз.</div>';
                        console.error('Error:', error);
                    });
                });
            }
            
            // Аналогично для модальной формы
            const modalForm = document.getElementById('modalForm');
            if (modalForm) {
                // Клонируем форму и заменяем оригинал
                const newModalForm = modalForm.cloneNode(true);
                modalForm.parentNode.replaceChild(newModalForm, modalForm);
                
                // Добавляем новый обработчик событий
                newModalForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Показываем индикатор загрузки
                    const modalFormStatus = document.getElementById('modalFormStatus');
                    modalFormStatus.innerHTML = '<div class="loading-message"><i class="fas fa-spinner fa-spin"></i> Отправка сообщения...</div>';
                    
                    // Собираем данные формы
                    const data = {
                        name: document.getElementById('modal-name').value,
                        company: document.getElementById('modal-company').value,
                        telegram: document.getElementById('modal-telegram').value,
                        phone: document.getElementById('modal-phone').value,
                        message: document.getElementById('modal-message').value
                    };
                    
                    // Отправляем данные
                    fetch('phpServers/send_telegram.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.ok) {
                            modalFormStatus.innerHTML = '<div class="success-message"><i class="fas fa-check-circle"></i> Сообщение отправлено!</div>';
                            newModalForm.reset();
                            
                            // Закрываем модальное окно
                            document.getElementById('contactModal').classList.remove('active');
                            
                            // Показываем модальное окно успешной отправки
                            showSuccessModal();
                        } else {
                            modalFormStatus.innerHTML = '<div class="error-message"><i class="fas fa-exclamation-circle"></i> Ошибка: ' + (data.description || 'Неизвестная ошибка') + '</div>';
                        }
                    })
                    .catch(error => {
                        modalFormStatus.innerHTML = '<div class="error-message"><i class="fas fa-exclamation-triangle"></i> Ошибка соединения</div>';
                        console.error('Error:', error);
                    });
                });
            }
        });
    </script>
    
    <!-- Можно добавить счетчик посещений в футер -->
    <div class="visitor-counter">
        Посещений сайта: <?php echo $visitorCount; ?>
    </div>
</body>
</html>

<?php
// Закрываем соединение с базой данных
if (isset($db)) {
    $db->close();
}
?>


