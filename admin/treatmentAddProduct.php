<?php 
    session_start();
    if(!isset($_SESSION['login'])){
        header("LOCATION:403.php");
    }
    // vérification si le formulaire est envoyé
    if(isset($_POST['nom'])){
        $err=0; // initialisation de la variable pour indiquer s'il y a une erreur
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
        // on teste s'il y a une erreur 
        if($err==0){
            require "../connexion.php"; // connexion à la bdd
            // tester s'il y a une image envoyée dans le formulaire (on doit regarder le fichier temporaire)
            if(empty($_FILES['image']['tmp_name'])){
                // insertion dans la bdd sans image
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
                $dossier = '../images/'; // dossier de destination - Attention au / à la fin sinon ../imagesNOMDUFICHIER.EXT
                $fichier = basename($_FILES['image']['name']); // récupère la composante finale d'un chemin de fichier
                $taille_maxi = 2000000;
                $taille = filesize($_FILES['image']['tmp_name']); // récupère la taille du fichier
                $extensions = ['.png','.jpg','.jpeg']; // les extensions autorisées
                $extension = strrchr($_FILES['image']['name'], '.');  // on recherche la dernière occurance du point dans notre fichier pour trouver l'extension 
                
                
                
                if(!in_array($extension, $extensions)) // on teste si l'extension du fichier uploadé correspond aux extensions autorisées
                {
                    $erreur = 'Vous devez uploader un fichier de type png, jpg, jpeg';
                   
                }
                if($taille>$taille_maxi)		// on teste la taille de notre fichier 
                {
                    $erreur = 'Le fichier dépasse la taille autorisée';
                }
                
                if(!isset($erreur)) // Si tous les tests sont OK (non existance de la variable $erreur), on passe à l'étape de l'upload sur notre serveur
                {
                    //On formate le nom du fichier, strtr remplace tout les KK speciaux en normaux suivant notre liste 
                    $fichier = strtr($fichier, 
                        'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
                        'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                    $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier); // REGEX - preg_replace remplace les caractères spéciaux par un - 
                    $fichiercptl=rand().$fichier;
                    if(move_uploaded_file($_FILES['image']['tmp_name'], $dossier . $fichiercptl)) // la fonction retourne True si l'upload a été realisé 
                    {
                        // insertion dans la bdd avec l'image
                        $insert = $bdd->prepare("INSERT INTO articles(nom,prix,image,description) VALUES(:nom,:prix,:image,:descri)");
                        $insert->execute([
                            ":nom"=>$nom,
                            ":prix"=>$prix,
                            ":image"=>$fichiercptl,
                            ":descri"=>$description
                        ]);
                        $insert->closeCursor();
                        
                        if($extension==".png"){
                            header("LOCATION:redimpng.php?image=".$fichiercptl);
                        }else{
                            header("LOCATION:redim.php?image=".$fichiercptl);
                        }
                        
                            
                    }
                    else //Sinon (la fonction retourne FALSE).
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