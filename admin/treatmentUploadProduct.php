<?php 
    session_start();
    if(!isset($_SESSION['login'])){
        header("LOCATION:403.php");
    }

    if(isset($_GET['id'])){
        $id=htmlspecialchars($_GET['id']);
    }else{
        header("LOCATION:articles.php");
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
                $upload = $bdd->prepare("UPDATE articles SET nom=:nom, prix=:prix,description=:descri WHERE id=:myid");
                $upload->execute([
                    ":nom"=>$nom,
                    ":prix"=>$prix,
                    ":descri"=>$description,
                    ":myid"=>$id
                ]);
                $upload->closeCursor();
                header("LOCATION:articles.php?update=success");
            }else{

                $reqImg = $bdd->prepare("SELECT * FROM articles WHERE id=?");
                $reqImg->execute([$id]);
                $donImg=$reqImg->fetch();

                if(!empty($donImg['image'])){
                    unlink("../images/".$donImg['image']);
                }

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
                        $upload = $bdd->prepare("UPDATE articles SET nom=:nom, prix=:prix, image=:img,description=:descri WHERE id=:myid");
                        $upload->execute([
                            ":nom"=>$nom,
                            ":prix"=>$prix,
                            ":img"=>$fichiercptl,
                            ":descri"=>$description,
                            ":myid"=>$id
                        ]);
                        $upload->closeCursor();
                        header("LOCATION:articles.php?update=success");
                            
                    }
                    else //Sinon (la fonction renvoie FALSE).
                    {
                        header("LOCATION:uploadProduct.php?id=".$id."&error=1&upload=echec");
                    }
                }
                else
                {
                    header("LOCATION:uploadProduct.php?id=".$id."&error=1&fich=".$erreur);
                }	


            }
        }else{
            header("LOCATION:uploadProduct.php?id=".$id."&err=".$err);
        }




    }else{
        header("LOCATION:uploadProduct.php?id=".$id);
    }




?>