<?php
    session_start();
    if(!isset($_SESSION['login'])){
        header("LOCATION:403.php");
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
    <h1>Ajouter un produit</h1>
    <form action="treatmentAddProduct.php" method="POST" enctype="multipart/form-data">
        <div>
            <label for="nom">Nom: </label>
            <input type="text" name="nom" id="nom" value="">
        </div>
        <div>
            <label for="prix">Prix: </label>
            <input type="number" step="0.01" name="prix" id="prix" value="">
        </div>
        <div>
            <label for="description">Description: </label>
            <textarea name="description" id="description" cols="30" rows="10"></textarea>
        </div>
        <div>
            <label for="image">Image: </label>
            <input type="file" name="image" id="image">
        </div>
        <div>
            <input type="submit" value="Ajouter">
        </div>
    </form>
</body>
</html>