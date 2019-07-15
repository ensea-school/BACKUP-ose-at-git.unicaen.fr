<?php

// Script provisoire : il aura disparu à la prochaine version!!!
$c->println('Mise à jour automatique de la base de données', $c::COLOR_LIGHT_CYAN);

$oa->oldVersion = $oa->purgerVersion($c->getArg(2));
$oa->version = $oa->purgerVersion($c->getArg(3));

$oa->run('update-bdd');