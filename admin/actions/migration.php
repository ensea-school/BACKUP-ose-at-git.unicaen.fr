<?php

// Script provisoire : il aura disparu à la prochaine version!!!

$oa->oldVersion = $oa->purgerVersion($c->getArg(2));
$oa->version = $oa->purgerVersion($c->getArg(3));
$oa->run('update-bdd');
