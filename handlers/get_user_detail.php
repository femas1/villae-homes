<?php

include dirname(__DIR__) . '/config/db.php';

$user_id = $_GET['user_id'];

$sql = "SELECT * FROM users WHERE user_id = ?;";

$stmt = mysqli_stmt_init($conn);

if(!mysqli_stmt_prepare($stmt, $sql)){
    die("sql error");
};

mysqli_stmt_bind_param($stmt, "i", $user_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

