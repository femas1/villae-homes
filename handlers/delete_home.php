<?php

include dirname(__DIR__) . '/config/db.php';

if (isset($_POST['home_id'])) {
    $id = $_POST['home_id'];

    // First, delete from pictures where home_id = ?
    $sql_pics = "DELETE FROM pictures WHERE home_id = ?";
    $stmt_pics = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt_pics, $sql_pics)) {
        die("Fehler beim Löschen der Bilder: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt_pics, "i", $id);
    mysqli_stmt_execute($stmt_pics);
    mysqli_stmt_close($stmt_pics);

    
    //Then delete the keywords
    $sql_words = "DELETE FROM home_keywords WHERE home_id = ?";
    $stmt_words = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt_words, $sql_words)) {
        die("Fehler beim Löschen der Keywörter: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt_words, "i", $id);
    mysqli_stmt_execute($stmt_words);
    mysqli_stmt_close($stmt_words);

    //Then read Booking_id
    $sql = "SELECT * FROM booking_items WHERE home_id = ?;";
    $stmt_read = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt_read, $sql)){
        die("sql error");
    };

    mysqli_stmt_bind_param($stmt_read, "i", $id);
    mysqli_stmt_execute($stmt_read);
    $result = mysqli_stmt_get_result($stmt_read);
    while ($booking = mysqli_fetch_assoc($result)) {
    $booking_ids[] = $booking['booking_id'];
    }
    mysqli_stmt_close($stmt_read);

    //Then delete the Booking_items
    $sql_booking = "DELETE FROM booking_items WHERE home_id = ?";
    $stmt_booking = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt_booking, $sql_booking)) {
        die("Fehler beim Löschen der Buchungens_Items: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt_booking, "i", $id);
    mysqli_stmt_execute($stmt_booking);
    mysqli_stmt_close($stmt_booking);

    //Then delete the Bookings with saved Booking_id
   foreach ($booking_ids as $booking_id) {
    $sql_booking_items = "DELETE FROM bookings WHERE booking_id = ?";
    $stmt_delete = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt_delete, $sql_booking_items)) {
        die("Fehler beim Löschen der Buchungen: " . mysqli_error($conn));
    }
    
    mysqli_stmt_bind_param($stmt_delete, "i", $booking_id);
    mysqli_stmt_execute($stmt_delete);
    mysqli_stmt_close($stmt_delete);
   }

    // Then, delete the home itself
    $sql_home = "DELETE FROM homes WHERE home_id = ?";
    $stmt_home = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt_home, $sql_home)) {
        die("Fehler beim Löschen des Hauses: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt_home, "i", $id);
    mysqli_stmt_execute($stmt_home);
    mysqli_stmt_close($stmt_home);

    // Redirect after success
    header('Location: /villae-homes/views/homes.php?error=none-home-deleted');
    exit();
}
?>