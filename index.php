<?php
    require "connexion.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon site</title>
</head>
<body>
    <h1>Mon site</h1>
    <?php
        $req = $bdd->query("SELECT * FROM articles");
        while($don = $req->fetch()){
            echo "<div><a href='produit.php?id=".$don['id']."'>".$don['nom']."</a></div>";
        }
        $req->closeCursor();
        
    ?>
</body>
</html>