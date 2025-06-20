<?php

include dirname(__DIR__) . '/config/db.php';
include dirname(__DIR__) . '/config/config.php';

if (isset($_POST['action'])) {
    $home_id = (int) $_POST['home_id'];
    $old = trim($_POST['old_searchword']);
    $new = trim($_POST['new_searchword']);
    $action = $_POST['action'];

  
    $sql = "SELECT words FROM home_keywords WHERE home_id =  $home_id";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result);
  

    if (!$data) {
        echo "<p>Haus nicht gefunden.</p>";
        exit;
    }

    $searchwords = $data['words'];
    $list = array_map('trim', explode(' ', $searchwords));

    if ($action === 'replace') {
        if ($new === '') {
            echo "<p>Bitte gib ein neues Schlagwort zum Ersetzen ein.</p>";
        } else {
            //array_map uses a function too all array-elements
            $list = array_map(function($wort) use ($old, $new) {
                return $wort === $old ? $new : $wort;
            }, $list);
            echo "<p>Schlagwort wurde ersetzt.</p>";
        }
    } elseif ($action === 'delete') {
        $list = array_filter($list, function($wort) use ($old) {
            return $wort !== $old;
        });
        echo "<p>Schlagwort wurde gelöscht.</p>";
    }
    elseif ($action === 'add') {
    if ($new === '') {
        echo "<p>Bitte gib ein neues Schlagwort zum Hinzufügen ein.</p>";
    } else {
        // add a new seachword to the list
        $list[] = $new;
        echo "<p>Schlagwort wurde hinzugefügt.</p>";
    }
}

    // save results
    $new_searchwords = implode(' ', $list);
    $update = "UPDATE home_keywords SET words = '" . mysqli_real_escape_string($conn, $new_searchwords) . "' WHERE home_id = $home_id";
    mysqli_query($conn, $update);

    echo '<p><a href="#" onclick="window.location.href=document.referrer; return false;">Zurück</a></p>';
    mysqli_close($conn);
}

?>
