<?php
// Pregled rezervacij in brisanje
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pdo->prepare("DELETE FROM rezervacije WHERE id = ?")->execute([$_POST["id"]]);
    header("Location: admin.php");
    exit;
}

$rezervacije = $pdo->query("SELECT * FROM rezervacije ORDER BY datum, ura")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="sl-SI">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="style.css">
<title>Rezervacije</title>
</head>
<body>

<h1>Rezervacije</h1>

<table>
    <tr>
        <th>Datum</th><th>Ura</th><th>Ime</th><th>Telefon</th><th>Oseb</th><th>Opomba</th><th></th>
    </tr>

    <?php foreach ($rezervacije as $r): ?>
    <tr>
        <td><?= htmlspecialchars($r["datum"]) ?></td>
        <td><?= htmlspecialchars(substr($r["ura"], 0, 5)) ?></td>
        <td><?= htmlspecialchars($r["ime"]) ?></td>
        <td><?= htmlspecialchars($r["tel"]) ?></td>
        <td><?= htmlspecialchars($r["osebe"]) ?></td>
        <td><?= htmlspecialchars($r["opomba"] ?? "") ?></td>
        <td>
            <form method="POST">
                <input type="hidden" name="id" value="<?= $r["id"] ?>">
                <button>Izbriši</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
