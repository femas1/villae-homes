<?php include dirname(__DIR__) . '/config/config.php';?>
<?php include '../handlers/get_user_detail.php';?>

<!DOCTYPE html>
<html lang="de">
 <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <title>Mein Konto</title>
    <link rel="stylesheet" href="../style.css" />
    <link rel="shortcut icon" href="../assets/favicon/favicon-96x96.png" />
  </head>

  <body>
    
  <?php include dirname(__DIR__) . '/partials/nav.php';?>

    <main class="main-container main-container-user-detail">
        <div class="user-detail">
            <?php
            if (!$user['is_host']) {
                echo "<h3>Gast: </h3><p>" . htmlspecialchars($user['first_name'] . " " . $user['last_name']) . "</p>";
            } else {
                echo "<h3>Gastgeber: </h3><p>" . htmlspecialchars($user['first_name'] . " " . $user['last_name']) . "</p>";
            }
            ?>
            
                <h3>Kontakt:</h3> <p><?php echo $user['email'];?></p>
                <?php if(isset($_SESSION['user_session_id']) && $_SESSION['user_session_id'] == $_GET['user_id']) {
                    echo "<div class='user-detail-buttons'></div>";
                    echo "<a href='./edit_user.php?user_id=" . $user['user_id'] . "' class='btn btn-primary'>Passwort ändern</a>";
                    echo "<a href='bookings.php' class='btn btn-primary'>Meine Buchungen</a>";
                    echo "</div>";
                    }
                    ?>
        </div>
            </main>
<?php include dirname(__DIR__) . '/partials/footer.php'; ?>
</body>
</html>