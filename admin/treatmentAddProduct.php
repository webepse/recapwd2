<?php 
    session_start();
    if(!isset($_SESSION['login'])){
        header("LOCATION:403.php");
    }

    if(isset($_POST['nom'])){
        $err=0;
        // gestion des erreurs
        if(!empty($_POST['nom'])){
            $nom=htmlspecialchars($_POST['nom']);
        }else{
            $err=1;
        }

        if(!empty($_POST['prix'])){
            $prix=htmlspecialchars($_POST['prix']);
        }else{
            $err=2;
        }

        if(!empty($_POST['description'])){
            $description=htmlspecialchars($_POST['description']);
        }else{
            $err=3;
        }



        // gestion d'insertion 
        if($err==0){
            require "../connexion.php";
            $insert = $bdd->prepare("INSERT INTO articles(nom,prix,description) VALUES(:nom,:prix,:descri)");
            $insert->execute([
                ":nom"=>$nom,
                ":prix"=>$prix,
                ":descri"=>$description
            ]);
            $insert->closeCursor();
            header("LOCATION:articles.php?insert=success");
        }else{
            header("LOCATION:addProduct.php?err=".$err);
        }




    }else{
        header("LOCATION:addProduct.php");
    }




?>