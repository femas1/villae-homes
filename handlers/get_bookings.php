<?php
include_once dirname(__DIR__) . "/config/db.php";

$bookings = []; // initialize to avoid undefined variable

if (isset($_SESSION['user_session_id'])) {
    $user_id = $_SESSION['user_session_id'];

    $sql = "
    SELECT b.*, bi.*, h.name, h.location, h.size, h.bedrooms, h.price AS home_price, 
           p.pic, p.main
    FROM bookings AS b
    JOIN booking_items AS bi ON b.booking_id = bi.booking_id
    JOIN homes AS h ON bi.home_id = h.home_id
    LEFT JOIN pictures AS p ON h.home_id = p.home_id
    WHERE b.user_id = ?
    ORDER BY b.booking_id, p.main DESC
";

    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $raw_results = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $bookings = [];

    foreach ($raw_results as $row) {
        $booking_id = $row['booking_id'];

        if (!isset($bookings[$booking_id])) {
            $bookings[$booking_id] = [
                'booking_id' => $row['booking_id'],
                'total_price' => $row['total_price'],
                'items' => [],
            ];
        }

        $bookings[$booking_id]['items'][] = [
            'home_id' => $row['home_id'],
            'check_in' => $row['check_in'],
            'check_out' => $row['check_out'],
            'price' => $row['price'],
            'breakfast' => $row['breakfast'],
            'petsitting' => $row['petsitting'],
            'bike_rental' => $row['bike_rental'],
            'car_rental' => $row['car_rental'],
            'laundry_service' => $row['laundry_service'],
            // Additional home info:
            'name' => $row['name'],
            'location' => $row['location'],
            'size' => $row['size'],
            'bedrooms' => $row['bedrooms'],
            'home_price' => $row['home_price'],
            'pic' => $row['pic'],
        ];
    }

    mysqli_stmt_close($stmt);
}
?>
