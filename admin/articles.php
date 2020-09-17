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
    <h1>Administration des produits</h1>
    <table border="1">
        <tr>
            <th>id</th>
            <th>nom</th>
            <th>prix</th>
            <th>action</th>
        </tr>
        <?php
            require "../connexion.php"; 
            $req = $bdd->query("SELECT * FROM articles");
            while($don = $req->fetch()){
                echo "<tr>";
                    echo "<td>".$don['id']."</td>";
                    echo "<td>".$don['nom']."</td>";
                    echo "<td>".$don['prix']."€</td>";
                    echo "<td>";
                        echo "<a href=''>Modifier</a>";
                        echo "<a href=''>Supprimer</a>";
                    echo "</td>";
                echo "</tr>";
            }
            $req->closeCursor();
            
        ?>


    </table>
</body>
</html>