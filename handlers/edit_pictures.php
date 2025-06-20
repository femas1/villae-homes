<?php
include dirname(__DIR__) . "/config/db.php";

if (isset($_POST['action'])) {
    
    $home_id = (int)$_POST['home_id'];
    $action = $_POST['action'];

    if ($action === 'delete') {
        $picture_id = (int)$_POST['picture_id'];
        $sql = "DELETE FROM pictures WHERE id = $picture_id AND home_id = $home_id";
        mysqli_query($conn, $sql);
    }

    if ($action === 'add') {
        // inside picture upload
foreach ($_FILES['insidebilder']['tmp_name'] as $tmpName) {
    if ($tmpName && is_uploaded_file($tmpName)) {
        $bild = file_get_contents($tmpName);
        $stmt = $conn->prepare("INSERT INTO pictures (home_id, pic, main, inside, outside) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Prepare-Fehler: " . $conn->error);
        }
        $null = NULL; // notwendig für BLOB
        $main = 0;
        $inside = 1;
        $outside = 0;
        $stmt->bind_param("ibiii", $home_id, $null, $main, $inside, $outside);
        $stmt->send_long_data(1, $bild);

        if (!$stmt->execute()) {
            die("Fehler beim INSERT: " . $stmt->error);
        }

        $stmt->close();
    }
}
// outside picture upload
foreach ($_FILES['outsidebilder']['tmp_name'] as $tmpName) {
    if ($tmpName && is_uploaded_file($tmpName)) {
        $bild = file_get_contents($tmpName);
        $stmt = $conn->prepare("INSERT INTO pictures (home_id, pic, main, inside, outside) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Prepare-Fehler: " . $conn->error);
        }
        $null = NULL; // notwendig für BLOB
        $main = 0;
        $inside = 0;
        $outside = 1;
        $stmt->bind_param("ibiii", $home_id, $null, $main, $inside, $outside);
        $stmt->send_long_data(1, $bild);

        if (!$stmt->execute()) {
            die("Fehler beim INSERT: " . $stmt->error);
        }

        $stmt->close();
    }
}
// main picture upload
foreach ($_FILES['mainbilder']['tmp_name'] as $tmpName) {
    if ($tmpName && is_uploaded_file($tmpName)) {
        $bild = file_get_contents($tmpName);
        $stmt = $conn->prepare("INSERT INTO pictures (home_id, pic, main, inside, outside) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Prepare-Fehler: " . $conn->error);
        }
        $null = NULL; // notwendig für BLOB
        $main = 1;
        $inside = 0;
        $outside = 0;
        $stmt->bind_param("ibiii", $home_id, $null, $main, $inside, $outside);
        $stmt->send_long_data(1, $bild);

        if (!$stmt->execute()) {
            die("Fehler beim INSERT: " . $stmt->error);
        }

        $stmt->close();
    }

}
    }
}
header("Location: " . $_SERVER['HTTP_REFERER']);

exit;
