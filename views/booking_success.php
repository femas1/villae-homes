<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mein Konto</title>
    <link rel="stylesheet" href="../style.css" />
    <link rel="shortcut icon" href="../assets/favicon/favicon.ico" />
</head>
<body>
    <?php include dirname(__DIR__) . '/partials/nav.php';?>

    <main class="main-container main-container-cart">
        <?php if(isset($_SESSION['user_session_id'])): ?>
        <h3>Deine Buchung war erfolgreich! Buchungsnummer: #<?= htmlspecialchars($_GET['booking_id']) ?></h3>
            <a href="bookings.php" class="btn btn-primary form-btn">Zu meiner Buchungsübersicht</a>
            <?php else: header('Location: ../index.php')?>
            <?php endif; ?>
    </main>
    <?php include dirname(__DIR__) . '/partials/footer.php'; ?>
</body>
</html>