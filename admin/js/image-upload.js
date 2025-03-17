document.addEventListener('DOMContentLoaded', function() {
    // Предпросмотр загружаемых изображений
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
    
    // Удаление изображений
    const deleteButtons = document.querySelectorAll('.btn-delete-image');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const imageId = this.getAttribute('data-id');
            const imageItem = this.closest('.image-preview-item');
            
            // Создаем скрытое поле для передачи ID удаляемого изображения
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'delete_images[]';
            input.value = imageId;
            document.querySelector('form').appendChild(input);
            
            // Скрываем элемент
            imageItem.style.display = 'none';
        });
    });
    
    // Перемещение изображений вверх/вниз
    const moveUpButtons = document.querySelectorAll('.btn-move-up');
    const moveDownButtons = document.querySelectorAll('.btn-move-down');
    
    moveUpButtons.forEach(button => {
        button.addEventListener('click', function() {
            const currentItem = this.closest('.image-preview-item');
            const prevItem = currentItem.previousElementSibling;
            
            if (prevItem && prevItem.classList.contains('image-preview-item')) {
                currentItem.parentNode.insertBefore(currentItem, prevItem);
                updateMoveButtons();
            }
        });
    });
    
    moveDownButtons.forEach(button => {
        button.addEventListener('click', function() {
            const currentItem = this.closest('.image-preview-item');
            const nextItem = currentItem.nextElementSibling;
            
            if (nextItem && nextItem.classList.contains('image-preview-item')) {
                currentItem.parentNode.insertBefore(nextItem, currentItem);
                updateMoveButtons();
            }
        });
    });
    
    function updateMoveButtons() {
        const items = document.querySelectorAll('.image-preview-item');
        
        items.forEach((item, index) => {
            const upButton = item.querySelector('.btn-move-up');
            const downButton = item.querySelector('.btn-move-down');
            
            // Обновляем состояние кнопок
            if (upButton) {
                upButton.disabled = index === 0;
            }
            
            if (downButton) {
                downButton.disabled = index === items.length - 1;
            }
            
            // Обновляем скрытое поле с порядком сортировки
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `image_order[${item.getAttribute('data-id')}]`;
            input.value = index + 1;
            
            // Удаляем существующее поле, если оно есть
            const existingInput = document.querySelector(`input[name="image_order[${item.getAttribute('data-id')}]"]`);
            if (existingInput) {
                existingInput.remove();
            }
            
            document.querySelector('form').appendChild(input);
        });
    }
}); 