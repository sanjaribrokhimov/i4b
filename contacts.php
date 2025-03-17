<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>I4B AGENCY - Контакты</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/contacts.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
   <?php include 'components/header.php'; ?>

    <section class="contacts-hero">
        <div class="left-border"></div>
        <div class="right-border"></div>
        <div class="hero-diagonal-lines">
            <div class="diagonal-line" style="--rotation: 30deg;"></div>
            <div class="diagonal-line" style="--rotation: -25deg;"></div>
            <div class="diagonal-line" style="--rotation: 20deg;"></div>
        </div>
        <div class="container">
            <h1 class="contacts-title">Контакты</h1>
            <p class="contacts-subtitle">Свяжитесь с нами любым удобным способом</p>
        </div>
    </section>

    <section class="contact-info-section">
        <div class="container">
            <div class="contact-info-grid">
                <div class="contact-info-item">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="contact-details">
                        <h3>Телефон</h3>
                        <p><a href="tel:+998331108810">+998 33 110 88 10</a></p>
                    </div>
                </div>
                
                <div class="contact-info-item">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="contact-details">
                        <h3>Email</h3>
                        <p><a href="mailto:info@i4b.agency">info@i4b.agency</a></p>
                    </div>
                </div>
                
                <div class="contact-info-item">
                    <div class="contact-icon">
                        <i class="fab fa-telegram"></i>
                    </div>
                    <div class="contact-details">
                        <h3>Telegram</h3>
                        <p><a href="https://t.me/i4bagency" target="_blank">@i4bagency</a></p>
                    </div>
                </div>
                
                <div class="contact-info-item">
                    <div class="contact-icon">
                        <i class="fab fa-instagram"></i>
                    </div>
                    <div class="contact-details">
                        <h3>Instagram</h3>
                        <p><a href="https://instagram.com/i4b.agency" target="_blank">@i4b.agency</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="contact-main-section">
        <div class="container">
            <div class="contact-main-wrapper">
                <div class="contact-form-side">
                    <h2>Напишите нам</h2>
                    <p class="contact-form-description">Заполните форму, и мы свяжемся с вами в ближайшее время для обсуждения вашего проекта</p>
                    
                    <form class="contact-form" id="mainContactForm">
                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" id="main-name" name="name" placeholder="Имя" required>
                            </div>
                            <div class="form-group">
                                <input type="text" id="main-company" name="company" placeholder="Компания">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" id="main-telegram" name="telegram" placeholder="Telegram username" required>
                            </div>
                            <div class="form-group">
                                <input type="tel" id="main-phone" name="phone" placeholder="Телефон">
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea id="main-message" name="message" placeholder="Ваш вопрос, предложение" rows="5" required></textarea>
                        </div>
                        <div class="form-group submit-group">
                            <button type="submit" class="submit-btn">Отправить</button>
                        </div>
                        <div class="form-status" id="mainFormStatus"></div>
                    </form>
                </div>
                
                <div class="contact-image-side">
                    <div class="green-background">
                        <div class="green-pattern"></div>
                        <div class="green-glow"></div>
                        <div class="green-dots"></div>
                        <div class="contact-info-card">
                            <h3>Наши контакты</h3>
                            <div class="contact-info-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <p>Ташкент, Узбекистан</p>
                            </div>
                            <div class="contact-info-item">
                                <i class="fas fa-phone"></i>
                                <p>+998 33 110 88 10</p>
                            </div>
                            <div class="contact-info-item">
                                <i class="fas fa-envelope"></i>
                                <p>info@i4b.agency</p>
                            </div>
                            <div class="contact-info-item">
                                <i class="fab fa-telegram"></i>
                                <p>@i4bagency</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="map-section">
        <div class="container">
            <h2>Как нас найти</h2>
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2996.0451267487!2d69.2850902!3d41.3123019!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDHCsDE4JzQ0LjMiTiA2OcKwMTcnMDYuMyJF!5e0!3m2!1sru!2s!4v1623456789012!5m2!1sru!2s" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </section>

    <?php include 'components/footer.php'; ?>

    <div class="modal-overlay" id="contactModal">
        <div class="modal-container">
            <button class="modal-close"><i class="fas fa-times"></i></button>
            <div class="modal-header">
                <h2>Напишите нам</h2>
                <p>Заполните форму, и мы свяжемся с вами в ближайшее время</p>
            </div>
            <form id="modalForm" class="modal-form">
                <div class="form-row">
                    <div class="form-group">
                        <input type="text" id="modal-name" name="name" placeholder="Имя" required>
                    </div>
                    <div class="form-group">
                        <input type="text" id="modal-company" name="company" placeholder="Компания">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <input type="text" id="modal-telegram" name="telegram" placeholder="Telegram username" required>
                    </div>
                    <div class="form-group">
                        <input type="tel" id="modal-phone" name="phone" placeholder="Телефон">
                    </div>
                </div>
                <div class="form-group">
                    <textarea id="modal-message" name="message" placeholder="Ваш вопрос, предложение" rows="5" required></textarea>
                </div>
                <div class="form-group submit-group">
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
            // Обновляем обработчик основной формы
            const mainContactForm = document.getElementById('mainContactForm');
            
            if (mainContactForm) {
                // Клонируем форму и заменяем оригинал, чтобы удалить все обработчики событий
                const newForm = mainContactForm.cloneNode(true);
                mainContactForm.parentNode.replaceChild(newForm, mainContactForm);
                
                // Добавляем новый обработчик событий
                newForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Показываем индикатор загрузки
                    const mainFormStatus = document.getElementById('mainFormStatus');
                    mainFormStatus.innerHTML = '<div class="loading-message"><i class="fas fa-spinner fa-spin"></i> Отправка сообщения...</div>';
                    
                    // Собираем данные формы
                    const data = {
                        name: document.getElementById('main-name').value,
                        company: document.getElementById('main-company').value,
                        telegram: document.getElementById('main-telegram').value,
                        phone: document.getElementById('main-phone').value,
                        message: document.getElementById('main-message').value
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
                            mainFormStatus.innerHTML = '<div class="success-message"><i class="fas fa-check-circle"></i> Ваше сообщение успешно отправлено!</div>';
                            newForm.reset();
                            
                            // Показываем модальное окно успешной отправки
                            showSuccessModal();
                            
                            // Скрываем сообщение в форме через 3 секунды
                            setTimeout(() => {
                                mainFormStatus.innerHTML = '';
                            }, 3000);
                        } else {
                            mainFormStatus.innerHTML = '<div class="error-message"><i class="fas fa-exclamation-circle"></i> Ошибка при отправке сообщения: ' + (data.description || 'Неизвестная ошибка') + '</div>';
                            console.error('Server error:', data);
                        }
                    })
                    .catch(error => {
                        mainFormStatus.innerHTML = '<div class="error-message"><i class="fas fa-exclamation-triangle"></i> Произошла ошибка. Пожалуйста, попробуйте еще раз.</div>';
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
</body>
</html> 