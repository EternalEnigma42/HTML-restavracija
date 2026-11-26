<?php
// Povezava z bazo (Laragon: uporabnik root, brez gesla)
$pdo = new PDO(
    "mysql:host=localhost;dbname=restavracija;charset=utf8mb4",
    "root",
    "",
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);
