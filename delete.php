<?php
    require('connexion.php');
    if (isset($_POST['reponse']) && isset($_POST['id'])) {
        $reponse = $_POST['reponse'];
        $id = $_POST['id'];
        $delete = $conn->prepare("DELETE FROM Oeuvre WHERE idOeuvre=?");
        if ($delete->execute(array($id))) {
            echo'<script>alert("ok")</script>';
        }
    }
?>
