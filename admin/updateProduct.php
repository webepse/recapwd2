<?php
    session_start();
    if(!isset($_SESSION['login'])){
        header("LOCATION:403.php");
    }

    if(isset($_GET['id'])){
        $id=htmlspecialchars($_GET['id']);
        require "../connexion.php";
        $req=$bdd->prepare("SELECT * FROM articles WHERE id=?");
        $req->execute([$id]);
     
        if(!$don=$req->fetch()){
            header("LOCATION:articles.php"); 
        }

        $req->closeCursor();

    }else{
        header("LOCATION:articles.php");
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Modifier le produit: <?= $don['nom'] ?></h1>
    <form action="treatmentUploadProduct.php?id=<?= $don['id'] ?>" method="POST" enctype="multipart/form-data">
        <!--
        <div>
            <input type="hidden" name="id" value="<?= $don['id'] ?>">
        </div>
        -->
        <div>
            <label for="nom">Nom: </label>
            <input type="text" name="nom" id="nom" value="<?= $don['nom'] ?>">
        </div>
        <div>
            <label for="prix">Prix: </label>
            <input type="number" step="0.01" name="prix" id="prix" value="<?= $don['prix'] ?>">
        </div>
        <div>
            <label for="description">Description: </label>
            <textarea name="description" id="description" cols="30" rows="10"><?= $don['description'] ?></textarea>
        </div>
        <div>
            <p>Nom de l'image:  <?= $don['image'] ?></p>
            <label for="image">Image: </label>
            <input type="file" name="image" id="image">
        </div>
        <div>
            <input type="submit" value="Modifier">
        </div>
    </form>
</body>
</html>