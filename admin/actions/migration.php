<?php

// Script provisoire : il aura disparu à la prochaine version!!!
$c->println('Mise à jour automatique de la base de données', $c::COLOR_LIGHT_CYAN);

$oa->migration('pre');
$oa->run('update-bdd');
$oa->migration('post');