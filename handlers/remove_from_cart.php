<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['home_id'])) {
        $home_id = $_POST['home_id'];

        // Check if the cart exists and if the item is in the cart
        if (isset($_SESSION['cart']) && isset($_SESSION['cart']['items'])) {
            // Loop through the cart items to find the item with the matching home_id
            foreach ($_SESSION['cart']['items'] as $key => $item) {
                if ($item['home_id'] == $home_id) {
                    // Remove the item from the cart
                    unset($_SESSION['cart']['items'][$key]);

                    // Re-index the array to avoid gaps in the array keys
                    $_SESSION['cart']['items'] = array_values($_SESSION['cart']['items']);

                    // Recalculate the total price
                    $_SESSION['cart']['total'] = 0;
                    foreach ($_SESSION['cart']['items'] as $item) {
                        $_SESSION['cart']['total'] += $item['price'];
                    }

                    break;
                }
            }
        }
    }

    // Redirect back to the cart page
    header('Location: ../views/cart.php');
    exit();
} else {
    // If the request method is not POST, redirect to the cart page
    header('Location: ../views/cart.php');
    exit();
}
?>
