<?php
    // sécurité pour ne pas afficher d'erreur
    if(isset($_GET['id'])){ // vérifie la présence de la variable get (url) id
        require "connexion.php";
        // protection de la données id 
        $id=htmlspecialchars($_GET['id']); // convertit les caractères spéciaux en entité HTML 

        // requête avec une inconnue, il faut donc préparer la requête à recevoir une inconnue
        $req = $bdd->prepare("SELECT * FROM articles WHERE id=?");
        $req->execute([$id]); // on doit executer la requête avec la variable contenue dans un array
        
        // tester si $don n'a pas de valeur si c'est le cas, redirection
        // on peut faire un if ici car dans ce cas, on sait qu'on ne doit récupèrer qu'une valeur
        if(!$don = $req->fetch()){
            header("LOCATION:404.php");
        }
        $req->closeCursor(); // libère la requête et vide la variable $req mais pas la $don qui  possède toujours sous forme de tableau nos informations si nous n'avons pas été redirigé vers 404

    }else{
        header("LOCATION:index.php"); // redirection vers la page index.php
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
    <?php
        //die(var_dump($don));
        // gestion de l'image 
        if(empty($don['image'])){ // si le champ est vide
            echo "pas d'image";
        }else{ // si le champ n'est pas vide
            echo "<img id='test' src='images/mini_".$don['image']."'>";
        }
    ?>
    
    <h1><?= $don['nom'] ?></h1>
    <h4>Prix: <?= $don['prix'] ?>€</h4>
    <p><?= nl2br($don['description']) ?></p> <!-- afficher en respectant la structure du texte -->
</body>
</html>