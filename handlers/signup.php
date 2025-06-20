<?php 

function signupEmpty($sanitized_first_name, $sanitized_last_name, $sanitized_user_email, $password, $repeat_password) {
$result;
if(empty($sanitized_first_name) || empty($sanitized_last_name) || empty($sanitized_user_email) || empty($password) || empty($repeat_password)){
   $result = true;
} else {
   $result = false;
};
return $result;
};

function invalidEmail($sanitized_user_email) {
   $result;
   if(!filter_var($sanitized_user_email, FILTER_VALIDATE_EMAIL)){
      $result = true; 
   } else {
      $result = false; 
   }
   return $result;
};

function pwdMatch($password, $repeat_password){
   $result; 
   if($password !== $repeat_password){
      $result = true; 
   } else {
      $result = false;
   }
   return $result;
};

function userExists($conn, $sanitized_user_email){
   $result;
   $sql = "SELECT * FROM users WHERE email = ?;";
   $stmt = mysqli_stmt_init($conn);
   if(!mysqli_stmt_prepare($stmt, $sql)){
      header('Location: ../views/signup.php?error=generic-error');
        exit();
   }
   mysqli_stmt_bind_param($stmt, "s", $sanitized_user_email);
   mysqli_execute($stmt);

   $data = mysqli_stmt_get_result($stmt);

   if($row = mysqli_fetch_assoc($data)){
      return $row;
   } else {
      $result = false; 
   }
   mysqli_stmt_close($stmt);
   return $result;
};

function createUser($conn, $sanitized_first_name, $sanitized_last_name, $sanitized_user_email, $password, $is_host){
   $sql = "INSERT INTO users (first_name, last_name, email, password, is_host) VALUES (?, ?, ?, ?, ?);";
   $stmt = mysqli_stmt_init($conn);
   if(!mysqli_stmt_prepare($stmt, $sql)){
      header('Location: ../views/signup.php?error=generic-error');
        exit();
   }

   $hashed_password = password_hash($password, PASSWORD_DEFAULT);

   mysqli_stmt_bind_param($stmt, "ssssi", $sanitized_first_name, $sanitized_last_name, $sanitized_user_email, $hashed_password, $is_host);
   mysqli_execute($stmt);

   mysqli_stmt_close($stmt);
   header('Location: ../views/login.php?error=none');
        exit();
};