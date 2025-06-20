<?php 

include_once '../config/db.php';
include_once '../handlers/signup.php';

if(isset($_POST['submit'])){

    $sanitized_first_name = filter_var($_POST['first_name'], FILTER_SANITIZE_SPECIAL_CHARS);
    $sanitized_last_name = filter_var($_POST['last_name'], FILTER_SANITIZE_SPECIAL_CHARS);
    $sanitized_user_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $repeat_password = $_POST['repeat-password'];
    $is_host = $_POST['is_host'];

    if(signupEmpty($sanitized_first_name, $sanitized_last_name, $sanitized_user_email, $password, $repeat_password) !== false){
        header('Location: ../views/signup.php?error=emptyinput');
        exit();
    };  

    if(invalidEmail($sanitized_user_email) !== false){
        header('Location: ../views/signup.php?error=invalidmail');
        exit();
    };

    if(pwdMatch($password, $repeat_password) !== false){
        header('Location: ../views/signup.php?error=pwdmatcherror');
        exit();
    };

    if(userExists($conn, $sanitized_user_email) !== false){
        header('Location: ../views/signup.php?error=userexists');
        exit();
    };

    createUser($conn, $sanitized_first_name, $sanitized_last_name, $sanitized_user_email, $password, $is_host);

} else {
    header('Location: ../views/signup.php');
};