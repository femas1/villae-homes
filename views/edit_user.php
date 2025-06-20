<?php include dirname(__DIR__) . '/config/config.php';?>
<?php include '../handlers/get_user_detail.php';?>
<!DOCTYPE html>
<html lang="de">
 <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="../style.css" />
    <link rel="shortcut icon" href="../assets/favicon/favicon-96x96.png" />
    <title>Mein Konto</title>
  </head>

  <body>
  <?php include dirname(__DIR__) . '/partials/nav.php';?>
  
  <main class="main-container main-container-user-detail">
    
      <?php if (isset($_SESSION['user_session_id']) && $_SESSION['user_session_id'] == $_GET['user_id']) : ?>
        
        <form action="../handlers/edit_password.php" method="POST" class="form">
          <h3>Passwort ändern</h3>
          <div class="old-password-input">
            <label for="old-password">Aktuelles Passwort:</label>
            <input type="password" id="old-password" name="old-password" placeholder="Altes Passwort eingeben...">
          </div>

          <div class="new-password-input">
            <label for="new-password">Neues Passwort:</label>
            <input type="password" id="new-password" name="new-password" placeholder="Neues Passwort eingeben...">
          </div>

          <div class="new-password-input-repeat">
            <label for="new-password-repeat">Neues Passwort wiederholen:</label>
            <input type="password" id="new-password-repeat" name="new-password-repeat" placeholder="Neues Passwort wiederholen...">
          </div>

          <input type="hidden" name="user_id" value="<?php echo $_GET['user_id']; ?>">

          <button type="submit" name="submit" class="form-btn btn-primary">Passwort ändern</button>
        </form>

        <?php
        if (isset($_GET['error'])) {
          $messages = [
            'emptyinput' => 'Bitte alle Felder ausfüllen!',
            'wrong-password' => 'Altes Passwort stimmt nicht.',
            'pwddontmatch' => 'Passwörter stimmen nicht überein.',
            'generic-error' => 'Unerwarteter Fehler, wenden Sie sich bitte an support@peakscape.de.',
            'pwdchange-error' => 'Passwort konnte nicht geändert werden, wenden Sie sich bitte an support@peakscape.de.',
            'none' => 'Passwort erfolgreich aktualisiert.'
          ];
          $type = $_GET['error'] === 'none' ? 'success' : 'warning';
          echo "<h4 class='{$type} btn'>{$messages[$_GET['error']]}</h4>";
        }
        ?>

      <?php else:
        header("Location: ./user_detail.php?user_id=" . $_GET['user_id']);
        exit();
      endif; ?>
   
  </main>

<?php include dirname(__DIR__) . '/partials/footer.php'; ?>
</body>
</html>