<?php $title = 'Projet innovant'; ?>
<?php ob_start(); ?>
    <?php $nomdelaview = 'Projet innovant'; ?>   
    <h1>Liens sémantiques entre textes</h1>
        <h2>Téléversez les textes à comparer </h2>
        <form action="index.php?action=projetInnovant" method="POST" enctype="multipart/form-data">
        <input type="file" name="file">
        <button type="submit">Indexer</button>
        </form>
        <?php
            $tmpName = $_FILES['file']['tmp_name'];
            $name = $_FILES['file']['name'];
            $error = $_FILES['file']['error'];
            $pathInfo = pathinfo($name);
            $extension = $pathInfo['extension'];

            $valide = array('txt', 'odt','doc');
            if (in_array($extension, $valide)){ 
                if (is_uploaded_file($tmpName)) {
                    echo '<br>Document téléchargé par HTTP POST<br>';
                    $path = 'televersement/';
                    $pathComplet=  $path.$name;
                    $televersement = move_uploaded_file($tmpName, $pathComplet);
                    if($televersement){ 
                        echo "Document téléversé <br>";
                        echo("Nom du document à indexer : <strong>$name</strong>  <br/>");

                        $indexation = indexer($name, $path);
                        
                    } else{ 
                        echo "Erreur téléversement<br>";
                    }

                } else {
                    echo "Erreur téléchargement <br>";
                }

            } else { 
                // echo "extension non valide";
            }


        
        
        ?>  

        <h2>Comparer les textes</h2>
        <?php 
                // echo 'Lecture récursive<br>';
                $path = "televersement";
                $docDispo = explorerDir($path);  
                // var_dump($docDispo);

                if(empty($docDispo)){
                    echo "<br/>Pas de documents à comparer. Télécharger les documents que vous voulez comparer.  <br/>";
                }
                else{
                    // echo("Liste des documents disponibles. <br/>");
                    ?>   
                    <h3>Choisissez 2 textes à comparer </h3>
                    <form action="index.php?action=projetInnovant" method="post">
                        <?php
                            foreach ($docDispo as $value) {
                                echo '<input type="checkbox" name="texteDispo[]" value="'.$value.'">'.$value.'<br>';
                            }
                        ?>
                    <input type="submit" name="submit" value="Comparer" /><br />
                    </form>
                    <?php

                }
        ?>   
<?php

?>






<?php
if(isset($_POST['texteDispo'])){
    $checked = $_POST['texteDispo'];
    $nb_checked = count($checked);

    if($nb_checked===2){
        foreach($_POST['texteDispo'] as $valeur){
            $textesAComparer[] = $valeur;
        }

        echo '<br>Nom des textes à comparer : <br>';
        $texteA = $textesAComparer[0];
        $texteB = $textesAComparer[1];

        echo 'Texte A : ';
        echo $texteA;
        echo '<br>Texte B : ';
        echo $texteB;
        echo '<br>';

        $infoTA = getSource($texteA);
        $infoTB = getSource($texteB);


        // COMPARAISON LANGUE
        $langueA = $infoTA[0]['langue'];
        $langueB = $infoTB[0]['langue'];

        if(strcmp($langueA, $langueB) !== 0){
            $memeLangue = false;
            $messageLangue = 'Les 2 documents ne sont pas dans la même langue. <br>';
        }
        else{
            $memeLangue = true;
            $messageLangue = 'Les 2 documents sont dans la même langue. <br>';
        }
        // ----------------------------------------------
        ?>
        <h3>Comparaison : étape 1</h3>
        <p>Comparaison de la langue des textes</p>
        <?php
                echo 'Langue Texte A : ';
                echo $langueA;
                echo '<br>Langue Texte B : ';
                echo $langueB;
                echo '<br>';

                if($memeLangue){
                    echo "<br>Les 2 textes sont écrits dans la même langue. <br> Nous allons donc continuer la comparaison (étape 2). <br>";
                }
                else{
                    echo "<br>Les 2 textes sont écrits dans une langue différente. On ne peut donc pas créer de lien sémantique. <br>";
                    $diagnostiqueFinal = 'Pas de lien sémantique trouvé. <br>';
                }
                if($memeLangue){
                    // ----------------------------------------------
                    ?>
                    <h3>Comparaison : étape 2</h3>
                    <p>Comparaison des mots du document</p>
                    <?php

                    $indexationTA = getIndexation($texteA);
                    $indexationTB = getIndexation($texteB);


                    foreach($indexationTA as $key => $row) {
                        $mot = $row['mot'];
                        $tabMotsA[] = $mot;
                    }

                    foreach($indexationTB as $key => $row) {
                        $mot = $row['mot'];
                        $tabMotsB[] = $mot;
                    }

                    $compteur = 0;
                    foreach($tabMotsA as $motA) {
                        if (in_array($motA, $tabMotsB)) {
                            $tabMotsCommuns[] = $motA;
                            $compteur++;
                        }
                    }

                    echo "Sur les 5 mots les + fréquents des 2 textes, il y a $compteur mots communs trouvés <br>";

                    if($compteur>0){
                        echo "Mots communs trouvés : <br>";
                        foreach($tabMotsCommuns as $motCommun) {
                            echo $motCommun;
                            echo '<br>';
                        }
                        $implodeMotsCommuns = implode(",", $tabMotsCommuns); 
                        $insertLienSemantique = insertLienSemantique($texteA, $texteB, $implodeMotsCommuns);
                        $diagnostiqueFinal = 'Liens sémantiques trouvés. <br>';
                    }
                    else{
                        echo "Les 2 textes n'ont pas de mot en commun. On ne peut donc pas créer de lien sémantique. <br>";
                        $diagnostiqueFinal = 'Pas de lien sémantique trouvé. <br>';
                    }
                }
        ?>
        <h3>Résultat de la comparaison : <?php echo $diagnostiqueFinal?></h3>
        <?php

    }
    else{
        echo "<br>Vous devez saisir 2 textes dans la liste. ";
    }
}
else{
    // echo "En attente de votre choix";
}

?>





<?php $content = ob_get_clean(); ?>
<?php require('view/template.php'); ?>





