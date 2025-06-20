<!DOCTYPE html>
<html lang="de">

  <?php include dirname(__DIR__) . '/config/db.php';?>
  <?php include '../handlers/get_all_homes.php'?>

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../style.css" />
    <link rel="shortcut icon" href="../assets/favicon/favicon-96x96.png" />
    <title>Ferienhäuser</title>
  </head>
  
  <?php include dirname(__DIR__) . '/partials/nav.php';?>
  <body>

    <main class="main-container">
      <?php if(isset($_GET['error']) && $_GET['error'] == 'none-home-deleted'):?>
    <h4 class='btn btn-primary'>Das Ferienhaus wurde entfernt.</h4>
  <?php endif; ?>
        <?php if(isset($_GET['error']) && $_GET['error'] == 'none-home-created'):?>
    <h4 class='btn btn-primary'>Das Ferienhaus wurde erfolgreich erstellt.</h4>
  <?php endif; ?>
        <?php $searchword = $_GET['searchword'] ?? ''; ?>
  <!-- Suchformular mit Filtern -->
    <form method="GET" action="../handlers/get_searchresults.php" class="form-horizontal">

  <!-- Text Inputs -->
  <label>Suchbegriff:
    <input type="text" name="searchword" value="<?= htmlspecialchars($searchword) ?>">
  </label>

  <label>Ferienhausname:
    <input type="text" name="name">
  </label>

  <label>Ort oder Land:
    <input type="text" name="location">
  </label>

  <!-- Numeric Inputs -->
<label>Schlafzimmer (mindestens):
  <input type="number" name="count_bedrooms" min="0">
</label>

<label>Badezimmer (mindestens):
  <input type="number" name="count_bathrooms" min="0">
</label>

<label>Größe in m² (mindestens):
  <input type="number" name="min_size" min="0">
</label>

<label>Preis von:
  <input type="number" name="min_price" min="0">
</label>

<label>bis:
  <input type="number" name="max_price" min="0">
</label>


  <!-- Amenities Group -->
  <div style="flex: 1 1 100%; display: flex; flex-wrap: wrap; gap: 1.5rem;">
    <label>Frühstück:
      <input type="checkbox" name="breakfast">
    </label>

    <label>Haustierbetreuung:
      <input type="checkbox" name="petsitting">
    </label>

    <label>Fahrradverleih:
      <input type="checkbox" name="bike_rental">
    </label>

    <label>Autovermietung:
      <input type="checkbox" name="car_rental">
    </label>

    <label>Wäscheservice:
      <input type="checkbox" name="laundry_service">
    </label>

    <label>Pool:
      <input type="checkbox" name="pool">
    </label>

    <label>Billiardtisch:
      <input type="checkbox" name="billiard">
    </label>

    <label>Fitnessraum:
      <input type="checkbox" name="fitness">
    </label>

    <label>Sauna:
      <input type="checkbox" name="sauna">
    </label>

    <label>Kingsizebett:
      <input type="checkbox" name="kingsizebed">
    </label>
  </div>

  <!-- Date Inputs -->
  <div class="dates">
  <h2>Zeitraum wählen</h2>
  <div class="date-fields">
    <label for="check_in">Von:
      <input type="date" name="check_in">
    </label>

    <label for="check_out">Bis:
      <input type="date" name="check_out">
    </label>
  </div>

  <?php if (isset($_GET['error']) && $_GET['error'] == 'past-dates') {
    echo "<h4 class='warning btn'>Wähle bitte zukünftige Termine.</h4>";
  } ?>
</div>


  <!-- Buttons -->
  <div class="buttons">
    <button type="submit" class="btn btn-primary">Suchen</button>
    <a class="btn btn-neutral" href="homes.php">Filter zurücksetzen</a>
  </div>

</form>

    <div class="homes-container">
        <?php foreach($homes as $home) {?>
          <div class="home">
            <figure class="home_picture">
               <?php $bildData = base64_encode($home['pic']); ?>
                <?php if (!empty($home['pic'])): ?>
                <?php echo '<img src="data:image/jpeg;base64,' . $bildData . '">';
                ?>
            <?php else: ?>
                <img src="/villae-homes/assets/sample-home.jpg" alt="Kein Bild verfügbar">
            <?php endif; ?>
        </figure>
<div class="home_information">
    <h2 class="home_information_heading">Informationen</h2>
    <div class="info-columns">
        <!-- General Information Column -->
        <div class="info-column general-info-column">
            <h3 class="info-column-heading">Grundinformation</h3>
            <ul class="home_information_list">
                <li class="home_information_list_item"><span><?php echo htmlspecialchars($home['name']); ?></span></li>
                <li class="home_information_list_item"><span><?php echo htmlspecialchars($home['location']); ?></span></li>
                <li class="home_information_list_item"><span><?php echo htmlspecialchars($home['size']); ?>m²</span></li>
                <li class="home_information_list_item"><span><?php echo htmlspecialchars($home['bedrooms']); ?> Schlafzimmer</span></li>
                <li class="home_information_list_item"><span>€ <?php echo htmlspecialchars($home['price']); ?> pro Nacht</span></li>
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
</div>

        
        <div class="home_actions">
          <a class="btn btn-primary" href="home_detail.php?home_id=<?php echo $home['home_id'];?>&owner_id=<?php echo $home['user_id'];?>">
        Details ansehen
          </a>
          
          <?php if (isset($_SESSION['user_session_id']) && $_SESSION['user_session_id'] == $home['user_id']) { ?>
        <form class="inline-form" action="../handlers/delete_home.php" method="POST">
          <input type="hidden" name="home_id" value="<?php echo $home['home_id']; ?>">
          <input type="hidden" name="user_id" value="<?php echo $home['user_id']; ?>">
          <button type="submit" class="btn btn-warning">
            Ferienhaus entfernen
          </button>
        </form>
          <?php } ?>
    </div>
</div>
      </div>
      <?php }?>
    </main>
    <?php include '../partials/footer.php'?> 
  </body>
</html>