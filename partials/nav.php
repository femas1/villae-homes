<?php session_start(); ?>
<?php include dirname(__DIR__) . '/config/config.php'; ?>

<nav class="nav">
    <a href=<?php echo $base_url; ?>><img class="logo-img" src=<?php echo $base_url . "/assets/logo-round.png"; ?> alt="villae-logo"/></a>

    <!-- Only show Mein Konto and welcome text if there is a session -->
    <?php if (isset($_SESSION['user_session_id'])) {
        $user_id = $_SESSION['user_session_id'];
        echo "<span id='welcome-text' class='nav_link link'>Hallo, <strong>" . $_SESSION['user_session_name'] . "🥳" ."</strong></span>";
        echo "<a href='$base_url" . "views/user_detail.php?user_id=$user_id' class='nav_link link'> Mein Konto </a>";
        // If the user is a owner too, a new home button is shown
        if ($_SESSION['user_role']) {
            echo "<a href='$base_url" . "views/new_home.php' class='btn btn-primary'> Neues Ferienhaus </a>";
        }
        // If the user is logged in a logout button is shown
        echo '<form id="logout-form" action="' . $base_url . 'includes/logout.inc.php" method="POST"><button class="btn btn-primary btn-warning" type="submit" name="submit">Ausloggen</button></form>';
        // Options for unlogged users
    } else {
        echo '<a href="' . $base_url . 'views/signup.php" class="nav_link">Als Vermieter registrieren</a>';
        echo '<a href="' . $base_url . 'views/signup_guest.php" class="nav_link">Als Gast registrieren</a>';
        echo '<a href="' . $base_url . 'views/login.php" class="nav_link">Einloggen</a>';
    } ?>
        <a href="<?php echo $base_url . 'views/cart.php'; ?>" class="nav_link link">Warenkorb</a>
</nav>
