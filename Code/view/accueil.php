<?php $title = 'accueilView'; ?>
<?php ob_start(); ?>
        <?php $nomdelaview = 'accueilView'; ?>
        <h3>Accueil</h3>

<?php $content = ob_get_clean(); ?>
<?php require('view/template.php'); ?>

