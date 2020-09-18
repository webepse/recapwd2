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
            if(empty($_FILES['image']['tmp_name'])){
                $insert = $bdd->prepare("INSERT INTO articles(nom,prix,description) VALUES(:nom,:prix,:descri)");
                $insert->execute([
                    ":nom"=>$nom,
                    ":prix"=>$prix,
                    ":descri"=>$description
                ]);
                $insert->closeCursor();
                header("LOCATION:articles.php?insert=success");
            }else{
                //traitement du fichier
                $dossier = '../images/';
                $fichier = basename($_FILES['image']['name']);
                $taille_maxi = 2000000;
                $taille = filesize($_FILES['image']['tmp_name']);
                $extensions = array('.png','.jpg','.jpeg');
                $extension = strrchr($_FILES['image']['name'], '.'); 
                
                
                
                if(!in_array($extension, $extensions)) // on test si l'extension du fichier uploadé correspond aux extensions autorisées
                {
                    $erreur = 'Vous devez uploader un fichier de type png, jpg, jpeg';
                   
                }
                if($taille>$taille_maxi)		// on test la taille de notre fichier 
                {
                    $erreur = 'Le fichier dépasse la taille autorisée';
                }
                
                if(!isset($erreur)) // Si tout les tests sont OK on passe à l'étape de l'upload sur notre serveur
                {
                    //On formate le nom du fichier, strtr remplace tout les KK speciaux en normaux suivant notre liste 
                    $fichier = strtr($fichier, 
                        'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
                        'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                    $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier); // preg_replace remplace tout ce qui n'est pas un KK normal en tiret 
                    $fichiercptl=rand().$fichier;
                    if(move_uploaded_file($_FILES['image']['tmp_name'], $dossier . $fichiercptl)) // la fonction renvoie True si l'upload à été realisé 
                    {
                        $insert = $bdd->prepare("INSERT INTO articles(nom,prix,image,description) VALUES(:nom,:prix,:image,:descri)");
                        $insert->execute([
                            ":nom"=>$nom,
                            ":prix"=>$prix,
                            ":image"=>$fichiercptl,
                            ":descri"=>$description
                        ]);
                        $insert->closeCursor();
                        header("LOCATION:articles.php?insert=success");
                            
                    }
                    else //Sinon (la fonction renvoie FALSE).
                    {
                        header("LOCATION:addProduct.php?error=1&upload=echec");
                    }
                }
                else
                {
                    header("LOCATION:addProduct.php?error=1&fich=".$erreur);
                }	


            }
        }else{
            header("LOCATION:addProduct.php?err=".$err);
        }




    }else{
        header("LOCATION:addProduct.php");
    }




?>