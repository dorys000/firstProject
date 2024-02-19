<?php
require('connexion.php');
$reqAuthor = $conn->prepare("SELECT * FROM Artiste");
$reqAuthor->execute();
$artiste = $reqAuthor->fetchAll();
$reqCategorie = $conn->prepare("SELECT * FROM Categorie");
$reqCategorie->execute();
$categorie = $reqCategorie->fetchAll();

if (isset($_POST['save'])) {
    if (!empty($_POST['oeuvre']) && !empty($_POST['detail']) && !empty($_POST['categorie'])) {
        $oeuvre = htmlspecialchars($_POST['oeuvre']);
        $author = htmlspecialchars($_POST['auteur']);
        $detail = htmlspecialchars($_POST['detail']);
        $annee = htmlspecialchars($_POST['annee']);
        $cat = htmlspecialchars($_POST['categorie']);

        $insert = $conn->prepare("INSERT INTO `Oeuvre`(`Nom`, `description`, `annee`, `idArtiste`, `idCategorie`) VALUES ('$oeuvre','$detail','$annee','$author','$cat')");
        if ($insert->execute()) {
            echo '<script>alert("ok")</script>';
            header("Location: index.php");
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TRESOR</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
</head>

<body>
    <div style="width: 700px; margin: auto; padding: 50px 50px; ">
        <p class="h2 text-center text-align-center">Enregistrement d'une oeuvre</p>
        <form class="form" method="POST">
            <label for="oeuvre" class="form-label">Oeuvre</label>
            <input type="text" id="oeuvre" name="oeuvre" class="form-control">
            <br>
            <label for="auteur" class="form-label">Auteur</label>
            <select name="auteur" id="" class="form-control">
                <option value="0">Selectionner l'auteur de l'oeuvre</option>
                <?php
                foreach ($artiste as $key => $auteur) {
                ?>
                    <option value="<?= $auteur['idArtiste'] ?>"><?= $auteur['nom'] . ' ' . $auteur['prenom'] ?></option>
                <?php
                }
                ?>
            </select>
            <br>
            <label for="annee" class="form-label">Année</label>
            <input type="tel" id="annee" name="annee" class="form-control">
            <br>
            <label for="annee" class="form-label">Description</label>
            <textarea id="detail" name="detail" class="form-control"></textarea>
            <br>
            <label for="categorie" class="form-label">Categorie</label>
            <select name="categorie" id="" class="form-control">
                <option value="0">Sélectionner une categorie</option>
                <?php
                foreach ($categorie as $key => $cat) {
                ?>
                    <option value="<?= $cat['idCategorie'] ?>"><?= $cat['nomCategorie'] ?></option>
                <?php
                }
                ?>
            </select>
            <br>
            <div style="text-align: center;">
                <input type="submit" name="save" value="Enregistrer" style="width: 150px;" class="btn btn-primary">

            </div>

        </form>
    </div>

    <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
</body>

</html>