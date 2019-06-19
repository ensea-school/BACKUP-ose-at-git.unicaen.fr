<?php

// Script provisoire : il aura disparu à la prochaine version!!!
$c->println('Mise à jour automatique de la base de données', $c::COLOR_LIGHT_CYAN);
$oa->run('update-bdd');

$oa->getBdd()->exec('ALTER TABLE "DOSSIER" MODIFY ("EMAIL" NULL)');
$oa->getBdd()->exec('ALTER TABLE "UTILISATEUR" ADD ("PASSWORD_RESET_TOKEN" VARCHAR2(256 CHAR) DEFAULT null)');