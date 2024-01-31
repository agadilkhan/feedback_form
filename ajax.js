// JavaScript для отправки формы и обновления списка отзывов через AJAX
$(document).ready(function() {
    // Функция для обновления списка отзывов
    function updateReviewsList() {
        $.ajax({
            url: 'get_reviews.php',
            type: 'GET',
            dataType: 'html',
            success: function(data) {
                
            },
            error: function() {
                console.error('Ошибка AJAX: Не удалось получить список отзывов.');
            }
        });
    }

    // Отслеживание события отправки формы
    $('#feedback-form').submit(function(event) {
        event.preventDefault();

        var formData = new FormData($(this)[0]);

        $.ajax({
            url: 'feedback_handler.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(data) {
                if (data.status === 'success') {
                    // Обновляем список отзывов
                    updateReviewsList();

                    // Очищаем поля формы после успешной отправки
                    $('#feedback-form')[0].reset();

                    // Отображаем предварительный просмотр отзыва
                    var review = data.review;
                    var previewHTML = `
                <div>
                    Имя: ${review.name}<br>
                    E-mail: ${review.email}<br>
                    Текст сообщения: ${review.message}<br>
                    ${review.image_path ? '<img src="' + review.image_path + '" alt="Изображение" class="preview-image"><br>' : ''}
                </div>
            `;
                    $('#preview-container').html(previewHTML);
                } else {
                    $('#preview-container').html('Ошибка при добавлении отзыва: ' + data.message);
                }
            },
            error: function() {
                $('#preview-container').html('Ошибка AJAX: Не удалось отправить данные на сервер.');
            }
        });
    });
});