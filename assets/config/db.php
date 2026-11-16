<?php

$hostname = "localhost";
$database = "Gymnasiearbete";
$username = "gymnasiearbete";
$password = "gymnasiearbete";

try {
    $pdo = new PDO("mysql:host=$hostname; dbname=$database;", $username, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Kunde inte ansluta till databasen.");
}

?>