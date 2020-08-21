<?php
try {
    $dbh = new PDO('mysql:host=sql7.freemysqlhosting.net;dbname=sql7361593','sql7361593','PLbzbqNpzK');
} catch (Exception $e) {
    die("Unable to connect: " . $e->getMessage());
}
?>