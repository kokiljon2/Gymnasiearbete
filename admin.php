<?php
Session_start();


require_once 'assets/config/db.php';
require_once 'assets/functions.php';

$uid = $_SESSION['personal_id'];
$namn = $_SESSION['personal_namn'];
$grupp = $_SESSION['personal_grupp'];
$tillåtnaRoller = ['Huvudadmin', 'Admin', 'Personal', 'Ekonom'];

if (!in_array($grupp, $tillåtnaRoller, true)) {
    header("Location: login.php");
    exit;
}


if (!isset($uid)) {
    header("Location: login.php");
    exit;
}



?>

<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <title>Administrationspanel</title>
</head>

<body>

    <h1>Administrationspanel</h1>
    <h3>Välkommen, <?= htmlspecialchars($namn) ?>!</h3>
    <p>Din roll: <?= htmlspecialchars($grupp) ?></p>
    <hr>

    <?php if (in_array($grupp, ['Huvudadmin', 'Admin', 'Personal'], true)): ?>

        <h2>Bokningar</h2>
        <a href="bokningar.php">Hantera bokningar</a>
        <br>

    <?php endif; ?>

    <?php if (in_array($grupp, ['Huvudadmin', 'Admin', 'Personal', 'Ekonom'], true)): ?>

        <h2>Lokaler</h2>
        <a href="lokaler.php">Visa lokaler</a>
        <br>

    <?php endif; ?>

    <?php if (in_array($grupp, ['Huvudadmin', 'Admin'], true)): ?>

        <a href="hantera_lokaler.php">Hantera lokalinformation</a>
        <br>

    <?php endif; ?>

    <?php if ($grupp === 'Huvudadmin'): ?>

        <h2>Personalhantering</h2>
        <a href="registrera_personal.php">Registrera personal</a>
        <br>
        <a href="hantera_personal.php">Hantera personal</a>
        <br>

    <?php endif; ?>

    <?php if (in_array($grupp, ['Huvudadmin', 'Ekonom'], true)): ?>

        <h2>Ekonomi</h2>
        <a href="ekonomi.php">Visa vinster och förluster</a>
        <br>
        <a href="hantera_priser.php">Hantera lokalpriser</a>
        <br>

    <?php endif; ?>

    <?php if ($grupp === 'Huvudadmin'): ?>

        <h2>Systeminställningar</h2>
        <a href="installningar.php">Hantera systeminställningar</a>
        <br>

    <?php endif; ?>

    <hr>
    <a href="logout.php">Logga ut</a>

</body>

</html>