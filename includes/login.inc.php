<?php

include_once '../config/db.php';
include_once '../handlers/login.php';

if(isset($_POST['submit'])){
   $sanitized_user_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
   $validated_user_email = filter_var($sanitized_user_email, FILTER_VALIDATE_EMAIL);
   $password = $_POST['password'];

   if(loginEmpty($sanitized_user_email, $password) !== false){
    header('Location: ../views/login.php?error=emptyinput');
    exit();
   }

   if(!filter_var($sanitized_user_email, FILTER_VALIDATE_EMAIL)){
      header('Location: ../views/login.php?error=wrongemail');
      exit();
   }

   loginUser($conn, $validated_user_email, $password);
}
