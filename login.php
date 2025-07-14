<?php
session_start();
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $mdp = $_POST["mdp"];

    $query = "SELECT * FROM membre WHERE email = '$email'";
    $result = mysqli_query($bdd, $query);

    if ($row = mysqli_fetch_assoc($result)) {
     if ($mdp == $row["mdp"]) {

            $_SESSION["id_membre"] = $row["id_membre"];
            $_SESSION["nom"] = $row["nom"];
            header("Location: liste_objet.php");
            exit();
        } else {
            $error = "Mot de passe incorrect.";
        }
    } else {
        $error = "Adresse email inconnue.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Connexion</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  
    <link rel="stylesheet" href="style.css" />
</head>
<body>

<div class="card shadow">
    <h2 class="mb-4 text-center">Connexion</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" novalidate>
        <div class="mb-3">
            <label for="email" class="form-label">Email :</label>
            <input type="email" class="form-control" id="email" name="email" required autofocus>
        </div>

        <div class="mb-4">
            <label for="mdp" class="form-label">Mot de passe :</label>
            <input type="password" class="form-control" id="mdp" name="mdp" required>
        </div>

        <button type="submit" class="btn btn-primary w-100 custom-btn">
            Se connecter
        </button>
    </form>

    <p class="mt-3 text-center">
        Pas encore inscrit ? <a href="inscription.php">Créer un compte</a>
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
