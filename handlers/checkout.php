<?php
include '../config/db.php';
session_start();

// Ensure cart exists and has items
if (!isset($_SESSION['cart']) || empty($_SESSION['cart']['items'])) {
    header("Location: ../views/cart.php");
    exit();
}

// Static prices for extras
$extra_prices = [
    'breakfast' => 18,
    'petsitting' => 30,
    'bike_rental' => 15,
    'car_rental' => 50,
    'laundry_service' => 25
];

$extra_translations = [
    'breakfast' => 'Frühstück',
    'petsitting' => 'Haustierbetreuung',
    'bike_rental' => 'Fahrradverleih',
    'car_rental' => 'Autovermietung',
    'laundry_service' => 'Wäscheservice'
];

$user_id = $_SESSION['cart']['items'][0]['user_id'];

// Recalculate total price (including extras)
$total_price = 0;
foreach ($_SESSION['cart']['items'] as &$item) {
    $base_price = $item['base_price']; // Use the base price without extras
    $extras_price = 0;

    // Calculate number of nights
    $check_in_date = new DateTime($item['check_in']);
    $check_out_date = new DateTime($item['check_out']);
    $nights = $check_out_date->diff($check_in_date)->days;

    foreach ($extra_prices as $extra => $extra_cost) {
        if (!empty($item[$extra])) {
            $extras_price += $extra_cost * $nights; // Multiply by the number of nights
        }
    }

    $item['price'] = $base_price + $extras_price; // Calculate the final price
    $total_price += $item['price'];
}
unset($item); // Break reference

// 1. Insert into bookings table
$insert_booking_sql = "
    INSERT INTO bookings (user_id, total_price)
    VALUES ('$user_id', '$total_price')
";

if (!mysqli_query($conn, $insert_booking_sql)) {
    die("Fehler bei Buchung: " . mysqli_error($conn));
}

$booking_id = mysqli_insert_id($conn);

// 2. Insert each item into booking_items table including extras
foreach ($_SESSION['cart']['items'] as $item) {
    $home_id = mysqli_real_escape_string($conn, $item['home_id']);
    $price = mysqli_real_escape_string($conn, $item['price']);
    $check_in = mysqli_real_escape_string($conn, $item['check_in']);
    $check_out = mysqli_real_escape_string($conn, $item['check_out']);

    // Check if dates are still available
    $availability_sql = "
        SELECT COUNT(*) AS booked_count
        FROM booking_items
        WHERE home_id = '$home_id'
        AND (
            ('$check_in' < check_out)
            AND ('$check_out' > check_in)
        )
    ";

    $result = mysqli_query($conn, $availability_sql);
    $data = mysqli_fetch_assoc($result);

    if ($data['booked_count'] > 0) {
        // Rollback the booking if any item is no longer available
        mysqli_query($conn, "DELETE FROM bookings WHERE id = '$booking_id'");
        header("Location: ../views/cart.php?error=home-no-longer-available");
        exit();
    }

    // Extras (cast to int for safety)
    $breakfast = isset($item['breakfast']) ? (int)$item['breakfast'] : 0;
    $petsitting = isset($item['petsitting']) ? (int)$item['petsitting'] : 0;
    $bike_rental = isset($item['bike_rental']) ? (int)$item['bike_rental'] : 0;
    $car_rental = isset($item['car_rental']) ? (int)$item['car_rental'] : 0;
    $laundry_service = isset($item['laundry_service']) ? (int)$item['laundry_service'] : 0;

    $insert_item_sql = "
        INSERT INTO booking_items (
            booking_id, home_id, price, check_in, check_out,
            breakfast, petsitting, bike_rental, car_rental, laundry_service
        ) VALUES (
            '$booking_id', '$home_id', '$price', '$check_in', '$check_out',
            '$breakfast', '$petsitting', '$bike_rental', '$car_rental', '$laundry_service'
        )
    ";

    if (!mysqli_query($conn, $insert_item_sql)) {
        die("Fehler beim Einfügen eines Hauses: " . mysqli_error($conn));
    }
}

// Clear cart
unset($_SESSION['cart']);

// Redirect to success page
header("Location: ../views/booking_success.php?booking_id=$booking_id");
exit();
?>