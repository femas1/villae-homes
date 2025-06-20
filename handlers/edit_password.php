<?php 

include_once '../config/db.php';

if(isset($_POST['submit'])){
    $user_id = $_POST['user_id'];
    $old_password = $_POST['old-password'];
    $password = $_POST['new-password'];
    $repeated_password = $_POST['new-password-repeat'];

    if(empty($old_password) || empty($password) || empty($repeated_password)){
        header("Location: ../views/edit_user.php?user_id=$user_id&error=emptyinput");
        exit();
    }

    if($password !== $repeated_password){
        header("Location: ../views/edit_user.php?user_id=$user_id&error=pwddontmatch");
        exit();
    }

// Verify old password 
function checkPwd($conn, $user_id, $old_password){
    $sql = "SELECT password FROM users WHERE user_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("Location: ../views/edit_user.php?user_id=$user_id&error=generic-error");
        exit();
    };
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $hash = $row['password'];
    if(!password_verify($old_password, $hash)){
        header("Location: ../views/edit_user.php?user_id=$user_id&error=wrong-password");
        exit();
    };
    mysqli_stmt_close($stmt);
};

checkPwd($conn, $user_id, $old_password);

    // update password
function updatePwd($password, $conn, $user_id){
    $sql = "UPDATE users SET password = ? WHERE user_id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("Location: ../views/edit_user.php?user_id=$user_id&error=generic-error");
        exit();
    };
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt, "si", $hashed_password, $user_id);
    mysqli_stmt_execute($stmt);

    if(mysqli_affected_rows($conn) < 1){
        header("Location: ../views/edit_user.php?user_id=$user_id&error=pwdchange-error");
        exit();
    } else {
        header("Location: ../views/edit_user.php?user_id=$user_id&error=none");
        exit();
    };
    mysqli_stmt_close($stmt);
}

updatePwd($password, $conn, $user_id); 

}