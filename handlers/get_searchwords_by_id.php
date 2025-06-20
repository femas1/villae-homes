<?php

include dirname(__DIR__) . "/config/db.php";


$sql = "SELECT words FROM home_keywords WHERE home_id = " . (int)$id;

$result = mysqli_query($conn, $sql);

$homes = mysqli_fetch_assoc($result);

mysqli_free_result($result);
mysqli_close($conn);


        //explode creates a array of strings out of one string
        $searchwords = explode(' ', $homes['words']);