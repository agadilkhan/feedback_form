<?php
// Подключение к базе данных 
include 'db.php';

// Определение параметра сортировки
    $sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'created_at';

// Получение отзывов из базы данных с учетом выбранной сортировки и статуса "accepted"
    $query = "SELECT * FROM reviews WHERE status='accepted' ORDER BY ";
    if ($sort_by === 'name') {
        $query .= "name ASC, date_added DESC";
    } elseif ($sort_by === 'email') {
        $query .= "email ASC, date_added DESC";
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
            // Set the maximum width and height for the image
            $maxWidth = 200; // Change this to the desired width
            $maxHeight = 150; // Change this to the desired height
            
            // Get the image dimensions using getimagesize()
            list($width, $height) = getimagesize($row['image_path']);
            
            // Calculate the new dimensions while maintaining the aspect ratio
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

            // Output the image with the new dimensions
            echo '<img src="' . $row['image_path'] . '" alt="Изображение" width="' . $newWidth . '" height="' . $newHeight . '"><br>';
        }
        echo '</div>';
    }
?>
