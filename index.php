<?php
//include 'connexion.php';
require('connexion.php');
$req = $conn->prepare("SELECT * FROM oeuvre, artiste, categorie WHERE artiste.idArtiste = oeuvre.idArtiste AND categorie.idCategorie = oeuvre.idCategorie ORDER BY oeuvre.idOeuvre");
//$req = $conn->prepare("SELECT * FROM Oeuvre INNER JOIN Artiste,Categorie WHERE Oeuvre.idArtiste = Artiste.idArtiste AND Oeuvre.idArtiste = Categorie.idCategorie");
$req->execute();
$rows = $req->fetchAll();

$reqAuthor = $conn->prepare("SELECT * FROM artiste");
$reqAuthor->execute();
$artiste = $reqAuthor->fetchAll();
$reqCategorie = $conn->prepare("SELECT * FROM categorie");
$reqCategorie->execute();
$categorie = $reqCategorie->fetchAll();
//modification
if (isset($_POST['edit'])) {
    if (!empty($_POST['oeuvre']) && !empty($_POST['categorie'])) {
        $oeuvre = htmlspecialchars($_POST['oeuvre']);
        $idOeuvre = htmlspecialchars($_POST['idOeuvre']);
        $author = htmlspecialchars($_POST['auteur']);
        $annee = htmlspecialchars($_POST['annee']);
        $cat = htmlspecialchars($_POST['categorie']);

        $insert = $conn->prepare("UPDATE `oeuvre` SET `Nom`='$oeuvre',`annee`='$annee',`idArtiste`='$author',`idCategorie`='$cat' WHERE idOeuvre='$idOeuvre'");
        if ($insert->execute([])) {
            header("Location: {$_SERVER['HTTP_REFERER']}");
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <title>Document</title>
</head>

<body>

    <div class="container">
        <p class="h2 text-center text-align-center" style="margin-top: 20px;">Liste des oeuvres</p>
        <div class="container">
            <div>
                <form action="create.php" method="post">
                    <button style="width: 150px;" type="" class="btn btn-primary">Create</button>
                </form>

            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Oeuvre</th>
                    <th scope="col">Auteur</th>
                    <th scope="col">Annee</th>
                    <th scope="col">Categorie</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($rows as $row) {
                ?>
                    <tr>
                        <td> <?= $row['Nom']; ?></td>
                        <td> <?= $row['nom'] . ' ' . $row['prenom']; ?></td>
                        <td> <?= $row['annee']; ?></td>
                        <td> <?= $row['nomCategorie']; ?></td>
                        <td>
                            <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $row['idOeuvre'] ?>">Modifier</button>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModalCenter<?= $row['idOeuvre'] ?>">Supprimer</button>
                        </td>
                        <div class="modal fade" id="exampleModal<?= $row['idOeuvre'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Modification de l'oeuvre N° <?= $row['idOeuvre'] ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form class="form" method="POST">
                                            <input type="hidden" name="idOeuvre" value="<?= $row['idOeuvre'] ?>" class="form-control">
                                            <label for="oeuvre" class="form-label">Oeuvre</label>
                                            <input type="text" id="oeuvre" name="oeuvre" value="<?= $row['Nom'] ?>" class="form-control">
                                            <label for="auteur" class="form-label">Auteur</label>
                                            <select name="auteur" id="" class="form-control">
                                                <option value="<?= $row['idArtiste'] ?>"><?= $row['nom'] . ' ' . $row['prenom'] ?></option>
                                                <?php
                                                foreach ($artiste as $key => $auteur) {
                                                ?>
                                                    <option value="<?= $auteur['idArtiste'] ?>"><?= $auteur['nom'] . ' ' . $auteur['prenom'] ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                            <label for="annee" class="form-label">Année</label>
                                            <input type="tel" id="annee" name="annee" value="<?= $row['annee'] ?>" class="form-control">
                                            <label for="categorie" class="form-label">Categorie</label>
                                            <select name="categorie" id="" class="form-control">
                                                <option value="<?= $row['idCategorie'] ?>"><?= $row['nomCategorie'] ?></option>
                                                <?php
                                                foreach ($categorie as $key => $cat) {
                                                ?>
                                                    <option value="<?= $cat['idCategorie'] ?>"><?= $cat['nomCategorie'] ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                            <br>

                                    </div>
                                    <div class="modal-footer">
                                        <input type="submit" name="edit" value="Enregistrer" class="btn btn-primary">
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </tr>
                    <div class="modal fade" id="exampleModalCenter<?= $row['idOeuvre'] ?>" tabindex="-1" aria-labelledby="exampleModalLabelSup" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabelSup">Suppression de l'oeuvre "<?= $row['Nom'] ?>"</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="text-center p2">Vous êtes sur le point de supprimer l'oeuvre "<?= $row['Nom'] ?>", voulez-vous continuer ?</p>
                                    <input type="hidden" name="action" id="id" value="<?= $row['idOeuvre'] ?>">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">NON</button>
                                    <button type="button" class="btn btn-success" onclick="envoyerReponse()" data-bs-dismiss="modal">OUI</button>
                                </div>
                            </div>
                        </div>
                    <?php
                }
                    ?>








            </tbody>

        </table>


    </div>



</body>

<script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<script>
    function envoyerReponse() {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "delete.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        var valeurInput = document.getElementById("id").value;

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Ce code s'exécute lorsque la réponse du serveur est prête
                console.log(xhr.responseText);
                location.reload();
            }
        };

        xhr.send("reponse=oui&id=" + encodeURIComponent(valeurInput));
    }
</script>

</html>