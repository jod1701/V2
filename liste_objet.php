<?php
session_start();
include("config.php");

if (!isset($_SESSION["id_membre"])) {
    header("Location: login.php");
    exit();
}

$id_membre = $_SESSION["id_membre"];
$categorie_choisie = $_GET['categorie'] ?? '';
$nom_recherche = $_GET['nom_objet'] ?? '';

$categories = ["esthétique", "bricolage", "mécanique", "cuisine"];

$sql_empruntes = "
    SELECT o.nom_objet, o.id_objet, c.nom_categorie, e.date_emprunt, e.date_retour
    FROM emprunt e
    JOIN objet o ON e.id_objet = o.id_objet
    JOIN categorie_objet c ON o.id_categorie = c.id_categorie
    WHERE e.id_membre = $id_membre
    ORDER BY e.date_emprunt DESC
    LIMIT 10
";
$res_empruntes = mysqli_query($bdd, $sql_empruntes);

$sql_disponibles = "
    SELECT o.*, c.nom_categorie
    FROM objet o
    JOIN categorie_objet c ON o.id_categorie = c.id_categorie
    WHERE o.id_objet NOT IN (
        SELECT id_objet FROM emprunt
        WHERE CURDATE() <= date_retour
    )
";

if ($categorie_choisie) {
    $categorie_choisie = mysqli_real_escape_string($bdd, $categorie_choisie);
    $sql_disponibles .= " AND c.nom_categorie = '$categorie_choisie'";
}

if ($nom_recherche !== '') {
    $nom_recherche = mysqli_real_escape_string($bdd, $nom_recherche);
    $sql_disponibles .= " AND o.nom_objet LIKE '%$nom_recherche%'";
}

$res_disponibles = mysqli_query($bdd, $sql_disponibles);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Objets de <?= htmlspecialchars($_SESSION['nom']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css" />
</head>
<body>

<div class="container py-4">
<h2 class="text-center text-white mb-4">
  Bienvenue, <?= htmlspecialchars($_SESSION['nom']) ?>
  <a href="fiche_membre.php?id=<?= $id_membre ?>" class="btn btn-sm btn-primary ms-3" style="vertical-align: middle;">
    Ma fiche
  </a>
</h2>

    <h4 class="text-white">Vos 10 derniers objets empruntés</h4>
    <div class="row mb-5">
        <?php if (mysqli_num_rows($res_empruntes) == 0): ?>
            <p class="text-light">Aucun objet emprunté.</p>
        <?php else: ?>
            <?php 
            $i = 1;
            while ($obj = mysqli_fetch_assoc($res_empruntes)) : 
                $image_path = "img/" . $i . ".jpg";
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow">
                        <a href="fiche_objet.php?id=<?= $obj['id_objet'] ?>">
                            <img src="<?= $image_path ?>" class="card-img-top" alt="Image de l'objet <?= htmlspecialchars($obj['nom_objet']) ?>" style="height: 200px; object-fit: cover;">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($obj['nom_objet']) ?>
                                <span class="badge bg-secondary"><?= htmlspecialchars($obj['nom_categorie']) ?></span>
                            </h5>
                            <span class="badge bg-warning text-dark">Statut : Emprunté</span>
                            <p class="mt-2 mb-0 text-muted">
                                📅 <strong>Date emprunt :</strong> <?= htmlspecialchars($obj['date_emprunt']) ?><br>
                                ⏳ <strong>Retour prévu :</strong> <?= htmlspecialchars($obj['date_retour']) ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php 
            $i++; 
            endwhile; 
            ?>
        <?php endif; ?>
    </div>

    <form method="GET" class="mb-4 row g-3 align-items-end">
        <div class="col-md-6">
            <label for="categorie" class="form-label text-white">Filtrer par catégorie :</label>
            <select name="categorie" id="categorie" class="form-select" onchange="this.form.submit()">
                <option value="">-- Toutes les catégories --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat ?>" <?= ($categorie_choisie == $cat) ? 'selected' : '' ?>>
                        <?= ucfirst($cat) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label for="nom_objet" class="form-label text-white">Rechercher un nom d'objet :</label>
            <input type="text" name="nom_objet" id="nom_objet" class="form-control" value="<?= htmlspecialchars($nom_recherche) ?>" placeholder="Ex: perceuse">
        </div>
    </form>

    <h4 class="text-white mb-3">Objets disponibles à emprunter</h4>
    <div class="row">
        <?php if (mysqli_num_rows($res_disponibles) == 0): ?>
            <p class="text-light">Aucun objet disponible selon les critères sélectionnés.</p>
        <?php else: ?>
            <?php while ($obj = mysqli_fetch_assoc($res_disponibles)) : ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow">
                        <a href="fiche_objet.php?id=<?= $obj['id_objet'] ?>">
                            <img src="img/<?= htmlspecialchars($obj['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($obj['nom_objet']) ?>" style="height: 200px; object-fit: cover;">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($obj['nom_objet']) ?>
                                <span class="badge bg-secondary"><?= htmlspecialchars($obj['nom_categorie']) ?></span>
                            </h5>
                            <span class="badge bg-success">Disponible</span>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
