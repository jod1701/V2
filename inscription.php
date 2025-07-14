<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include("config.php");

include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $date_naissance = $_POST["date_naissance"];
    $genre = $_POST["genre"];
    $email = $_POST["email"];
    $ville = $_POST["ville"];
    $mdp = password_hash($_POST["mdp"], PASSWORD_DEFAULT);
    $image = $_FILES['image_profil']['name'];
    $image_tmp = $_FILES['image_profil']['tmp_name'];

    move_uploaded_file($image_tmp, "images/" . $image);

    $sql = "INSERT INTO membre (nom, date_naissance, genre, email, ville, mdp, image_profil)
            VALUES ('$nom', '$date_naissance', '$genre', '$email', '$ville', '$mdp', '$image')";
    if (mysqli_query($bdd, $sql)) {
        header("Location: login.php");
        exit();
    } else {
        $error = "Erreur : " . mysqli_error($bdd);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Inscription</title>
 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
   
    <link rel="stylesheet" href="style.css" />
</head>
<body>

<div class="card shadow">
    <h2 class="mb-4 text-center">Inscription</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" novalidate>
        <div class="mb-3">
            <label for="nom" class="form-label">Nom :</label>
            <input type="text" class="form-control" id="nom" name="nom" required autofocus>
        </div>

        <div class="mb-3">
            <label for="date_naissance" class="form-label">Date de naissance :</label>
            <input type="date" class="form-control" id="date_naissance" name="date_naissance" required>
        </div>

        <div class="mb-3">
            <label for="genre" class="form-label">Genre :</label>
            <select id="genre" name="genre" class="form-select" required>
                <option value="">-- Sélectionnez --</option>
                <option value="F">F</option>
                <option value="H">H</option>
                <option value="Autre">Autre</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email :</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
            <label for="ville" class="form-label">Ville :</label>
            <input type="text" class="form-control" id="ville" name="ville" required>
        </div>

        <div class="mb-3">
            <label for="mdp" class="form-label">Mot de passe :</label>
            <input type="password" class="form-control" id="mdp" name="mdp" required>
        </div>

        <div class="mb-4">
            <label for="image_profil" class="form-label">Image de profil :</label>
            <input type="file" class="form-control" id="image_profil" name="image_profil" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary w-100 custom-btn">S'inscrire</button>
    </form>

    <p class="mt-3 text-center">
        Déjà un compte ? <a href="login.php">Se connecter</a>
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
