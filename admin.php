<?php
    session_start();

    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: admin_login.php');
        exit;
    }

    include 'db.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $reviewId = $_POST['review_id'];
        $editedMessage = $_POST['edited_message'];
    
        // Проверка валидности данных
        if (!empty($reviewId) && !empty($editedMessage)) {
            $editedMessage = mysqli_real_escape_string($connection, $editedMessage);
            $query = "UPDATE reviews
            SET message = CASE
                WHEN message LIKE '%(изменен администратором)' THEN message
                ELSE CONCAT(message, ' (изменен администратором)')
                END
            WHERE id = $reviewId;
            ";
            mysqli_query($connection, $query);
        } else {
            echo 'Ошибка: Не удалось сохранить изменения. Проверьте данные формы.';
        }
    }
 

    $query = "SELECT * FROM reviews ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Панель администратора</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Панель администратора</h1>

    <h2>Список отзывов</h2>
    <div id="reviews-container">
    <?php
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class=element>';
        echo 'Имя: ' . $row['name'] . '<br>';
        echo 'E-mail: ' . $row['email'] . '<br>';
        if ($row['modified_by_admin'] == 1) {
            echo 'Текст сообщения: ' . htmlspecialchars($row['message']) . ' (изменен администратором)<br>';
        } else {
            echo 'Текст сообщения: ' . htmlspecialchars($row['message']) . '<br>';
        }
        echo "Статус: " .$row['status'] . '<br>';

        if ($row['image_path']) {
            // Set the maximum width and height for the image
            $maxWidth = 50; // Change this to the desired width
            $maxHeight = 50; // Change this to the desired height
            
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

        // Toggle the visibility of the edit form
        echo '<button onclick="toggleEditForm(' . $row['id'] . ')">Редактировать</button>';

        // Edit form
        echo '<div style="display: none;" id="editForm_' . $row['id'] . '">';
        echo '<form action="edit_review.php" method="post">';
        echo '<input type="hidden" name="review_id" value="' . $row['id'] . '">';
        echo '<label for="edited_message">Текст сообщения:</label><br>';
        echo '<textarea name="edited_message" rows="4" required>' . htmlspecialchars($row['message']) . '</textarea><br>';
        echo '<input type="submit" name="action" value="Принять и сохранить изменения">';
        echo '<input type="submit" name="action" value="Отклонить">';
        echo '</form>';
        echo '</div>';

        echo '</div>';
    }
    ?>
    </div>

    <script>
        function toggleEditForm(reviewId) {
            var editForm = document.getElementById('editForm_' + reviewId);
            if (editForm.style.display === 'none') {
                editForm.style.display = 'block';
            } else {
                editForm.style.display = 'none';
            }
        }
    </script>
</body>
</html>
