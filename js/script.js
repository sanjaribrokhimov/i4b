// Улучшенная анимация и интерактивность
document.addEventListener('DOMContentLoaded', function() {
    // Мобильное меню
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const mobileMenu = document.querySelector('.mobile-menu');
    const mobileMenuClose = document.querySelector('.mobile-menu-close');
    
    // Создаем мобильное меню, если его нет в HTML
    if (!mobileMenu) {
        const menuHTML = `
            <div class="mobile-menu">
                <button class="mobile-menu-close"><i class="fas fa-times"></i></button>
                <ul>
                    <li><a href="index.php">Главная</a></li>
                    <li><a href="#">О компании</a></li>
                    <li><a href="#services-section">Услуги</a></li>
                    <li><a href="projects.php">Проекты</a></li>
                    <li><a href="#">Блог</a></li>
                    <li><a href="contacts.php">Контакты</a></li>
                </ul>
                <div class="mobile-contacts">
                    <a href="tel:+998331108810" class="navbar-phone" style="color: #8BCC33;"><i class="fas fa-phone" style="color: #8BCC33;"></i> +998 33 110 88 10</a>
                </div>
                <button class="mobile-contact-btn">Написать нам</button>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', menuHTML);
    }
    
    const menu = document.querySelector('.mobile-menu');
    const closeBtn = document.querySelector('.mobile-menu-close');
    
    mobileMenuBtn.addEventListener('click', function() {
        menu.classList.add('active');
        document.body.style.overflow = 'hidden';
    });
    
    closeBtn.addEventListener('click', function() {
        menu.classList.remove('active');
        document.body.style.overflow = '';
    });
    
    // Закрытие меню при клике вне его
    document.addEventListener('click', function(e) {
        if (!menu.contains(e.target) && !mobileMenuBtn.contains(e.target) && menu.classList.contains('active')) {
            menu.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
    
    // Добавляем обработчик для мобильной кнопки "Написать нам"
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('mobile-contact-btn') || e.target.closest('.mobile-contact-btn')) {
            const contactModal = document.getElementById('contactModal');
            if (contactModal) {
                contactModal.classList.add('active');
                document.body.style.overflow = 'hidden';
                
                // Закрываем мобильное меню
                const mobileMenu = document.querySelector('.mobile-menu');
                if (mobileMenu) {
                    mobileMenu.classList.remove('active');
                }
            }
        }
    });
    
    // Голографическая анимация для слогана
    const slogan = document.querySelector('.slogan');
    if (slogan) {
        const originalText = slogan.textContent;
        slogan.setAttribute('data-text', originalText);
        
        // Создаем контейнер для слогана
        const sloganParent = slogan.parentNode;
        const sloganContainer = document.createElement('div');
        sloganContainer.className = 'slogan-container';
        sloganParent.replaceChild(sloganContainer, slogan);
        sloganContainer.appendChild(slogan);
        
        // Добавляем эффект сканирующих линий
        const scanLines = document.createElement('div');
        scanLines.className = 'slogan-lines';
        sloganContainer.appendChild(scanLines);
        
        // Добавляем эффект шума
        const noiseEffect = document.createElement('div');
        noiseEffect.className = 'slogan-noise';
        sloganContainer.appendChild(noiseEffect);
        
        // Создаем элемент для глитч-эффекта
        const glitchElement = document.createElement('div');
        glitchElement.className = 'slogan-glitch';
        slogan.appendChild(glitchElement);
        
        // Функция для создания эффекта глитча
        function createGlitch() {
            // Сохраняем оригинальный текст
            const originalContent = slogan.textContent;
            
            // Создаем серию глитчей
            let glitchCount = 0;
            const maxGlitches = 10;
            const glitchInterval = setInterval(() => {
                if (glitchCount >= maxGlitches) {
                    clearInterval(glitchInterval);
                    slogan.style.transform = '';
                    return;
                }
                
                // Случайное смещение
                const offsetX = (Math.random() - 0.5) * 5;
                const offsetY = (Math.random() - 0.5) * 5;
                slogan.style.transform = `translate(${offsetX}px, ${offsetY}px)`;
                
                // Случайный текст
                if (Math.random() > 0.7) {
                    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@#$%^&*()_+{}|:<>?';
                    let randomText = '';
                    for (let i = 0; i < originalContent.length; i++) {
                        randomText += chars.charAt(Math.floor(Math.random() * chars.length));
                    }
                    slogan.textContent = randomText;
                }
                
                glitchCount++;
            }, 100);
            
            // Восстанавливаем оригинальный текст после всех глитчей
            setTimeout(() => {
                slogan.textContent = originalContent;
                slogan.style.transform = '';
            }, maxGlitches * 100 + 200);
        }
        
        // Запускаем глитч каждые 10 секунд
        setInterval(createGlitch, 10000);
        
        // Запускаем глитч сразу при загрузке
        setTimeout(createGlitch, 2000);
    }
    
    // Улучшенная анимация чисел
    const statNumbers = document.querySelectorAll('.stat-item h2');
    
    function animateNumbers() {
        statNumbers.forEach(number => {
            const targetNumber = parseInt(number.textContent);
            let currentNumber = 0;
            const increment = Math.ceil(targetNumber / 50);
            const timer = setInterval(() => {
                currentNumber += increment;
                if (currentNumber >= targetNumber) {
                    clearInterval(timer);
                    number.innerHTML = targetNumber + '<span>+</span>';
                } else {
                    number.textContent = currentNumber;
                }
            }, 30);
        });
    }
    
    // Наблюдатель за появлением секции в поле зрения
    const statsContainer = document.querySelector('.stats-container');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateNumbers();
                observer.unobserve(entry.target);
            }
        });
    }, {threshold: 0.3});
    
    if (statsContainer) {
        observer.observe(statsContainer);
    }
    
    // Улучшенный параллакс-эффект
    document.addEventListener('mousemove', function(e) {
        const heroSection = document.querySelector('.hero-section');
        if (!heroSection) return;
        
        const moveX = (e.clientX - window.innerWidth / 2) * 0.01;
        const moveY = (e.clientY - window.innerHeight / 2) * 0.01;
        
        const bgElements = document.querySelectorAll('.bg-element');
        bgElements.forEach((element, index) => {
            const depth = (index + 1) * 0.5;
            element.style.transform = `translate(${moveX * depth}px, ${moveY * depth}px)`;
        });
        
        // Анимация для логотипа и текста
        const heroLogo = document.querySelector('.hero-logo');
        const heroText = document.querySelector('.hero-text');
        
        if (heroLogo && heroText) {
            heroLogo.style.transform = `translateX(${moveX * -0.3}px) translateY(${moveY * -0.3}px)`;
            heroText.style.transform = `translateX(${moveX * 0.3}px) translateY(${moveY * 0.3}px)`;
        }
    });
    
    // Плавная прокрутка для кнопок
    const buttons = document.querySelectorAll('button');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            // Добавляем эффект нажатия
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 200);
        });
    });
    
    // Анимация при скролле
    function revealOnScroll() {
        const elements = document.querySelectorAll('.stat-item');
        elements.forEach((element, index) => {
            setTimeout(() => {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }, 100 * index);
        });
    }
    
    // Инициализация анимаций
    revealOnScroll();
    
    // Проверка размера экрана и адаптация
    function checkScreenSize() {
        const width = window.innerWidth;
        const heroSection = document.querySelector('.hero-section');
        
        if (width <= 576) {
            // Мобильные устройства
            heroSection.style.minHeight = '80vh';
        } else if (width <= 768) {
            // Планшеты
            heroSection.style.minHeight = '85vh';
        } else {
            // Десктопы
            heroSection.style.minHeight = '90vh';
        }
    }
    
    // Вызываем функцию при загрузке и изменении размера окна
    checkScreenSize();
    window.addEventListener('resize', checkScreenSize);
    
    // Анимация для кнопки скачивания
    const downloadCircle = document.querySelector('.download-circle');
    if (downloadCircle) {
        downloadCircle.addEventListener('mouseenter', function() {
            const svg = this.querySelector('svg path');
            svg.style.stroke = '#d2ff8a';
            svg.style.strokeWidth = '3';
        });
        
        downloadCircle.addEventListener('mouseleave', function() {
            const svg = this.querySelector('svg path');
            svg.style.stroke = '#b4ff46';
            svg.style.strokeWidth = '2';
        });
    }
    
    // Добавляем библиотеку AOS для анимаций при скролле
    const aosScript = document.createElement('script');
    aosScript.src = 'https://unpkg.com/aos@2.3.1/dist/aos.js';
    aosScript.async = true;
    document.head.appendChild(aosScript);
    
    const aosStyles = document.createElement('link');
    aosStyles.rel = 'stylesheet';
    aosStyles.href = 'https://unpkg.com/aos@2.3.1/dist/aos.css';
    document.head.appendChild(aosStyles);
    
    aosScript.onload = function() {
        // Инициализация AOS
        AOS.init({
            duration: 800,
            easing: 'ease-out',
            once: false,
            mirror: true
        });
    };
    
    // Простые анимации для классической секции услуг
    function initClassicServiceAnimations() {
        const serviceCards = document.querySelectorAll('.services-section.classic .service-card');
        
        // Простая анимация появления карточек
        serviceCards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100 * index);
            
            // Добавляем простой эффект при наведении
            card.addEventListener('mouseenter', function() {
                const icon = this.querySelector('.service-icon svg');
                if (icon) {
                    icon.style.transform = 'scale(1.1)';
                }
            });
            
            card.addEventListener('mouseleave', function() {
                const icon = this.querySelector('.service-icon svg');
                if (icon) {
                    icon.style.transform = '';
                }
            });
        });
        
        // Анимация при скролле
        window.addEventListener('scroll', function() {
            const servicesSection = document.querySelector('.services-section.classic');
            if (!servicesSection) return;
            
            const rect = servicesSection.getBoundingClientRect();
            const isInView = rect.top < window.innerHeight && rect.bottom > 0;
            
            if (isInView) {
                servicesSection.classList.add('in-view');
            }
        });
    }
    
    // Запускаем анимации после загрузки страницы
    setTimeout(initClassicServiceAnimations, 500);
    
    // Продвинутые анимации для карточек услуг
    function initAdvancedServiceAnimations() {
        const serviceCards = document.querySelectorAll('.services-section.light .service-card');
        
        if (!serviceCards.length) return;
        
        // Добавляем 3D эффект при наведении
        serviceCards.forEach(card => {
            card.addEventListener('mousemove', function(e) {
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left; // x position within the element
                const y = e.clientY - rect.top; // y position within the element
                
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                
                const deltaX = (x - centerX) / centerX * 10; // max 10 degrees
                const deltaY = (y - centerY) / centerY * 10; // max 10 degrees
                
                this.style.transform = `perspective(1000px) rotateX(${-deltaY}deg) rotateY(${deltaX}deg) translateY(-5px)`;
                
                // Двигаем иконку в противоположном направлении для эффекта параллакса
                const icon = this.querySelector('.service-icon');
                if (icon) {
                    icon.style.transform = `translateX(${deltaX * 0.5}px) translateY(${deltaY * 0.5}px)`;
                }
                
                // Анимируем эффект свечения
                const hoverEffect = this.querySelector('.service-hover-effect');
                if (hoverEffect) {
                    hoverEffect.style.background = `radial-gradient(circle at ${x}px ${y}px, rgba(180, 255, 70, 0.2) 0%, transparent 70%)`;
                }
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = '';
                
                const icon = this.querySelector('.service-icon');
                if (icon) {
                    icon.style.transform = '';
                }
                
                const hoverEffect = this.querySelector('.service-hover-effect');
                if (hoverEffect) {
                    hoverEffect.style.background = '';
                }
            });
            
            // Добавляем эффект пульсации при клике
            card.addEventListener('click', function() {
                this.classList.add('pulse-animation');
                setTimeout(() => {
                    this.classList.remove('pulse-animation');
                }, 500);
            });
        });
        
        // Анимация фоновых элементов
        const bgElements = document.querySelectorAll('.services-section.light .bg-element');
        
        document.addEventListener('mousemove', function(e) {
            const moveX = (e.clientX - window.innerWidth / 2) * 0.01;
            const moveY = (e.clientY - window.innerHeight / 2) * 0.01;
            
            bgElements.forEach((element, index) => {
                const depth = (index + 1) * 0.5;
                element.style.transform = `translate(${moveX * depth}px, ${moveY * depth}px)`;
            });
        });
    }
    
    // Запускаем инициализацию после загрузки страницы
    setTimeout(initAdvancedServiceAnimations, 500);
    
    // Добавляем анимацию при скролле
    window.addEventListener('scroll', function() {
        const servicesSection = document.querySelector('.services-section.light');
        if (!servicesSection) return;
        
        const rect = servicesSection.getBoundingClientRect();
        const isInView = rect.top < window.innerHeight && rect.bottom > 0;
        
        if (isInView) {
            const scrollProgress = Math.min(1, 1 - (rect.top / window.innerHeight));
            const bgElements = document.querySelectorAll('.services-section.light .bg-element');
            
            bgElements.forEach((element, index) => {
                const translateY = 50 - (scrollProgress * 100);
                element.style.opacity = 0.1 + (scrollProgress * 0.2);
                element.style.transform = `translateY(${translateY * (index + 1) * 0.2}px)`;
            });
        }
    });
    
    // Добавляем класс для анимации пульсации
    const style = document.createElement('style');
    style.textContent = `
        @keyframes pulse-animation {
            0% { transform: scale(1); box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05); }
            50% { transform: scale(1.05); box-shadow: 0 15px 30px rgba(180, 255, 70, 0.3); }
            100% { transform: scale(1); box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05); }
        }
        
        .pulse-animation {
            animation: pulse-animation 0.5s ease-out;
        }
    `;
    document.head.appendChild(style);
    
    // Ретро-анимации для секции услуг
    function initRetroAnimations() {
        // Загружаем ретро-шрифт
        const fontLink = document.createElement('link');
        fontLink.rel = 'stylesheet';
        fontLink.href = 'https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap';
        document.head.appendChild(fontLink);
        
        // Создаем стиль для ретро-эффектов
        const retroStyle = document.createElement('style');
        retroStyle.textContent = `
            @font-face {
                font-family: 'VCR OSD Mono';
                src: url('https://cdn.jsdelivr.net/gh/soroushchehresa/vcr-osd-mono/VCR_OSD_MONO_1.001.ttf') format('truetype');
            }
            
            @keyframes scanline {
                0% {
                    transform: translateY(-100%);
                }
                100% {
                    transform: translateY(100%);
                }
            }
            
            @keyframes flicker {
                0%, 19.999%, 22%, 62.999%, 64%, 64.999%, 70%, 100% {
                    opacity: 0.99;
                    filter: drop-shadow(0 0 1px rgba(0, 255, 255, 0.8)) drop-shadow(0 0 5px rgba(0, 255, 255, 0.5));
                }
                20%, 21.999%, 63%, 63.999%, 65%, 69.999% {
                    opacity: 0.4;
                    filter: none;
                }
            }
            
            .scanline {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 10px;
                background: linear-gradient(to bottom, 
                    rgba(0, 255, 255, 0) 0%,
                    rgba(0, 255, 255, 0.2) 50%,
                    rgba(0, 255, 255, 0) 100%);
                z-index: 10;
                opacity: 0.1;
                animation: scanline 8s linear infinite;
                pointer-events: none;
            }
            
            .crt-effect {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: repeating-linear-gradient(
                    0deg,
                    rgba(0, 0, 0, 0.15),
                    rgba(0, 0, 0, 0.15) 1px,
                    transparent 1px,
                    transparent 2px
                );
                pointer-events: none;
                z-index: 9;
            }
        `;
        document.head.appendChild(retroStyle);
        
        // Добавляем эффекты CRT и сканлайн
        const servicesSection = document.querySelector('.services-section.retro');
        if (servicesSection) {
            // Добавляем сканлайн
            const scanline = document.createElement('div');
            scanline.className = 'scanline';
            servicesSection.appendChild(scanline);
            
            // Добавляем CRT-эффект
            const crtEffect = document.createElement('div');
            crtEffect.className = 'crt-effect';
            servicesSection.appendChild(crtEffect);
            
            // Создаем звезды для фона
            createRetroStars(servicesSection);
            
            // Анимация для карточек услуг
            const serviceCards = document.querySelectorAll('.services-section.retro .service-card');
            serviceCards.forEach((card, index) => {
                // Добавляем эффект мерцания для неоновых границ
                card.addEventListener('mouseenter', function() {
                    const neonBorder = this.querySelector('.service-neon-border');
                    if (neonBorder) {
                        neonBorder.style.animation = 'neon-pulse 1s ease-in-out infinite alternate';
                    }
                    
                    // Добавляем эффект глитча при наведении
                    this.classList.add('glitch-effect');
                    setTimeout(() => {
                        this.classList.remove('glitch-effect');
                    }, 500);
                });
                
                card.addEventListener('mouseleave', function() {
                    const neonBorder = this.querySelector('.service-neon-border');
                    if (neonBorder) {
                        neonBorder.style.animation = '';
                    }
                });
                
                // Добавляем эффект глитча при клике
                card.addEventListener('click', function() {
                    // Создаем эффект глитча
                    const glitchEffect = document.createElement('div');
                    glitchEffect.className = 'glitch-overlay';
                    glitchEffect.style.position = 'absolute';
                    glitchEffect.style.top = '0';
                    glitchEffect.style.left = '0';
                    glitchEffect.style.width = '100%';
                    glitchEffect.style.height = '100%';
                    glitchEffect.style.background = 'rgba(0, 255, 255, 0.2)';
                    glitchEffect.style.zIndex = '5';
                    glitchEffect.style.mixBlendMode = 'overlay';
                    glitchEffect.style.pointerEvents = 'none';
                    
                    this.appendChild(glitchEffect);
                    
                    // Удаляем эффект через некоторое время
                    setTimeout(() => {
                        glitchEffect.remove();
                    }, 300);
                });
            });
        }
    }
    
    // Функция для создания ретро-звезд
    function createRetroStars(container) {
        const starsContainer = document.querySelector('.retro-stars');
        if (!starsContainer) return;
        
        for (let i = 0; i < 100; i++) {
            const star = document.createElement('div');
            star.className = 'retro-star';
            star.style.position = 'absolute';
            star.style.width = `${Math.random() * 2 + 1}px`;
            star.style.height = star.style.width;
            star.style.backgroundColor = i % 5 === 0 ? '#ff00ff' : '#00ffff';
            star.style.borderRadius = '50%';
            star.style.top = `${Math.random() * 100}%`;
            star.style.left = `${Math.random() * 100}%`;
            star.style.opacity = Math.random() * 0.8 + 0.2;
            star.style.animation = `twinkle ${Math.random() * 5 + 2}s infinite alternate`;
            star.style.boxShadow = `0 0 ${Math.random() * 5 + 2}px ${star.style.backgroundColor}`;
            
            starsContainer.appendChild(star);
        }
        
        // Добавляем анимацию мерцания звезд
        const starAnimation = document.createElement('style');
        starAnimation.textContent = `
            @keyframes twinkle {
                0% { opacity: 0.2; transform: scale(1); }
                100% { opacity: 1; transform: scale(1.2); }
            }
        `;
        document.head.appendChild(starAnimation);
    }
    
    // Запускаем ретро-анимации после загрузки страницы
    setTimeout(initRetroAnimations, 500);
    
    // Продвинутые анимации для улучшенной секции услуг
    function initEnhancedServiceAnimations() {
        // Загружаем библиотеку AOS для анимаций при скролле
        if (typeof AOS === 'undefined') {
            const aosScript = document.createElement('script');
            aosScript.src = 'https://unpkg.com/aos@2.3.1/dist/aos.js';
            aosScript.async = true;
            document.head.appendChild(aosScript);
            
            const aosStyles = document.createElement('link');
            aosStyles.rel = 'stylesheet';
            aosStyles.href = 'https://unpkg.com/aos@2.3.1/dist/aos.css';
            document.head.appendChild(aosStyles);
            
            aosScript.onload = function() {
                // Инициализация AOS
                AOS.init({
                    duration: 800,
                    easing: 'ease-out',
                    once: false,
                    mirror: true
                });
                
                // Запускаем остальные анимации
                setupCardAnimations();
            };
        } else {
            // Если AOS уже загружен, просто запускаем анимации
            setupCardAnimations();
        }
        
        function setupCardAnimations() {
            const serviceCards = document.querySelectorAll('.services-section.classic.enhanced .service-card');
            
            if (!serviceCards.length) return;
            
            // Добавляем 3D эффект при наведении
            serviceCards.forEach(card => {
                card.addEventListener('mousemove', function(e) {
                    const rect = this.getBoundingClientRect();
                    const x = e.clientX - rect.left; // x position within the element
                    const y = e.clientY - rect.top; // y position within the element
                    
                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;
                    
                    const deltaX = (x - centerX) / centerX * 5; // max 5 degrees
                    const deltaY = (y - centerY) / centerY * 5; // max 5 degrees
                    
                    this.style.transform = `perspective(1000px) rotateX(${-deltaY}deg) rotateY(${deltaX}deg) translateY(-5px) scale(1.02)`;
                    
                    // Двигаем иконку в противоположном направлении для эффекта параллакса
                    const icon = this.querySelector('.service-icon');
                    if (icon) {
                        icon.style.transform = `translateX(${deltaX * 2}px) translateY(${deltaY * 2}px) translateZ(30px)`;
                    }
                    
                    // Анимируем заголовок
                    const title = this.querySelector('h3');
                    if (title) {
                        title.style.transform = `translateX(${deltaX * 1.5}px) translateY(${deltaY * 1.5}px) translateZ(25px)`;
                    }
                    
                    // Анимируем разделитель
                    const divider = this.querySelector('.service-divider');
                    if (divider) {
                        divider.style.transform = `translateX(${deltaX * 1}px) translateY(${deltaY * 1}px) translateZ(10px)`;
                    }
                    
                    // Анимируем текст
                    const text = this.querySelector('p');
                    if (text) {
                        text.style.transform = `translateX(${deltaX * 1.5}px) translateY(${deltaY * 1.5}px) translateZ(20px)`;
                    }
                });
            });
        }
    }
    
    // Запускаем инициализацию после загрузки страницы
    setTimeout(initEnhancedServiceAnimations, 500);
});

// Функциональность модального окна для контактной формы
document.addEventListener('DOMContentLoaded', function() {
    // Находим все кнопки, которые должны открывать модальное окно
    const contactBtns = document.querySelectorAll('.contact-btn');
    const mobileContactBtn = document.querySelector('.mobile-contact-btn');
    const orderBtn = document.querySelector('.order-btn');
    const contactModal = document.getElementById('contactModal');
    const modalClose = document.querySelector('.modal-close');
    
    // Функция для открытия модального окна
    function openModal() {
        if (contactModal) {
            contactModal.classList.add('active');
            document.body.style.overflow = 'hidden'; // Предотвращаем прокрутку страницы
            
            // Закрываем мобильное меню, если оно открыто
            const mobileMenu = document.querySelector('.mobile-menu');
            if (mobileMenu && mobileMenu.classList.contains('active')) {
                mobileMenu.classList.remove('active');
            }
        }
    }
    
    // Добавляем обработчики для всех кнопок "Написать нам"
    if (contactBtns.length > 0) {
        contactBtns.forEach(btn => {
            btn.addEventListener('click', openModal);
        });
    }
    
    // Добавляем обработчик для мобильной кнопки
    if (mobileContactBtn) {
        mobileContactBtn.addEventListener('click', openModal);
    }
    
    // Добавляем обработчик для кнопки "Заказать разработку"
    if (orderBtn) {
        orderBtn.addEventListener('click', openModal);
    }
    
    // Закрытие модального окна
    if (modalClose) {
        modalClose.addEventListener('click', function() {
            contactModal.classList.remove('active');
            document.body.style.overflow = '';
        });
    }
    
    // Закрытие модального окна при клике вне его содержимого
    if (contactModal) {
        contactModal.addEventListener('click', function(e) {
            if (e.target === contactModal) {
                contactModal.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }
});