<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neues Ferienhaus</title>
    <link rel="stylesheet" href="../style.css" />
    <link rel="shortcut icon" href="../assets/favicon/favicon-96x96.png" />
    <script src="../scripts/uploadCheck.js" defer></script>
</head>


<body>
    
    <?php include '../config/db.php'?>
    <?php include '../partials/nav.php'?>
    <?php include '../handlers/new_home.php'?>
    <?php $user_id = $_SESSION['user_session_id']?>
<?php if(isset($_SESSION['user_session_id'])): ?>
    <main class="main-container">
    
            <form class="form" action="../handlers/new_home.php" method="POST" enctype="multipart/form-data" id="upload">
                <div class="name-input">
                    <label for="name">Name</label>
                    <input type="text" name="name" placeholder="Villa Hügel">
                </div>
                <div class="location-input">
                    <label for="location">Ort</label>
                    <input type="text" name="location" placeholder="Essen">
                </div>
                <div class="size-input">
                    <label for="size">Fläche</label>
                    <input type="number" name="size" placeholder="6000">
                </div>
                <div class="bedrooms-input">
                    <label for="bedrooms">Schlafzimmer</label>
                    <input type="number" name="bedrooms" placeholder="10">
                </div>
                <div class="price-input">
                    <label for="price">Preis pro Nacht</label>
                    <input type="number" name="price" placeholder="2500">
                </div>
                <div class="bathrooms-input">
                    <label for="bathrooms">Badezimmer</label>
                    <input type="number" name="bathrooms" placeholder="10">
                </div>
                <div class="description-input">
                    <label for="description">Beschreibung</label>
                    <textarea name="description" placeholder="Diese historische Villa bietet atemberaubende Ausblicke auf den Rhein und ist von einem wunderschönen Park umgeben. Genießen Sie luxuriöse Zimmer..."></textarea>
                </div>
                <div class="words-input">
                    <label for="words">Schlagwörter - mit Leerzeichen getrennt angeben</label>
                    <textarea name="words" placeholder="Seeblick Pool"></textarea>
                </div>
                <div class="words-input">
                    <label for="link">Lageplan - iFrame Link aus Google Maps einfügen</label>
                    <textarea name="link" placeholder="&lt;iframe src=&quot;https://www.google.com/maps/embed?...&quot;&gt;&lt;/iframe&gt;"></textarea>
                </div>
                <h3>Mögliche Aktivitäten auswählen:</h3>
                <div class="features-input">
                    <label for="breakfast">Frühstück</label>
                    <input type="checkbox" name="breakfast">

                    <label for="petsitting">Haustierbetreuung</label>
                    <input type="checkbox" name="petsitting">

                    <label for="bike_rental">Fahrradverleih</label>
                    <input type="checkbox" name="bike_rental">

                    <label for="car_rental">Autovermietung</label>
                    <input type="checkbox" name="car_rental">

                    <label for="laundry_service">Wäscheservice</label>
                    <input type="checkbox" name="laundry_service">
                </div> 
                <h3>Ausstattung auswählen:</h3>
                <div class="features-input">
                    <label for="pool">Pool</label>
                    <input type="checkbox" name="pool">

                    <label for="billiard">Billiardtisch</label>
                    <input type="checkbox" name="billiard">

                    <label for="fitness">Fitnessraum</label>
                    <input type="checkbox" name="fitness">

                    <label for="sauna">Sauna</label>
                    <input type="checkbox" name="sauna">

                    <label for="kingsize">Kingsizebett</label>
                    <input type="checkbox" name="kingsize">
                </div> 
                  
                <div class="upload-image-group">
                    <div class="form-group form-group-searchwords">
                        <h2>Titelbilder:</h2>
                        <div class="file-input-container">
                            <input type="file" id="mainbilder" name="mainbilder[]" multiple accept="image/*" style="display: none">
                            <label for="mainbilder" class="btn-primary file-input-label">Durchsuchen</label>
                        </div>
                    </div>
                    <div class="form-group form-group-searchwords">
                        <h2>Außenansicht:</h2>
                        <div class="file-input-container">
                            <input type="file" id="outsidebilder" name="outsidebilder[]" multiple accept="image/*" style="display: none">
                            <label for="outsidebilder" class="btn-primary file-input-label">Durchsuchen</label>
                        </div>
                    </div>
                    <div class="form-group form-group-searchwords">
                        <h2>Innenansicht:</h2>
                        <div class="file-input-container">
                            <input type="file" id="insidebilder" name="insidebilder[]" multiple accept="image/*" style="display: none">
                            <label for="insidebilder" class=" btn-primary file-input-label">Durchsuchen</label>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <button class="form-btn btn-primary" type="submit" name="submit">Absenden</button>
            </form>
            <?php if (isset($_GET['error'])){
               if($_GET['error'] == "empty-name") {echo "<h4 class='warning'>Bitte einen Namen angeben!</h4>";
               }
               if($_GET['error'] == "empty-location") {echo "<h4 class='warning'>Bitte einen Ort angeben!</h4>";
               }
               if($_GET['error'] == "empty-size") {echo "<h4 class='warning'>Bitte Fläche angeben!</h4>";
               }
               if($_GET['error'] == "empty-bedrooms") {echo "<h4 class='warning'>Bitte Schlafzimmer angeben!</h4>";
               }
               if($_GET['error'] == "empty-price") {echo "<h4 class='warning'>Bitte Preis pro Nacht angeben!</h4>";
               }
               if($_GET['error'] == "empty-bathrooms") {echo "<h4 class='warning'>Bitte Bäder angeben!</h4>";
               }
               if($_GET['error'] == "empty-description") {echo "<h4 class='warning'>Bitte Beschreibung angeben!</h4>";
               }
               if($_GET['error'] == "empty-words") {echo "<h4 class='warning'>Bitte Schlagwörter angeben!</h4>";
               }
               if($_GET['error'] == "empty-link") {echo "<h4 class='warning'>Bitte Lageplan angeben!</h4>";
               }
            };?>
  <?php else: header('Location: ../index.php')?>
            <?php endif; ?>
    </main>
    <?php include '../partials/footer.php'?>
    
</body>
</html>