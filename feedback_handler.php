<?php
// Подключение к базе данных
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];


    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = array('status' => 'error', 'message' => 'Некорректный адрес электронной почты.');
        echo json_encode($response);
        exit;
    }

    // Проверка наличия прикрепленного изображения
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image = $_FILES['image'];

        // Проверка размера и формата изображения
        if ($image['size'] > 1048576) { // 1 MB в байтах
            $response = array('status' => 'error', 'message' => 'Изображение должно быть не более 1 MB.');
            echo json_encode($response);
            exit;
        }

        $allowedFormats = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($image['type'], $allowedFormats)) {
            $response = array('status' => 'error', 'message' => 'Недопустимый формат изображения. Допустимые форматы: JPG, PNG, GIF.');
            echo json_encode($response);
            exit;
        }

        
        $imagePath = 'img/' . $image['name'];
        move_uploaded_file($image['tmp_name'], $imagePath);
    } else {
        $imagePath = null;
    }

   
    $query = "INSERT INTO reviews (name, email, message, image_path) 
          VALUES ('$name', '$email', '$message', '$imagePath')";

    if (mysqli_query($conn, $query)) {
        $response = array(
            'status' => 'success',
            'review' => array(
                'name' => $name,
                'email' => $email,
                'message' => $message,
                'image_path' => $imagePath
            )
        );
        echo json_encode($response);
    } else {
        $response = array('status' => 'error', 'message' => 'Ошибка при добавлении отзыва: ' . mysqli_error($conn));
        echo json_encode($response);
    }
}
?>
