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
    <main class="main-container-signup">
    <form class="form" action="../includes/signup.inc.php" method="POST">
        <div class="buttons-column"><a href="login.php" class="btn btn-primary">Schon dabei? Zum Login.</a></div>
        <h2>Als Vermieter registrieren</h2>
                <div class="first-name-input">
                    <label for="first_name">Vorname</label>
                    <input type="text" name="first_name">
                </div>
                <div class="last-name-input">
                    <label for="last_name">Nachname</label>
                    <input type="text" name="last_name">
                </div>
                <div class="email-input">
                    <label for="email">E-Mail-Adresse</label>
                    <input type="email" name="email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" 
                    title="Gültige E-Mail eingeben">
                </div>
                <div class="password-input">
                    <label for="password">Passwort</label>
                    <input type="password" name="password">
                </div>
                <div class="repeat-password-input">
                    <label for="repeat-password">Passwort wiederholen</label>
                    <input type="password" name="repeat-password">
                </div>
                <input type="hidden" name="user_id">
                <input type="hidden" name="is_host" value="1">
               <div class="buttons-column">
                    <button type="submit" name="submit" class="btn btn-primary">Weiter</button>
                    <a href="signup_guest.php" class="nav_link">Willst Du ein Haus buchen? Als Gast registrieren.</a>
                </div>
                 <?php 
        if(isset($_GET['error'])){
            if($_GET['error'] === 'emptyinput'){
                echo "<h4 class='warning btn form-btn'>Bitte alle Felder ausfüllen.</h4>";
            }
            if($_GET['error'] === 'invalidmail'){
                echo "<h4 class='warning btn form-btn'>E-Mail-Adresse ungültig.</h4>";
            }
            if($_GET['error'] === 'pwdmatcherror'){
                echo "<h4 class='warning btn form-btn'>Passwörter stimmen nicht überein.</h4>";
            }
            if($_GET['error'] === 'userexists'){
                echo "<h4 class='warning btn form-btn'>Ein Nutzer mit dieser E-Mail-Adresse existiert bereits.</h4>";
            }
            if($_GET['error'] === 'generic-error'){
                echo "<h4 class='warning btn form-btn'>Unbekannter Fehler.</h4>";
            }
            if($_GET['error'] === 'none'){
                echo "<h4 class='success btn form-btn'>Registrierung abgeschlossen. Du kannst dich jetzt einloggen.</h4>";
            }
        }
    ?>
            </form>

           
    </main>

  
   
    <?php include '../partials/footer.php'?>
  </body>
</html>
