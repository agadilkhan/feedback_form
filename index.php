<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Отзывы</title>
    <style>
        .preview-image {
            width: 100px;
            height: 100px; 
        }
    </style>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="ajax.js"></script>
</head>

<body>
    <h1>Отзывы</h1>
    <div class="sorting">
        <p>Сортировать по:</p>
        <?php
        $sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'created_at';
        ?>
        <form method="get">
            <select name="sort" onchange="this.form.submit()">
                <option value="name" <?php if ($sort_by === 'name') echo 'selected'; ?>>Имени</option>
                <option value="email" <?php if ($sort_by === 'email') echo 'selected'; ?>>E-mail</option>
                <option value="date_added" <?php if ($sort_by === 'date_added') echo 'selected'; ?>>Времени добавления</option>
            </select>
        </form>
    </div>


    <div id="reviews-container">
        <?php
        include 'get_reviews.php';
            ?>
    </div>

    <h2>Добавить отзыв</h2>
    <div class="form">
        <form id="feedback-form" enctype="multipart/form-data">
            <label for="name">Имя:</label><br>
            <input type="text" name="name" required><br>

            <label for="email">E-mail:</label><br>
            <input type="email" name="email" required><br>

            <label for="message">Текст сообщения:</label><br>
            <textarea name="message" rows="4" required></textarea><br>

            <label for="image">Прикрепить картинку:</label>
            <input type="file" name="image"><br>

            <div class="submit">
                <input type="submit" value="Отправить">
            </div>
        </form>

        <div id="preview-container">
        </div>
    </div>

</body>
</html>