<!DOCTYPE html>
<html lang="de">
 <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <title>Anmelden</title>
    <link rel="stylesheet" href="../style.css" />
    <link rel="shortcut icon" href="../assets/favicon/favicon-96x96.png" />
  </head>
  <body>
    <?php include '../partials/nav.php'?>
    <main class="main-container-login">
    <form class="form" action="../includes/login.inc.php" method="POST">
        <h2>Einloggen</h2>
                <div class="email-input">
                    <label for="email">E-Mail-Adresse</label>
                    <input type="text" name="email">
                </div>
                <div class="password-input">
                    <label for="password">Passwort</label>
                    <input type="password" name="password">
                </div>

                <div class="buttons-column"><button type="submit" name="submit" class="btn btn-primary">Einloggen</button></div>

                <h4>Noch kein Konto?</h4>
      <div class="buttons">
        <a href="signup.php" class="btn btn-primary">Als Vermieter registrieren.</a>
        <a href="signup_guest.php" class="btn btn-primary">Als Gast registrieren.</a>
      </div>
                <?php if(isset($_GET['error'])){
                  if($_GET['error'] === 'emptyinput') {
                    echo "<h4 class='warning btn form-btn'>Bitte alle Felder ausfüllen.</h4>";
                  }
                  else if($_GET['error'] === 'wronglogin' || $_GET['error'] === 'wrongemail') {
                    echo "<h4 class='warning btn form-btn'>E-Mail stimmt nicht.</h4>";
                  } else if ($_GET['error'] === 'wrongpassword') {
                    echo "<h4 class='warning btn form-btn>Passwort stimmt nicht.</h4>";
                  } if($_GET['error'] === 'none'){
                echo "<h4 class='success btn form-btn btn-primary'>Registrierung abgeschlossen. Du kannst dich jetzt einloggen.</h4>";
            }
                }?>
            </form>
    </main>
    <?php include '../partials/footer.php'?>
  </body>
</html>
