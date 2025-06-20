<?php
include_once dirname(__DIR__) . "/partials/nav.php";
include_once dirname(__DIR__) . "/handlers/get_bookings.php";

if (!isset($_SESSION['user_session_id'])){
    header('Location: ../index.php');
    exit();
}

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
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Meine Buchungen</title>
    <link rel="stylesheet" href="../style.css" />
    <link rel="shortcut icon" href="../assets/favicon/favicon.ico" />
</head>
<body>

<main class="main-container">
   <a href="<?php echo './user_detail.php?user_id=' . $_SESSION['user_session_id']; ?>" class="btn btn-primary form-btn">Zurück zu meinem Konto</a>
<?php if (empty($bookings)): ?>
    <p>Keine Häuser gebucht, finde hier dein Traumhaus.</p>
    <a href="homes.php" class="btn btn-primary">Zur Häuserübersicht</a>
<?php else: ?>
    <h1>Ihre Buchungen</h1>
    <?php foreach ($bookings as $booking): ?>
        <section class="booking-section">
            <h2>Buchung #<?= htmlspecialchars($booking['booking_id']) ?></h2>
            <?php
            $total_booking_price = 0;
            foreach ($booking['items'] as $item) {
                $check_in = new DateTime($item['check_in']);
                $check_out = new DateTime($item['check_out']);
                $nights = $check_out->diff($check_in)->days;

                $extras_total = 0;
                foreach ($extra_prices as $extra => $extra_price) {
                    if (!empty($item[$extra])) {
                        $extras_total += $extra_price * $nights;
                    }
                }

                $base_price = ($item['home_price'] * $nights);
                $total_item_price = $base_price + $extras_total;
                $total_booking_price += $total_item_price;
            }
            ?>
            <p><strong>Gesamtpreis der Buchung:</strong> <?= number_format($booking['total_price'], 2, ',', '.') ?> €</p>

           <?php foreach ($booking['items'] as $index => $item): ?>
                <?php 
                // Skip if this home was already displayed in this booking
                $home_displayed = false;
                for ($i = 0; $i < $index; $i++) {
                    if ($booking['items'][$i]['home_id'] == $item['home_id']) {
                        $home_displayed = true;
                        break;
                    }
                }
                if ($home_displayed) continue;
                ?>
                
                <div class="booking-item">
                    <div class="booking-home-thumbnail">
                        <?php 
                        if (!empty($item['pic'])) {
                            if (base64_encode(base64_decode($item['pic'])) === $item['pic']) {
                                echo '<img src="data:image/jpeg;base64,'.$item['pic'].'" alt="Hausbild" width="200">';
                            } else {
                                echo '<img src="data:image/jpeg;base64,'.base64_encode($item['pic']).'" alt="Hausbild" width="200">';
                            }
                        } else {
                            echo '<img src="/villae-homes/assets/sample-home.jpg" alt="Kein Bild verfügbar" width="200">';
                        }
                        ?>
                    </div>
                    
                    <div class="home_information">
                        <h3>
                            <a href="./home_detail.php?home_id=<?= $item['home_id'] ?>&owner_id=<?= $_SESSION['user_session_id'] ?>" class="house-link">
                                <?= htmlspecialchars($item['name']) ?>
                            </a>
                        </h3>
                        <p class="home_information_list_item"><strong>Ort:</strong> <?= htmlspecialchars($item['location']) ?></p>
                        <p class="home_information_list_item"><strong>Größe:</strong> <?= htmlspecialchars($item['size']) ?> m²</p>
                        <p class="home_information_list_item"><strong>Schlafzimmer:</strong> <?= htmlspecialchars($item['bedrooms']) ?></p>
                        <p class="home_information_list_item"><strong>Preis pro Nacht:</strong> <?= number_format($item['home_price'], 2, ',', '.') ?> €</p>

                        <p class="home_information_list_item">
                        🗓️ <strong>Check-In:</strong>
                        <?php
                        $checkInDate = DateTime::createFromFormat('Y-m-d', $item['check_in']);
                        echo $checkInDate ? $checkInDate->format('d/m/Y') : htmlspecialchars($item['check_in']);
                        ?>
                    </p>
                    <p class="home_information_list_item">
                        🗓️ <strong>Check-Out:</strong>
                        <?php
                        $checkOutDate = DateTime::createFromFormat('Y-m-d', $item['check_out']);
                        echo $checkOutDate ? $checkOutDate->format('d/m/Y') : htmlspecialchars($item['check_out']);
                        ?>
                    </p>


                        <?php
                        $check_in = new DateTime($item['check_in']);
                        $check_out = new DateTime($item['check_out']);
                        $nights = $check_out->diff($check_in)->days;

                        $extras_total = 0;
                        foreach ($extra_prices as $extra => $extra_price) {
                            if (!empty($item[$extra])) {
                                $extras_total += $extra_price * $nights;
                            }
                        }

                        $base_price = ($item['home_price'] * $nights);
                        ?>

                        <p><strong>Grundpreis (<?= $nights ?> Nächte):</strong> <?= number_format($base_price, 2, ',', '.') ?> €</p>

                        <?php if ($extras_total > 0): ?>
                            <div class="extras">
                                <strong>Inklusive:</strong>
                                <ul class="home_information_list">
                                    <?php foreach ($extra_prices as $extra => $extra_price): ?>
                                        <?php if (!empty($item[$extra])): ?>
                                            <li class="home_information_list_item">
                                                <?= $extra_translations[$extra] ?>
                                                (+ <?= number_format($extra_price, 2, ',', '.') ?> €/Nacht,
                                                Gesamt: <?= number_format($extra_price * $nights, 2, ',', '.') ?> €)
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                                <p><strong>Extras Gesamt:</strong> <?= number_format($extras_total, 2, ',', '.') ?> €</p>
                            </div>
                        <?php endif; ?>


                        <p class="price-highlight"><strong>Gesamtpreis für dieses Haus:</strong> <?= number_format($base_price + $extras_total, 2, ',', '.') ?> €</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
    <?php endforeach; ?>
<?php endif; ?>
</main>

<?php include_once dirname(__DIR__) . "/partials/footer.php"; ?>
</body>
</html>