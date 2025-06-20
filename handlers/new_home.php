<?php 

include dirname(__DIR__) . '/config/db.php';
include dirname(__DIR__) . '/config/config.php';

if(isset($_POST['submit'])){

    // sanitizing values using filter constants
    
    $sanitizedName = filter_var(trim($_POST['name']), FILTER_SANITIZE_SPECIAL_CHARS);
    $sanitizedLocation = filter_var(trim($_POST['location']), FILTER_SANITIZE_SPECIAL_CHARS);
    $sanitizedSize = filter_var(trim($_POST['size']), FILTER_SANITIZE_NUMBER_INT);
    $sanitizedBedrooms = filter_var(trim($_POST['bedrooms']), FILTER_SANITIZE_NUMBER_INT);
    $sanitizedPrice = filter_var(trim($_POST['price']), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $sanitized_user_id = filter_var(trim($_POST['user_id']), FILTER_SANITIZE_NUMBER_INT);
    $sanitizedDescription = filter_var(trim($_POST['description']), FILTER_SANITIZE_SPECIAL_CHARS);
    $sanitizedBathrooms = filter_var(trim($_POST['bathrooms']), FILTER_SANITIZE_NUMBER_INT);
    $sanitizedWords = filter_var(trim($_POST['words']), FILTER_SANITIZE_SPECIAL_CHARS);
    $sanitizedLink = filter_var(trim($_POST['link']), FILTER_SANITIZE_SPECIAL_CHARS);
    $sanitized_breakfast = isset($_POST['breakfast']) ? 1 : 0;
    $sanitized_petsitting = isset($_POST['petsitting']) ? 1 : 0;
    $sanitized_bike_rental = isset($_POST['bike_rental']) ? 1 : 0;
    $sanitized_car_rental = isset($_POST['car_rental']) ? 1 : 0;
    $sanitized_laundry_service = isset($_POST['laundry_service']) ? 1 : 0;
    $sanitized_pool = isset($_POST['pool']) ? 1 : 0;
    $sanitized_billiard = isset($_POST['billiard']) ? 1 : 0;
    $sanitized_fitness = isset($_POST['fitness']) ? 1 : 0;
    $sanitized_sauna = isset($_POST['sauna']) ? 1 : 0;
    $sanitized_kingsize = isset($_POST['kingsize']) ? 1 : 0;

       if(empty($sanitizedName)){
        header("Location: $base_url/views/new_home.php?error=empty-name");
        exit();
    } else if (empty($sanitizedLocation)){
        header("Location: $base_url/views/new_home.php?error=empty-location");
        exit();
    } else if (empty($sanitizedSize)){
        header("Location: $base_url/views/new_home.php?error=empty-size");
        exit();
    } else if (empty($sanitizedBedrooms)){
        header("Location: $base_url/views/new_home.php?error=empty-bedrooms");
        exit();
    } else if (empty($sanitizedPrice)){
        header("Location: $base_url/views/new_home.php?error=empty-price");
        exit();
    } else if (empty($sanitizedDescription)){
        header("Location: $base_url/views/new_home.php?error=empty-description");
        exit();
    } else if (empty($sanitizedBathrooms)){
        header("Location: $base_url/views/new_home.php?error=empty-bathrooms");
        exit();
    } else if (empty($sanitizedWords)){
        header("Location: $base_url/views/new_home.php?error=empty-words");
        exit();
    } else if (empty($sanitizedLink)){
        header("Location: $base_url/views/new_home.php?error=empty-link");
        exit();
    };

$sql = "INSERT INTO homes (name, location, size, bedrooms, price, user_id, description, bathrooms, breakfast, petsitting, bike_rental, car_rental, laundry_service, pool, billiard, fitness, sauna, kingsizebed, link) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_stmt_init($conn);

if(!mysqli_stmt_prepare($stmt, $sql)){
    //add redirect with error handling message for user
    die(mysqli_error($conn));
};

mysqli_stmt_bind_param($stmt, "ssiidisiiiiiiiiiiis", $sanitizedName, $sanitizedLocation, $sanitizedSize,$sanitizedBedrooms, $sanitizedPrice, $sanitized_user_id, $sanitizedDescription, $sanitizedBathrooms, $sanitized_breakfast, $sanitized_petsitting, $sanitized_bike_rental, $sanitized_car_rental, $sanitized_laundry_service, $sanitized_pool, $sanitized_billiard, $sanitized_fitness, $sanitized_sauna, $sanitized_kingsize, $sanitizedLink);

if (!mysqli_stmt_execute($stmt)) {
                die("Fehler beim INSERT: " . mysqli_stmt_error($stmt));
            }
// use the current home_id
$home_id = mysqli_insert_id($conn);
mysqli_stmt_close($stmt);

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



$sql = "INSERT INTO home_keywords (home_id, words) VALUES (?, ?)";
$stmt = mysqli_stmt_init($conn);
if(!mysqli_stmt_prepare($stmt, $sql)){
    die(mysqli_error($conn));
};
mysqli_stmt_bind_param($stmt, "is", $home_id, $sanitizedWords);

if (!mysqli_stmt_execute($stmt)) {
                die("Fehler beim INSERT: " . mysqli_stmt_error($stmt));
            }
mysqli_stmt_close($stmt);

header('Location: ../views/homes.php?error=none-home-created');
exit();
};