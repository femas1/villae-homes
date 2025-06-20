<?php

include '../config/db.php';

if(isset($_POST['submit'])){

// 1. sanitize

$sanitized_id = filter_var($_POST['home_id'], FILTER_SANITIZE_NUMBER_INT);
$sanitized_name = filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);
$sanitized_location = filter_var($_POST['location'], FILTER_SANITIZE_SPECIAL_CHARS);
$sanitized_size = filter_var($_POST['size'], FILTER_SANITIZE_NUMBER_INT);
$sanitized_bedrooms = filter_var($_POST['bedrooms'], FILTER_SANITIZE_NUMBER_INT);
$sanitized_bathrooms = filter_var($_POST['bathrooms'], FILTER_SANITIZE_NUMBER_INT);
$sanitized_description = filter_var($_POST['description'], FILTER_SANITIZE_SPECIAL_CHARS);
$sanitized_price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT);
$sanitized_user_id = filter_var($_POST['user_id'], FILTER_SANITIZE_NUMBER_FLOAT);
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
$sanitizedLink = filter_var(trim($_POST['link']), FILTER_SANITIZE_SPECIAL_CHARS);

// 2. validate
if(empty($sanitized_name)){
    die("Bitte einen Namen eingeben.");
} else if(empty($sanitized_location)){
    die("Bitte einen Ort eingeben.");
} else if(empty($sanitized_size)){
    die("Bitte einen Wert für die Fläche eingeben.");
} else if(empty($sanitized_bedrooms)){
    die("Bitte einen Wert für Schlafzimmer eingeben.");
} else if(empty($sanitized_price)){
    die("Bitte einen Preis eingeben");
} else if(empty($sanitized_bathrooms)){
    die("Bitte einen Wert für Badezimmer eingeben.");
} else if (empty($sanitizedLink)){
    die("Bitte Lageplan eingeben.");    
} else if(empty($sanitized_description)){
    die("Bitte eine Beschreibung eingeben.");
};
// 3. sql string with placeholders

$sql = "UPDATE homes SET name = ?, location = ?, size = ?, bedrooms = ?, price = ?, bathrooms = ?, description = ?, breakfast = ?, petsitting = ?, bike_rental = ?, car_rental = ?, laundry_service = ?, pool = ?, billiard = ?, fitness = ?, sauna = ?, kingsizebed = ?, link = ? WHERE home_id = ?;";

// 4. initialize

$stmt = mysqli_stmt_init($conn);

// 5. prepare to check syntax

if(!mysqli_stmt_prepare($stmt, $sql)){
    die("check your query");
};
// 6. binding to connect placeholders to values

mysqli_stmt_bind_param($stmt, "ssiiiisiiiiiiiiiisi", $sanitized_name, $sanitized_location, $sanitized_size, $sanitized_bedrooms, $sanitized_price, $sanitized_bathrooms, $sanitized_description, $sanitized_breakfast, $sanitized_petsitting, $sanitized_bike_rental, $sanitized_car_rental, $sanitized_laundry_service,$sanitized_pool, $sanitized_billiard, $sanitized_fitness, $sanitized_sauna, $sanitized_kingsize, $sanitizedLink, $sanitized_id);
// 7. execute and redirect

mysqli_stmt_execute($stmt);
header("Location: /villae-homes/views/home_detail.php?home_id=$sanitized_id&owner_id=$sanitized_user_id");
}