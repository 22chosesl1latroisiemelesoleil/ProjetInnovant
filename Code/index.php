<?php
require('controller/controller.php');
try {
    if (isset($_GET['action'])) {
        if ($_GET['action'] == 'projetInnovant') {
	            projetInnovant();
        }
    }
    else {
        accueil();
    }
}

catch(Exception $e) {
    // echo 'Erreur : ' . $e->getMessage();
    $errorMessage = $e->getMessage();
    // $errorMessage = "Test du message d'erreur";
    require('view/errorView.php');
}





        
   
    
