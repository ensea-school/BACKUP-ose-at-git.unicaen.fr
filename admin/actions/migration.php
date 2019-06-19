<?php

// Script provisoire : il aura disparu à la prochaine version!!!
$c->println('Mise à jour automatique de la base de données', $c::COLOR_LIGHT_CYAN);
$oa->run('update-bdd');

$oa->getBdd()->exec('alter table utilisateur add PASSWORD_RESET_TOKEN varchar2(256) default null');
$oa->getBdd()->exec('create unique index USER_PASSWORD_RESET_TOKEN_UN on utilisateur (PASSWORD_RESET_TOKEN)');