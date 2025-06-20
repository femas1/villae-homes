<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php

include dirname(__DIR__) . "/config/db.php";


$sql = "SELECT id, pic, main, outside, inside 
        FROM pictures 
        WHERE home_id = " . (int)$id . "
        ORDER BY 
            main DESC,
            outside DESC,
            inside DESC";

$result = mysqli_query($conn, $sql);

 ?>

<div class="home_images">
<?php while ($picture = mysqli_fetch_assoc($result)): ?>
    <div>
        <figure class="home_picture">
            <?php if (!empty($picture['pic'])): ?>
                <img src="data:image/jpeg;base64,<?= base64_encode($picture['pic']) ?>">
                <form method="post" action="../handlers/edit_pictures.php" class="inline-form">
                    <input type="hidden" name="picture_id" value="<?= $picture['id'] ?>">
                    <input type="hidden" name="home_id" value="<?= $id ?>">
                    <button type="submit" name="action" value="delete" class="btn btn-warning">Löschen</button>
                </form>
            <?php else: ?>
                <img src="/villae-homes/assets/sample-home.jpg" alt="Kein Bild verfügbar">
            <?php endif; ?>
        </figure>
    </div>
<?php endwhile; ?>
</div>

<!-- Formular für neuen Bild-Upload -->
<form class="searchwords-form" method="post" action="../handlers/edit_pictures.php" enctype="multipart/form-data" id="upload">
    <h3>Neue Bilder Hinzufügen</h3>
    <input type="hidden" name="home_id" value="<?= $id ?>">

    <div class="form-group form-group-searchwords">
        <h2>Titelbilder:</h2>
        <div class="file-input-container">
            <input type="file" id="mainbilder" name="mainbilder[]" class="file-input" multiple accept="image/*">
            <label for="mainbilder" class="btn-primary file-input-label">Durchsuchen</label>
        </div>
    </div>

    <div class="form-group form-group-searchwords">
        <h2>Außenansicht:</h2>
        <div class="file-input-container">
            <input type="file" id="outsidebilder" name="outsidebilder[]" class="file-input" multiple accept="image/*">
            <label for="outsidebilder" class="btn-primary file-input-label">Durchsuchen</label>
        </div>
    </div>

    <div class="form-group form-group-searchwords">
        <h2>Innenansicht:</h2>
        <div class="file-input-container">
            <input type="file" id="insidebilder" name="insidebilder[]" class="file-input" multiple accept="image/*">
            <label for="insidebilder" class="btn-primary file-input-label">Durchsuchen</label>
        </div>
    </div>

    <button class="btn btn-primary" type="submit" name="action" value="add">Bilder hochladen</button>
</form>

<?php
mysqli_free_result($result);
mysqli_close($conn);
?>
    <script src="/villaer-homes/handlers/javascript.js"
        type="text/javascript"></script>
</body>
</html>


