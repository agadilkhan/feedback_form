<?php
$config = json_decode(file_get_contents('config.json'), true);

$servername = $config['db_host'];
$database = $config['db_name'];
$username = $config['db_user'];
$password = $config['db_password'];

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

