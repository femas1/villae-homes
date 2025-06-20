<?php
// Prices for extras
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

// Ensure cart structure exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [
        'items' => [],
        'total' => 0
    ];
}

// Recalculate total to ensure accuracy
$_SESSION['cart']['total'] = 0;
foreach ($_SESSION['cart']['items'] as $item) {
    $_SESSION['cart']['total'] += $item['price'];
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Warenkorb</title>
    <link rel="stylesheet" href="../style.css" />
    <link rel="shortcut icon" href="../assets/favicon/favicon-96x96.png" />
</head>
<body>
<?php include '../partials/nav.php'?>

<main class="main-container main-container-cart">
    <div class="cart-container">
        <?php if(empty($_SESSION['cart']['items'])): ?>
            <div class="empty-cart">
                <h1>Dein Warenkorb ist leer</h1>
                <p>Finde hier dein Traumhaus:</p>
                <a href="../views/homes.php" class="btn btn-primary">Zur Häuserübersicht</a>
            </div>
        <?php else: ?>
            <h1>Ihre Auswahl</h1>

            <div class="cart-items">
                <?php foreach($_SESSION['cart']['items'] as $item): ?>
                    <div class="cart-item">
                        <div class="cart-item-header">
                            <h3>Haus #<?= htmlspecialchars($item['home_id']) ?></h3>
                        </div>

                    <div class="cart-item-details">
                        <div class="detail-group">
                            <strong>Zeitraum:</strong>
                            <p>
                                <?php
                                $checkInDate = DateTime::createFromFormat('Y-m-d', $item['check_in']);
                                $checkOutDate = DateTime::createFromFormat('Y-m-d', $item['check_out']);
                                echo $checkInDate ? $checkInDate->format('d/m/Y') : htmlspecialchars($item['check_in']);
                                ?>
                                bis
                                <?php
                                echo $checkOutDate ? $checkOutDate->format('d/m/Y') : htmlspecialchars($item['check_out']);
                                ?>
                            </p>
                        </div>

                            <?php
                            $check_in_date = new DateTime($item['check_in']);
                            $check_out_date = new DateTime($item['check_out']);
                            $nights = $check_out_date->diff($check_in_date)->days;

                            $extras_total = 0;
                            foreach ($extra_prices as $extra => $price) {
                                if (!empty($item[$extra])) {
                                    $extras_total += $price * $nights; // Multiply by the number of nights
                                }
                            }
                            $base_price = $item['price'] - $extras_total;
                            ?>

                            <div class="detail-group">
                                <strong>Grundpreis (<?= $nights ?> Nächte):</strong>
                                <p><?= number_format($base_price, 2, ',', '.') ?> €</p>
                            </div>

                                <?php if ($extras_total > 0): ?>
                                <div class="extras detail-group">
                                    <strong>Inklusive:</strong>
                                    <ul class="extras-list">
                                        <?php foreach ($extra_prices as $extra => $price): ?>
                                            <?php if (!empty($item[$extra])): ?>
                                                <li>
                                                    <?= $extra_translations[$extra] ?>
                                                    (+ <?= number_format($price, 2, ',', '.') ?> €/Nacht,
                                                    Gesamt: <?= number_format($price * $nights, 2, ',', '.') ?> €)
                                                </li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                    <p><strong>Gesamt Extras:</strong> <?= number_format($extras_total, 2, ',', '.') ?> €</p>
                                </div>
                            <?php endif; ?>

                            <div class="detail-group">
                                <strong>Gesamtpreis:</strong>
                                <p><?= number_format($item['price'], 2, ',', '.') ?> €</p>
                            </div>
                        </div>

                        <form action="../handlers/remove_from_cart.php" method="post" class="cart-item-actions">
                            <input type="hidden" name="home_id" value="<?= $item['home_id'] ?>">
                            <button type="submit" class="btn btn-warning">Entfernen</button>
                         </form>

                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <div class="summary-content">
                    <h2>Gesamtsumme: <?= number_format($_SESSION['cart']['total'], 2, ',', '.') ?> €</h2>
                    <form action="../handlers/checkout.php" method="post">
                        <input type="hidden" name="total_price" value="<?= $_SESSION['cart']['total'] ?>">
                        <button type="submit" class="btn btn-primary">Jetzt buchen</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include '../partials/footer.php'?>
</body>
</html>