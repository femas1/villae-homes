<?php
include '../config/db.php';
session_start();

// Initialize cart if needed
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [
        'items' => [],
        'total' => 0
    ];
}

// Get form data
$home_id    = mysqli_real_escape_string($conn, $_POST['home_id']);
$user_id    = mysqli_real_escape_string($conn, $_POST['user_id']);
$host_id    = mysqli_real_escape_string($conn, $_POST['host_id']);
$check_in   = mysqli_real_escape_string($conn, $_POST['check_in']);
$check_out  = mysqli_real_escape_string($conn, $_POST['check_out']);
$home_price = floatval($_POST['home_price']); // convert to float

// Validate dates are not in the past
$today = date('Y-m-d');
if ($check_in < $today || $check_out < $today) {
    header("Location: ../views/home_detail.php?error=past-dates&home_id=$home_id&owner_id=$host_id");
    exit();
}

// Validate check-out is after check-in
if ($check_out <= $check_in) {
    header("Location: ../views/home_detail.php?error=invalid-dates&home_id=$home_id&owner_id=$host_id");
    exit();
}

// Calculate number of nights
$check_in_date = new DateTime($check_in);
$check_out_date = new DateTime($check_out);
$nights = $check_out_date->diff($check_in_date)->days;

// Extras as booleans
$breakfast    = isset($_POST['breakfast']) ? 1 : 0;
$pet_sitting   = isset($_POST['petsitting']) ? 1 : 0;
$bike_rental = isset($_POST['bike_rental']) ? 1 : 0;
$car_rental    = isset($_POST['car_rental']) ? 1 : 0;
$laundry_service    = isset($_POST['laundry_service']) ? 1 : 0;

// Static extra prices
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

// Calculate total extra price
$extras_total = 0;
$selected_extras = [];

foreach (['breakfast', 'petsitting', 'bike_rental', 'car_rental', 'laundry_service'] as $extra) {
    $selected = isset($_POST[$extra]) ? 1 : 0;
    $selected_extras[$extra] = $selected;
    if ($selected) {
        $extras_total += $extra_prices[$extra] * $nights; // Multiply by the number of nights
    }
}

// Calculate final total price
$total_price = ($home_price * $nights) + $extras_total;

// First check availability in the database
$availability_sql = "
    SELECT COUNT(*) AS booked_count 
    FROM booking_items AS bi
    WHERE bi.home_id = '$home_id'
    AND (
        ('$check_in' < bi.check_out)  
        AND ('$check_out' > bi.check_in)  
    )
";

$result = mysqli_query($conn, $availability_sql);
$data = mysqli_fetch_assoc($result);

if ($data['booked_count'] > 0) {
    header("Location: ../views/home_detail.php?error=home-not-available&home_id=$home_id&owner_id=$host_id");
    exit();
}

// Check for conflicts in cart
foreach ($_SESSION['cart']['items'] as $item) {
    if ($item['home_id'] == $home_id) {
        // Check for date overlap (allowing same-day transitions)
        if (($check_in < $item['check_out']) && ($check_out > $item['check_in'])) {
            header("Location: ../views/home_detail.php?error=home-already-in-cart&home_id=$home_id&owner_id=$host_id");
            exit();
        }
    }
}

// Add to cart
$new_item = array_merge([
    'home_id' => $home_id,
    'user_id' => $user_id,
    'host_id' => $host_id,
    'check_in' => $check_in,
    'check_out' => $check_out,
    'base_price' => $home_price * $nights,
    'extras_total' => $extras_total,
    'price' => $total_price,
    'nights' => $nights
], $selected_extras);


$_SESSION['cart']['items'][] = $new_item;
$_SESSION['cart']['total'] += $total_price;

header("Location: ../views/cart.php");
exit();
?>