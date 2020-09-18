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

    if(isset($_GET['delete'])){
        if(!empty($don['image'])){
            unlink("../images/".$don['image']);
        }

        $delete=$bdd->prepare("DELETE FROM articles WHERE id=?");
        $delete->execute([$id]);
        $delete->closeCursor();
        header("LOCATION:articles.php?delete=success");
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
    <h1>Supprimer? <?= $don['nom'] ?></h1>
    <h2><a href="deleteProduct.php?id=<?= $don['id'] ?>&delete=accept">Oui</a></h2>
    <h2><a href="articles.php">Non</a></h2>


</body>
</html>