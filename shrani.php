<?php
// Shrani rezervacijo iz obrazca v bazo
require_once "db.php";

$pdo->prepare("INSERT INTO rezervacije (ime, tel, datum, ura, osebe, opomba) VALUES (?, ?, ?, ?, ?, ?)")
    ->execute([$_POST["ime"], $_POST["tel"], $_POST["datum"], $_POST["ura"], $_POST["osebe"], $_POST["opomba"]]);
?>
<!DOCTYPE html>
    <html lang="sl-SI">
    <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Hvala</title>
</head>
<body>
    <h1>Hvala!</h1>
    <p>Vaša rezervacija je shranjena.</p>
    <a href="osnovna.html">Nazaj na domačo stran</a>
</body>
</html>
