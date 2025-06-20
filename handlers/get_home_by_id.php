<?php

include '../config/db.php';

$sanitized_home_id = filter_var($home_id, FILTER_SANITIZE_NUMBER_INT);

$sql = "SELECT h.*, p.*, hk.words 
        FROM homes h 
        LEFT JOIN pictures p ON h.home_id = p.home_id 
        LEFT JOIN home_keywords hk ON h.home_id = hk.home_id
        WHERE h.home_id = ? 
        ORDER BY 
            p.main DESC,    
            p.outside DESC, 
            p.inside DESC"; 

$stmt = mysqli_stmt_init($conn);

if(!mysqli_stmt_prepare($stmt, $sql)){
    die(mysqli_error($conn));
};

mysqli_stmt_bind_param($stmt, "i", $sanitized_home_id);

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$home_values = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_free_result($result);

// GET OWNER DATA (FOR SHOWING IN HOME DETAIL PAGE)

$user_id = $_GET['owner_id'];

$sanitized_user_id = filter_var($user_id, FILTER_SANITIZE_NUMBER_INT);

$sql = "SELECT * FROM users WHERE user_id = ?";

$stmt = mysqli_stmt_init($conn);

if(!mysqli_stmt_prepare($stmt, $sql)){
    die(mysqli_error($conn));
};

mysqli_stmt_bind_param($stmt, "i", $sanitized_user_id);

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);