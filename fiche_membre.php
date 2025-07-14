<?php
session_start();
include("config.php");

if (!isset($_SESSION['id_membre'])) {
    header("Location: login.php");
    exit();
}

$id_membre = isset($_GET['id']) ? (int)$_GET['id'] : $_SESSION['id_membre'];

if ($id_membre <= 0) {
    die("ID membre invalide.");
}

$sql_membre = "SELECT * FROM membre WHERE id_membre = $id_membre";
$res_membre = mysqli_query($bdd, $sql_membre);
if (mysqli_num_rows($res_membre) == 0) {
    die("Membre non trouvé.");
}
$membre = mysqli_fetch_assoc($res_membre);

$sql_objets = "
    SELECT o.*, c.nom_categorie 
    FROM objet o
    LEFT JOIN categorie_objet c ON o.id_categorie = c.id_categorie
    WHERE o.id_membre = $id_membre
    ORDER BY c.nom_categorie, o.nom_objet
";
$res_objets = mysqli_query($bdd, $sql_objets);

// Regrouper objets par catégorie
$objets_par_categorie = [];
while ($objet = mysqli_fetch_assoc($res_objets)) {
    $cat = $objet['nom_categorie'] ?? 'Sans catégorie';
    $objets_par_categorie[$cat][] = $objet;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Fiche membre - <?= htmlspecialchars($membre['nom']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container py-4">

    <h2>Fiche du membre : <?= htmlspecialchars($membre['nom']) ?></h2>

    <p><strong>Email :</strong> <?= htmlspecialchars($membre['email']) ?></p>
    <p><strong>Téléphone :</strong> <?= htmlspecialchars($membre['telephone']) ?></p>
   

    <hr />

    <h3>Objets possédés par <?= htmlspecialchars($membre['nom']) ?></h3>

    <?php if (empty($objets_par_categorie)): ?>
        <p>Aucun objet enregistré pour ce membre.</p>
    <?php else: ?>
        <?php foreach ($objets_par_categorie as $categorie => $objets): ?>
            <h4><?= htmlspecialchars($categorie) ?></h4>
            <ul class="list-group mb-4">
                <?php foreach ($objets as $objet): ?>
                    <li class="list-group-item">
                        <a href="fiche_objet.php?id=<?= $objet['id_objet'] ?>">
                            <?= htmlspecialchars($objet['nom_objet']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endforeach; ?>
    <?php endif; ?>

    <a href="liste_objet.php" class="btn btn-secondary">Retour à la liste</a>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
