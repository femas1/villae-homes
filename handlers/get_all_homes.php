<?php
include dirname(__DIR__) . "/config/db.php";

$sql = 'SELECT h.*, hk.words,
        (SELECT pic FROM pictures p WHERE p.home_id = h.home_id AND p.main = 1 LIMIT 1) AS pic
        FROM homes h
        LEFT JOIN home_keywords hk ON h.home_id = hk.home_id';

$result = mysqli_query($conn, $sql);

$homes = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_free_result($result);
mysqli_close($conn);
?>
