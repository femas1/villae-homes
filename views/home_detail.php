<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferienhaus Details</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="shortcut icon" href="../assets/favicon/favicon-96x96.png" />
    <script src="../scripts/lightbox.js" defer></script>
    <script src="../scripts/checkout.js" defer></script>
</head>

<body>
<?php include '../partials/nav.php'; ?>
<main class="main-container">
    
  <?php
  $home_id =  $_GET['home_id'];
  include '../handlers/get_home_by_id.php';
  if (!empty($home_values)) {
      $home = $home_values[0];
        ?>
        
<!-- ERROR MESSAGES  -->
        <?php if(isset($_GET['error']) && $_GET['error'] == 'home-not-available') { 
                    echo "<h4 class='warning btn'>Das Ferienhaus ist im angegebenen Zeitraum nicht verfügbar.</h4>";
                } 
                if(isset($_GET['error']) && $_GET['error'] == 'home-already-in-cart') { 
                    echo "<h4 class='warning btn'>Diese Das Ferienhaus ist bereits für überlappende Zeiträume in Ihrem Warenkorb.</h4>";
                }
                if(isset($_GET['error']) && $_GET['error'] == 'past-dates') { 
                    echo "<h4 class='warning btn'>Buchungen in der Vergangenheit sind nicht möglich.</h4>";
                } 
                if(isset($_GET['error']) && $_GET['error'] == 'invalid-dates') { 
                    echo "<h4 class='warning btn'>Check-out Datum muss nach dem Check-in Datum liegen.</h4>";
                }
                ?>
<!-- END ERROR MESSAGES -->

   <!-- Bilderanzeige -->
  <div class="home_images">
    <?php foreach ($home_values as $home_image): ?>
        <?php if (!empty($home_image['pic'])): ?>
            <?php $bildData = base64_encode($home_image['pic']); ?>
            <img src="data:image/jpeg;base64,<?php echo $bildData; ?>" class="home_picture">
        <?php endif; ?>
    <?php endforeach; ?>
  </div>
      <?php } ?>
      <a href="./homes.php" class="btn">Zurück zur Häuserübersicht</a>
      <div class="home">
        <div class="home_information">
            <h2 class="home_information_heading">Informationen</h2>
            <div class="info-columns">
                <!-- General Information Column -->
                <div class="info-column general-info-column">
                    <h3 class="info-column-heading">Grundinformation</h3>
                    <ul class="home_information_list">
                        <li class="home_information_list_item"><span><?php echo $home['name']?></span></li>
                        <li class="home_information_list_item"><span><?php echo $home['location']?></span></li>
                        <li class="home_information_list_item"><span><?php echo $home['size']?>m²</span></li>
                        <li class="home_information_list_item"><span><?php echo $home['bedrooms']?> Schlafzimmer</span></li>
                        <li class="home_information_list_item"><span>€ <?php echo $home['price']?> pro Nacht</span></li>
                        <li class="home_information_list_item"><span><?php echo $home['bathrooms']?> Badezimmer</span></li>
                        <li class="home_information_list_item"><span>Gastgeber: <a href="../views/user_detail.php?user_id=<?php echo $home['user_id']?>"><?php echo $user["first_name"] . " " . $user["last_name"]?> </a></span></li>
                    </ul>
                </div>
                
                <!-- Equipment Column -->
                <div class="info-column equipment-column">
                    <h3 class="info-column-heading">Ausstattung</h3>
                    <ul class="home_information_list">
                        <?php if ($home['pool']): ?>
                        <li class="home_information_list_item"><span>Pool</span></li>
                        <?php endif; ?>
                        <?php if ($home['billiard']): ?>
                        <li class="home_information_list_item"><span>Billiardtisch</span></li>
                        <?php endif; ?>
                        <?php if ($home['fitness']): ?>
                        <li class="home_information_list_item"><span>Fitnessraum</span></li>
                        <?php endif; ?>
                        <?php if ($home['sauna']): ?>
                        <li class="home_information_list_item"><span>Sauna</span></li>
                        <?php endif; ?>
                        <?php if ($home['kingsizebed']): ?>
                        <li class="home_information_list_item"><span>Kingsizebett</span></li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <!-- Extras Column -->
                <div class="info-column extras-column">
                    <h3 class="info-column-heading">Freizeit</h3>
                    <ul class="home_information_list">
                        <?php if ($home['breakfast']): ?>
                        <li class="home_information_list_item"><span>Frühstück (+18€)</span></li>
                        <?php endif; ?>
                        <?php if ($home['petsitting']): ?>
                        <li class="home_information_list_item"><span>Haustierbetreuung (+30€)</span></li>
                        <?php endif; ?>
                        <?php if ($home['bike_rental']): ?>
                        <li class="home_information_list_item"><span>Fahrradverleih (+15€)</span></li>
                        <?php endif; ?>
                        <?php if ($home['car_rental']): ?>
                        <li class="home_information_list_item"><span>Autovermietung (+50€)</span></li>
                        <?php endif; ?>
                        <?php if ($home['laundry_service']): ?>
                        <li class="home_information_list_item"><span>Wäscheservice (+25€)</span></li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <!-- Words Column -->
                <div class="info-column extras-column">
                    <h3 class="info-column-heading">Sonstiges</h3>
                    <ul class="home_information_list">
                        <?php
                        $words = explode(' ', $home['words']);
                        foreach ($words as $word): ?>
                            <li class="home_information_list_item word_item"><span><?php echo htmlspecialchars($word); ?></span></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            
            <!-- Description Section -->
            <div class="description-fullwidth">
                <h3 class="info-column-heading">Beschreibung</h3>
                <p class="description-text"><?php echo $home['description']?></p>
            </div>

            <!-- Map -->
            <div class="description-fullwidth">
                <h3 class="info-column-heading">Lageplan</h3>
                <?php echo htmlspecialchars_decode($home['link']); ?>
            </div>
            
            <?php if(isset($_SESSION['user_session_id'])): ?>
                <div class="booking-section">
                    <form action="../handlers/book_home.php" method="POST">
                        <h3 class="info-column-heading">Zeitraum wählen</h3>
                        <div class="form-group">
                            <label for="check_in">Von:</label>
                            <input type="date" name="check_in" id="check_in" required>
                        </div>
                        <div class="form-group">
                            <label for="check_out">Bis:</label>
                            <input type="date" name="check_out" id="check_out" required>
                        </div>
                        
                        <h3 class="info-column-heading">Extras buchen:</h3>
                        <div class="extras-group">
                            <?php if ($home['breakfast']): ?>
                            <label class="extra-option">
                                <input type="checkbox" name="breakfast" id="breakfast">
                                <span>Frühstück (+18€)</span>
                            </label>
                            <?php endif; ?>
                            <?php if ($home['petsitting']): ?>
                            <label class="extra-option">
                                <input type="checkbox" name="petsitting" id="petsitting">
                                <span>Haustierbetreuung (+30€)</span>
                            </label>
                            <?php endif; ?>
                            <?php if ($home['bike_rental']): ?>
                            <label class="extra-option">
                                <input type="checkbox" name="bike_rental" id="bike_rental">
                                <span>Fahrradverleih (+15€)</span>
                            </label>
                            <?php endif; ?>
                            <?php if ($home['car_rental']): ?>
                            <label class="extra-option">
                                <input type="checkbox" name="car_rental" id="car_rental">
                                <span>Autovermietung (+50€)</span>
                            </label>
                            <?php endif; ?>
                            <?php if ($home['laundry_service']): ?>
                            <label class="extra-option">
                                <input type="checkbox" name="laundry_service" id="laundry_service">
                                <span>Wäscheservice (+25€)</span>
                            </label>
                            <?php endif; ?>
                        </div>
                        
                        <input type="hidden" name="home_price" value="<?php echo $home['price'];?>">
                        <input type="hidden" name="home_id" value="<?php echo $home_id;?>">
                        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_session_id'];?>">
                        <input type="hidden" name="host_id" value="<?php echo $home['user_id'];?>">
                        
                        <button type="submit" name="submit" class="btn btn-primary">Reservieren</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="login-prompt">
                    <p>Logge dich ein, um dieses Ferienhaus zu buchen:</p>
                    <a class="btn btn-primary" href="<?php echo $base_url; ?>views/login.php">Einloggen</a>
                </div>
            <?php endif; ?>
            
            <?php if(isset($_SESSION['user_session_id']) && $_SESSION['user_session_id'] == $_GET['owner_id']): ?>
                <div class="owner-actions">
                    <a class="btn btn-primary" href="./edit_home.php?home_id=<?php echo $home_id; ?>&name=<?php echo urlencode($home['name']); ?>&location=<?php echo urlencode($home['location']); ?>&size=<?php echo $home['size']; ?>&bedrooms=<?php echo $home['bedrooms']; ?>&price=<?php echo $home['price']; ?>&bathrooms=<?php echo $home['bathrooms']; ?>&description=<?php echo $home['description']; ?>&user_id=<?php echo $home['user_id']; ?>&breakfast=<?php echo $home['breakfast']; ?>&petsitting=<?php echo $home['petsitting']; ?>&bike_rental=<?php echo $home['bike_rental']; ?>&car_rental=<?php echo $home['car_rental']; ?>&laundry_service=<?php echo $home['laundry_service'] ?>&pool=<?php echo $home['pool']; ?>&billiard=<?php echo $home['billiard']; ?>&fitness=<?php echo $home['fitness']; ?>&sauna=<?php echo $home['sauna']; ?>&kingsizebed=<?php echo $home['kingsizebed']?>&link=<?php echo $home['link']; ?>">
                        Haus Bearbeiten
                    </a>
                   
                    <form action="../handlers/delete_home.php" method="POST">
                        <input type="hidden" name="home_id" value="<?php echo $home_id;?>">
                        <button type="submit" class="btn btn-warning home_information_delete_btn">Ferienhaus entfernen</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>
<?php include '../partials/footer.php' ?>
</body>
</html>