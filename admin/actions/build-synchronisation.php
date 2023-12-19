<?php
$c->println('Reconstruction des vues différentielles et des procédures de mise à jour ...');

$args = 'UnicaenImport MajVuesFonctions';
$c->passthru("php " . getcwd() . "/public/index.php " . $args);