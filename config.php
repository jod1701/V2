<?php
$bdd = mysqli_connect('localhost', 'root', '', 'emprunt_db');
if (!$bdd) {
    die("Erreur de connexion : " . mysqli_connect_error());
}
?>
