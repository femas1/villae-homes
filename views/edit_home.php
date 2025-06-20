<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferienhaus Bearbeiten</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="shortcut icon" href="../assets/favicon/favicon-96x96.png" />
</head>
<body>
    <?php include '../partials/nav.php' ?>
    <main class="main-container">
    <?php 
     $id = $_GET['home_id'];
     $name = $_GET['name'];
     $location = $_GET['location'];
     $size = $_GET['size'];
     $bedrooms = $_GET['bedrooms'];
     $price = $_GET['price'];
     $bathrooms = $_GET['bathrooms'];
     $description = $_GET['description'];
     $user_id = $_GET['user_id'];
     $breakfast = $_GET['breakfast'];
     $petsitting = $_GET['petsitting'];
     $bike_rental = $_GET['bike_rental'];
     $car_rental = $_GET['car_rental'];
     $laundry_service = $_GET['laundry_service'];
     $pool = $_GET['pool'];
     $billiard = $_GET['billiard'];
     $fitness = $_GET['fitness'];
     $sauna = $_GET['sauna'];
     $kingsize = $_GET['kingsizebed'];
     $link = $_GET['link'];
    ?>
    <?php include '../handlers/get_searchwords_by_id.php';?>
    
    
    <form class="edit-home-form" action="../handlers/edit_home.php" method="POST">
        <input type="hidden" name="home_id" value="<?php echo $id;?>">
        <h2>Ferienhaus bearbeiten</h2>
        
        <div class="form-group name-input">
            <label for="name">Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($name)?>">
        </div>
        
        <div class="form-group location-input">
            <label for="location">Ort</label>
            <input type="text" name="location" value="<?php echo htmlspecialchars($location)?>">
        </div>
        
        <div class="form-group size-input">
            <label for="size">Fläche (m²)</label>
            <input type="number" name="size" value="<?php echo $size?>">
        </div>
        
        <div class="form-group bedrooms-input">
            <label for="bedrooms">Schlafzimmer</label>
            <input type="number" name="bedrooms" value="<?php echo $bedrooms?>">
        </div>
        
        <div class="form-group price-input">
            <label for="price">Preis pro Nacht (€)</label>
            <input type="number" name="price" value="<?php echo $price?>">
        </div>
        
        <div class="form-group bathrooms-input">
            <label for="bathrooms">Badezimmer</label>
            <input type="number" name="bathrooms" value="<?php echo $bathrooms?>">
        </div>
        
        <div class="form-group description-input" style="grid-column: span 2;">
            <label for="description">Beschreibung</label>
            <textarea name="description"><?php echo htmlspecialchars($description); ?></textarea>
        </div>

        <div class="form-group description-input" style="grid-column: span 2;">
            <label for="link">Lageplan</label>
            <textarea name="link"><?php echo htmlspecialchars($link); ?></textarea>
        </div>
        
        <h3>Mögliche Aktivität auswählen</h3>
        <div class="features-grid">
            <div class="feature-checkbox">
                <input type="checkbox" name="breakfast" id="breakfast" <?php if ($breakfast) echo "checked"; ?>>
                <label for="breakfast">Frühstück</label>
            </div>
            
            <div class="feature-checkbox">
                <input type="checkbox" name="petsitting" id="petsitting" <?php if ($petsitting) echo "checked"; ?>>
                <label for="petsitting">Haustierbetreuung</label>
            </div>
            
            <div class="feature-checkbox">
                <input type="checkbox" name="bike_rental" id="bike_rental" <?php if ($bike_rental) echo "checked"; ?>>
                <label for="bike_rental">Fahrradverleih</label>
            </div>
            
            <div class="feature-checkbox">
                <input type="checkbox" name="car_rental" id="car_rental" <?php if ($car_rental) echo "checked"; ?>>
                <label for="car_rental">Autovermietung</label>
            </div>
            
            <div class="feature-checkbox">
                <input type="checkbox" name="laundry_service" id="laundry_service" <?php if ($laundry_service) echo "checked"; ?>>
                <label for="laundry_service">Wäscheservice</label>
            </div>
        </div>
                <h3>Ausstattung auswählen</h3>
        <div class="features-grid">
            <div class="feature-checkbox">
                <input type="checkbox" name="pool" id="pool" <?php if ($pool) echo "checked"; ?>>
                <label for="pool">Pool</label>
            </div>
            
            <div class="feature-checkbox">
                <input type="checkbox" name="billiard" id="billiard" <?php if ($billiard) echo "checked"; ?>>
                <label for="billiard">Billiardtisch</label>
            </div>
            
            <div class="feature-checkbox">
                <input type="checkbox" name="fitness" id="fitness" <?php if ($fitness) echo "checked"; ?>>
                <label for="fitness">Fitnessraum</label>
            </div>
            
            <div class="feature-checkbox">
                <input type="checkbox" name="sauna" id="sauna" <?php if ($sauna) echo "checked"; ?>>
                <label for="sauna">Sauna</label>
            </div>
            
            <div class="feature-checkbox">
                <input type="checkbox" name="kingsize" id="kingsize" <?php if ($kingsize) echo "checked"; ?>>
                <label for="kingsize">Kingsizebett</label>
            </div>
        </div>
        
        <input type="hidden" name="user_id" value="<?php echo $user_id?>">
        
        <div class="edit-form-actions">
            <a href="./homes.php" class="btn btn-neutral">Abbrechen</a>
            <button class="btn btn-primary" type="submit" name="submit">Änderungen speichern</button>
        </div>
    </form>
    <?php include '../handlers/get_pictures_by_id.php';?>
    <div class="searchwords-form">
        <h3>Schlagwörter bearbeiten</h3>
        <form method='POST' action='../handlers/edit_searchwords.php'>
            <div class="form-group">
                <label for="old_searchword">Vorhandenes Schlagwort:</label>
                <select name="old_searchword" id="old_searchword">
                    <?php foreach ($searchwords as $word): ?>
                        <?php $word = trim($word); ?>
                        <option value="<?php echo htmlspecialchars($word); ?>">
                            <?php echo htmlspecialchars($word); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="new_searchword">Neues Schlagwort:</label>
                <input type='text' name='new_searchword' id="new_searchword">
            </div>
            
            <input type='hidden' name='home_id' value='<?php echo $id; ?>'>
            
            <div class="buttons-left">
                <button type='submit' name='action' value='replace' class="btn btn-primary">Ersetzen</button>
                <button type='submit' name='action' value='add' class="btn btn-primary">Hinzufügen</button>
                <button type='submit' name='action' value='delete' class="btn btn-primary btn-warning">Löschen</button>
            </div>
        </form>
    </div>
    
    <div class="delete-home-form">
        <form action="../handlers/delete_home.php" method="POST">
            <input type="hidden" name="home_id" value="<?php echo $id; ?>">
            <button type="submit" class="btn btn-warning">Ferienhaus entfernen</button>
        </form>
    </div>
    </main>
    <?php include '../partials/footer.php' ?>
</body>
</html>