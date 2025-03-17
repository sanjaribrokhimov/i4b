// Функция для адаптивного отображения карточек на мобильных устройствах
function adjustForMobile() {
    const isMobile = window.innerWidth <= 768;
    
    // Функция для обрезания текста
    function truncateText(selector, maxLength) {
        if (!isMobile) return;
        
        document.querySelectorAll(selector).forEach(element => {
            const text = element.textContent;
            if (text.length > maxLength) {
                element.textContent = text.substring(0, maxLength) + '...';
            }
        });
    }
    
    // Обрезаем длинные тексты на мобильных устройствах
    truncateText('.project-content p', 80);
    truncateText('.service-card p', 70);
    truncateText('.telecom-service-card p', 70);
    truncateText('.news-content p', 80);
    
    // Сбрасываем высоту всех карточек на мобильных
    if (isMobile) {
        const selectors = [
            '.project-card', 
            '.service-card', 
            '.telecom-service-card', 
            '.team-member',
            '.news-card'
        ];
        
        selectors.forEach(selector => {
            document.querySelectorAll(selector).forEach(card => {
                card.style.height = 'auto';
            });
        });
    }
}

// Функция для создания карусели на мобильных устройствах
function setupCarousel(containerSelector, cardSelector, carouselClass, dotsClass, arrowsClass, dotClass) {
    const isMobile = window.innerWidth <= 768;
    const container = document.querySelector(containerSelector);
    
    if (!container) return;
    
    // Если мобильное устройство и карусель еще не создана
    if (isMobile && !container.querySelector(`.${carouselClass}`)) {
        // Получаем все карточки
        const cards = Array.from(container.querySelectorAll(cardSelector));
        
        // Создаем обертку для карусели
        const carousel = document.createElement('div');
        carousel.className = carouselClass;
        
        // Перемещаем все карточки в карусель
        cards.forEach(card => {
            carousel.appendChild(card);
        });
        
        // Добавляем карусель в контейнер
        container.appendChild(carousel);
        
        // Создаем навигацию
        const navigation = document.createElement('div');
        navigation.className = `${carouselClass}-navigation`;
        
        // Создаем точки
        const dots = document.createElement('div');
        dots.className = dotsClass;
        
        cards.forEach((_, index) => {
            const dot = document.createElement('div');
            dot.className = dotClass;
            if (index === 0) dot.classList.add('active');
            dot.addEventListener('click', () => {
                moveToSlide(index, carouselClass, dotClass);
            });
            dots.appendChild(dot);
        });
        
        navigation.appendChild(dots);
        container.appendChild(navigation);
        
        // Создаем стрелки
        const arrows = document.createElement('div');
        arrows.className = arrowsClass;
        
        const prevArrow = document.createElement('button');
        prevArrow.className = `${carouselClass}-arrow prev-arrow`;
        prevArrow.innerHTML = '<i class="fas fa-chevron-left"></i>';
        prevArrow.addEventListener('click', () => {
            const activeIndex = getCurrentSlideIndex(dotClass);
            moveToSlide(activeIndex > 0 ? activeIndex - 1 : cards.length - 1, carouselClass, dotClass);
        });
        
        const nextArrow = document.createElement('button');
        nextArrow.className = `${carouselClass}-arrow next-arrow`;
        nextArrow.innerHTML = '<i class="fas fa-chevron-right"></i>';
        nextArrow.addEventListener('click', () => {
            const activeIndex = getCurrentSlideIndex(dotClass);
            moveToSlide(activeIndex < cards.length - 1 ? activeIndex + 1 : 0, carouselClass, dotClass);
        });
        
        arrows.appendChild(prevArrow);
        arrows.appendChild(nextArrow);
        container.appendChild(arrows);
        
        // Инициализируем первый слайд
        moveToSlide(0, carouselClass, dotClass);
        
        // Добавляем свайп для мобильных
        let touchStartX = 0;
        let touchEndX = 0;
        
        carousel.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, false);
        
        carousel.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe(carouselClass, dotClass, cards.length);
        }, false);
        
        function handleSwipe(carouselClass, dotClass, cardsLength) {
            const activeIndex = getCurrentSlideIndex(dotClass);
            if (touchEndX < touchStartX) {
                // Свайп влево - следующий слайд
                moveToSlide(activeIndex < cardsLength - 1 ? activeIndex + 1 : 0, carouselClass, dotClass);
            } else if (touchEndX > touchStartX) {
                // Свайп вправо - предыдущий слайд
                moveToSlide(activeIndex > 0 ? activeIndex - 1 : cardsLength - 1, carouselClass, dotClass);
            }
        }
    } else if (!isMobile && container.querySelector(`.${carouselClass}`)) {
        // Если не мобильное устройство, но карусель существует - возвращаем к обычному виду
        const carousel = container.querySelector(`.${carouselClass}`);
        const cards = Array.from(carousel.querySelectorAll(cardSelector));
        
        // Возвращаем карточки в контейнер
        cards.forEach(card => {
            container.appendChild(card);
        });
        
        // Удаляем карусель и навигацию
        container.querySelector(`.${carouselClass}`)?.remove();
        container.querySelector(`.${carouselClass}-navigation`)?.remove();
        container.querySelector(`.${arrowsClass}`)?.remove();
    }
}

// Функция для получения индекса активного слайда
function getCurrentSlideIndex(dotClass) {
    const dots = document.querySelectorAll(`.${dotClass}`);
    for (let i = 0; i < dots.length; i++) {
        if (dots[i].classList.contains('active')) {
            return i;
        }
    }
    return 0;
}

// Функция для перемещения к определенному слайду
function moveToSlide(index, carouselClass, dotClass) {
    const carousel = document.querySelector(`.${carouselClass}`);
    if (!carousel) return;
    
    const card = carousel.querySelector('*');
    if (!card) return;
    
    const cardWidth = card.offsetWidth + parseInt(getComputedStyle(card).marginRight);
    
    carousel.style.transform = `translateX(-${index * cardWidth}px)`;
    
    // Обновляем активную точку
    document.querySelectorAll(`.${dotClass}`).forEach((dot, i) => {
        dot.classList.toggle('active', i === index);
    });
}

// Вызываем функцию при загрузке и изменении размера окна
document.addEventListener('DOMContentLoaded', function() {
    adjustForMobile();
    
    // Настройка карусели для телекоммуникаций
    setupCarousel(
        '.telecom-services', 
        '.telecom-service-card', 
        'telecom-carousel', 
        'telecom-dots', 
        'telecom-arrows', 
        'telecom-dot'
    );
    
    // Настройка карусели для отзывов
    setupCarousel(
        '.testimonials-grid', 
        '.testimonial-card', 
        'testimonials-carousel', 
        'testimonial-dots', 
        'testimonials-arrows', 
        'testimonial-dot'
    );
});

window.addEventListener('resize', function() {
    adjustForMobile();
    
    // Обновляем карусели при изменении размера окна
    setupCarousel(
        '.telecom-services', 
        '.telecom-service-card', 
        'telecom-carousel', 
        'telecom-dots', 
        'telecom-arrows', 
        'telecom-dot'
    );
    
    setupCarousel(
        '.testimonials-grid', 
        '.testimonial-card', 
        'testimonials-carousel', 
        'testimonial-dots', 
        'testimonials-arrows', 
        'testimonial-dot'
    );
}); 