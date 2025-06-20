<?php include __DIR__ . '/config/config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Villae Homes</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="shortcut icon" href="./assets/favicon/favicon-96x96.png" />
</head>

<body class="homepage">
    <?php include_once './partials/nav.php';?>
    <main class="main-container main-container-homepage homepage">
         <div class="landing-page-elements">
             <h1 id="landing-page-title">NATUR. STILLE. FREIHEIT.</h1>
                     <a href=<?php echo $base_url . "views/homes.php"; ?> class="btn btn-primary">Finde jetzt Dein Traumhaus</a>
         </div>
    
    </main>
    
    <?php include './partials/footer.php';?>

</body>
</html>


