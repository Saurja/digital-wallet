<?php
try {
    $dbh = new PDO('mysql:host=localhost;dbname=digital-wallet','root','');
} catch (Exception $e) {
    die("Unable to connect: " . $e->getMessage());
}
?>