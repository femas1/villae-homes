<?php

$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');
$dbname = getenv('DB_NAME');
$port = getenv('DB_PORT');

$conn = mysqli_connect($servername, $username, $password, $dbname, $port);

if (!$conn) {
    die("Verbindung fehlgeschlagen: " . mysqli_connect_error());
}
?>