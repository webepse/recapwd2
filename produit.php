<?php
    if(isset($_GET['id'])){
        $id=htmlspecialchars($_GET['id']);
        require "connexion.php";
        $req=$bdd->prepare("SELECT * FROM articles WHERE id=?");
        $req->execute([$id]);

     
        if(!$don=$req->fetch()){
            header("LOCATION:404.php"); 
        }

        $req->closeCursor();

    }else{
        header("LOCATION:404.php");
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produit: <?= $don['nom'] ?></title>
</head>
<body>
    <h1><?= $don['nom'] ?></h1>
    <h2>Prix: <?= $don['prix'] ?>€</h2>
    <div><?= nl2br($don['description']) ?></div>
</body>
</html>