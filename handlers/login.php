<?php

include '../handlers/signup.php';

function loginEmpty($sanitized_user_email, $password){
    $result;
    if(empty($sanitized_user_email) || empty($password)){
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function loginUser($conn, $sanitized_user_email, $password) {

    // 1. Check if user exists
    $userExists = userExists($conn, $sanitized_user_email);
    if ($userExists === false) {
        header('Location: ../views/login.php?error=wronglogin');
        exit();
    }

    // 2. Verify password
    $hashed_password = $userExists['password'];
    if (!password_verify($password, $hashed_password)) {
        header('Location: ../views/login.php?error=wrongpassword');
        exit();
    }

    // 3. Start session
    session_start();
    $_SESSION['user_session_id'] = $userExists['user_id'];
    $_SESSION['user_session_email'] = $userExists['email'];
    $_SESSION['user_session_name'] = $userExists['first_name'];
    $_SESSION['user_role'] = $userExists['is_host'];
    header('Location: ../views/homes.php');
    exit();
}