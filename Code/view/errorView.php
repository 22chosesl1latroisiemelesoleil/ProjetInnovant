<?php $title = 'Error View ...'; ?>
<?php ob_start(); ?>
    <?php $nomdelaview = 'Error View ...'; ?>
    <p>L'erreur est la suivante : <?= $errorMessage ?></p>
<?php $content = ob_get_clean(); ?>
<?php require('view/template.php'); ?>

