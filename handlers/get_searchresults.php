<?php
include '../config/db.php';

// Sanitize all inputs
// features inputs
$sanitized_breakfast = isset($_GET['breakfast']) ? 1 : 0;
$sanitized_petsitting = isset($_GET['petsitting']) ? 1 : 0;
$sanitized_bike_rental = isset($_GET['bike_rental']) ? 1 : 0;
$sanitized_car_rental = isset($_GET['car_rental']) ? 1 : 0;
$sanitized_laundry_service = isset($_GET['laundry_service']) ? 1 : 0;
//activities inputs
$sanitized_pool = isset($_GET['pool']) ? 1 : 0;
$sanitized_billiard = isset($_GET['billiard']) ? 1 : 0;
$sanitized_fitness = isset($_GET['fitness']) ? 1 : 0;
$sanitized_sauna = isset($_GET['sauna']) ? 1 : 0;
$sanitized_kingsize = isset($_GET['kingsizebed']) ? 1 : 0;
// home details inputs
$sanitized_countbedrooms = filter_var($_GET['count_bedrooms'] ?? '', FILTER_SANITIZE_NUMBER_INT);
$sanitized_countbathrooms = filter_var($_GET['count_bathrooms'] ?? '', FILTER_SANITIZE_NUMBER_INT);
$sanitized_minsize = filter_var($_GET['min_size'] ?? '', FILTER_SANITIZE_NUMBER_INT);
$sanitized_minprice = filter_var($_GET['min_price'] ?? '', FILTER_SANITIZE_NUMBER_INT);
$sanitized_maxprice = filter_var($_GET['max_price'] ?? '', FILTER_SANITIZE_NUMBER_INT);
$sanitized_searchword = filter_var($_GET['searchword'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
$sanitized_name = filter_var($_GET['name'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
$sanitized_location = filter_var($_GET['location'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
//date inputs
$sanitized_check_in = !empty($_GET['check_in']) ? $_GET['check_in'] : null;
$sanitized_check_out = !empty($_GET['check_out']) ? $_GET['check_out'] : null;

// Base SQL query
$sql = "
    SELECT DISTINCT h.*,
           hk.words,
           (SELECT pic FROM pictures p
            WHERE p.home_id = h.home_id AND p.main = 1
            LIMIT 1) as pic
    FROM homes h
    LEFT JOIN home_keywords hk ON h.home_id = hk.home_id
    WHERE 1=1
";

// Add search conditions
if (!empty($sanitized_searchword)) {
    $sql .= " AND hk.words LIKE '%" . mysqli_real_escape_string($conn, $sanitized_searchword) . "%'";
}

if (!empty($sanitized_name)) {
    $sql .= " AND h.name LIKE '%" . mysqli_real_escape_string($conn, $sanitized_name) . "%'";
}

if (!empty($sanitized_location)) {
    $sql .= " AND h.location LIKE '%" . mysqli_real_escape_string($conn, $sanitized_location) . "%'";
}

// Date availability check (allows same-day transitions)
if (!empty($sanitized_check_in) && !empty($sanitized_check_out)) {
    $sql .= " AND h.home_id NOT IN (
        SELECT bi.home_id
        FROM booking_items bi
        WHERE (
            bi.check_in < '" . mysqli_real_escape_string($conn, $sanitized_check_out) . "'
            AND bi.check_out > '" . mysqli_real_escape_string($conn, $sanitized_check_in) . "'
        )
    )";
}

// Add other filters
if (!empty($sanitized_countbedrooms)) {
    $sql .= " AND h.bedrooms >= " . (int)$sanitized_countbedrooms;
}

if (!empty($sanitized_countbathrooms)) {
    $sql .= " AND h.bathrooms >= " . (int)$sanitized_countbathrooms;
}

if (!empty($sanitized_minsize)) {
    $sql .= " AND h.size >= " . (int)$sanitized_minsize;
}

if (!empty($sanitized_minprice)) {
    $sql .= " AND h.price >= " . (float)$sanitized_minprice;
}

if (!empty($sanitized_maxprice)) {
    $sql .= " AND h.price <= " . (float)$sanitized_maxprice;
}

if ($sanitized_breakfast) {
    $sql .= " AND h.breakfast = 1";
}

if ($sanitized_petsitting) {
    $sql .= " AND h.petsitting = 1";
}

if ($sanitized_bike_rental) {
    $sql .= " AND h.bike_rental = 1";
}

if ($sanitized_car_rental) {
    $sql .= " AND h.car_rental = 1";
}

if ($sanitized_laundry_service) {
    $sql .= " AND h.laundry_service = 1";
}

if ($sanitized_pool) {
    $sql .= " AND h.pool = 1";
}

if ($sanitized_billiard) {
    $sql .= " AND h.billiard = 1";
}

if ($sanitized_fitness) {
    $sql .= " AND h.fitness = 1";
}

if ($sanitized_sauna) {
    $sql .= " AND h.sauna = 1";
}

if ($sanitized_kingsize) {
    $sql .= " AND h.kingsizebed = 1";
}

if (!empty($sanitized_check_in) && !empty($sanitized_check_out)) {
    $today = date('Y-m-d');

    if ($sanitized_check_in < $today || $sanitized_check_out < $today) {
        echo "<h4 class='btn-warning btn'>Bitte wähle zukünftige Fristen.</h4>";
        $sanitized_check_in = $today;
        $sanitized_check_out = '';
        header("Location: ../views/homes.php?error=past-dates");
        exit();
    }
}
// Sort results
$sql .= " ORDER BY h.home_id DESC";

// Execute query
$result = mysqli_query($conn, $sql);
$anzahl = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suchergebnisse</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="shortcut icon" href="./assets/favicon/favicon-96x96.png" />
</head>
<body>
<?php include dirname(__DIR__) . '/partials/nav.php'; ?>
<main class="main-container">
    <form class="form-horizontal" method="GET" action="../handlers/get_searchresults.php">
        <label>Suchbegriff:
            <input type="text" name="searchword" value="<?= htmlspecialchars($sanitized_searchword) ?>">
        </label>
        <label>Ferienhausname:
            <input type="text" name="name" value="<?= htmlspecialchars($sanitized_name) ?>">
        </label>
        <label>Ort oder Land:
            <input type="text" name="location" value="<?= htmlspecialchars($sanitized_location) ?>">
        </label>
        <label>Schlafzimmer (mindestens):
            <input type="number" name="count_bedrooms" value="<?= htmlspecialchars($sanitized_countbedrooms) ?>">
        </label>
        <label>Badezimmer (mindestens):
            <input type="number" name="count_bathrooms" value="<?= htmlspecialchars($sanitized_countbathrooms) ?>">
        </label>
        <label>Größe in m² (mindestens):
            <input type="number" name="min_size" value="<?= htmlspecialchars($sanitized_minsize) ?>">
        </label>
        <label>Preis von:
            <input type="number" name="min_price" value="<?= htmlspecialchars($sanitized_minprice) ?>">
        </label>
        <label>bis:
            <input type="number" name="max_price" value="<?= htmlspecialchars($sanitized_maxprice) ?>">
        </label>
        <label>Frühstück:
            <input type="checkbox" name="breakfast" <?= $sanitized_breakfast ? 'checked' : '' ?>>
        </label>
        <label>Haustierbetreuung:
            <input type="checkbox" name="petsitting" <?= $sanitized_petsitting ? 'checked' : '' ?>>
        </label>
        <label>Fahrradverleih:
            <input type="checkbox" name="bike_rental" <?= $sanitized_bike_rental ? 'checked' : '' ?>>
        </label>
        <label>Autovermietung:
            <input type="checkbox" name="car_rental" <?= $sanitized_car_rental ? 'checked' : '' ?>>
        </label>
        <label>Wäscheservice:
            <input type="checkbox" name="laundry_service" <?= $sanitized_laundry_service ? 'checked' : '' ?>>
        </label>
        <label>Pool:
            <input type="checkbox" name="pool" <?= $sanitized_pool ? 'checked' : '' ?>>
        </label>
        <label>Billiardtisch:
            <input type="checkbox" name="billiard" <?= $sanitized_billiard ? 'checked' : '' ?>>
        </label>
        <label>Fitnessraum:
            <input type="checkbox" name="fitness" <?= $sanitized_fitness ? 'checked' : '' ?>>
        </label>
        <label>Sauna:
            <input type="checkbox" name="sauna" <?= $sanitized_sauna ? 'checked' : '' ?>>
        </label>
        <label>Kingsizebett:
            <input type="checkbox" name="kingsizebed" <?= $sanitized_kingsize ? 'checked' : '' ?>>
        </label>
   <!-- Date Inputs -->
<div class="dates">
    <h2>Zeitraum wählen</h2>
    <div class="date-fields">
        <label for="check_in">Von:
            <input type="date" name="check_in" value="<?= htmlspecialchars($sanitized_check_in) ?>">
        </label>

        <label for="check_out">Bis:
            <input type="date" name="check_out" value="<?= htmlspecialchars($sanitized_check_out) ?>">
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

    <?php if ($anzahl > 0): ?>
        <h2>Suchergebnisse</h2>
        <div class="homes-container">
            <?php while ($home = mysqli_fetch_assoc($result)): ?>
                <div class="home">
                    <figure class="home_picture">
                        <?php $bildData = base64_encode($home['pic']); ?>
                        <?php if (!empty($home['pic'])): ?>
                            <?php echo '<img src="data:image/jpeg;base64,' . $bildData . '">'; ?>
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
                                    if (!empty($home['words'])) {
                                        $words = explode(' ', $home['words']);
                                        foreach ($words as $word): ?>
                                            <li class="home_information_list_item word_item"><span><?php echo htmlspecialchars($word); ?></span></li>
                                        <?php endforeach;
                                    } else { ?>
                                        <li class="home_information_list_item word_item"><span>Keine zusätzlichen Informationen verfügbar</span></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="home_actions">
                        <a class="btn btn-primary" href="../views/home_detail.php?home_id=<?php echo $home['home_id'];?>&owner_id=<?php echo $home['user_id'];?>">
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
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>Keine passenden Häuser gefunden.</p>
    <?php endif; ?>

    <?php
    mysqli_free_result($result);
    mysqli_close($conn);
    ?>
</main>
</body>
</html>
