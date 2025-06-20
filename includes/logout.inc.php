<?php 

// include dirname(__DIR__) . '/config/config.php';
    session_start();
    session_unset();
    session_destroy();
    header('Location: ../index.php?=logout');
    exit();
