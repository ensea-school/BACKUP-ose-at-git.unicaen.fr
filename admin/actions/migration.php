<?php

// Script provisoire : il aura disparu à la prochaine version!!!
$c->println('Mise à jour automatique de la base de données', $c::COLOR_LIGHT_CYAN);

$oa->oldVersion = $oa->purgerVersion($c->getArg(2));
$oa->version = $oa->purgerVersion($c->getArg(3));

$c->println('ancienne version='.$oa->oldVersion);
$c->println('nouvelle version='.$oa->version);

$oa->migration('pre');
$oa->run('update-bdd');
$oa->migration('post');