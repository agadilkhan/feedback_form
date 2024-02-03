<?php
// Подключение к базе данных 
include 'db.php';

// Определение параметра сортировки
    $sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'created_at';

// Получение отзывов из базы данных с учетом выбранной сортировки и статуса "accepted"
    $query = "SELECT * FROM reviews WHERE status='accepted' ORDER BY ";
    if ($sort_by === 'name') {
        $query .= "name ASC, created_at DESC";
    } elseif ($sort_by === 'email') {
        $query .= "email ASC, created_at DESC";
    } else {
        // По умолчанию сортируем по времени добавления (дате)
        $query .= "created_at DESC";
    }

    $result = mysqli_query($conn, $query);

// Вывод отзывов
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="element">';
        echo 'Имя: ' . $row['name'] . '<br>';
        echo 'E-mail: ' . $row['email'] . '<br>';
        
        
        if ($row['modified_by_admin'] == 1) {
            echo 'Текст сообщения: ' . $row['message'] . ' (изменен администратором)<br>';
        } else {
            echo 'Текст сообщения: ' . $row['message'] . '<br>';
        }
        
        if ($row['image_path']) {
            // Определение максимальной ширины и высоты изображения.
            $maxWidth = 200; 
            $maxHeight = 150;
            
            list($width, $height) = getimagesize($row['image_path']);
            
            // Вычисление нового размера изображения
            $aspectRatio = $width / $height;
            if ($width > $maxWidth || $height > $maxHeight) {
                if ($width / $maxWidth > $height / $maxHeight) {
                    $newWidth = $maxWidth;
                    $newHeight = $maxWidth / $aspectRatio;
                } else {
                    $newHeight = $maxHeight;
                    $newWidth = $maxHeight * $aspectRatio;
                }
            } else {
                $newWidth = $width;
                $newHeight = $height;
            }

            // Вывод изображения новыми размерами
            echo '<img src="' . $row['image_path'] . '" alt="Изображение" width="' . $newWidth . '" height="' . $newHeight . '"><br>';
        }
        echo '</div>';
    }
?>
