<?php

$c->println('Test d\'accès à la base de données...', $c::COLOR_LIGHT_CYAN);

if ($oa->bddIsOk($msg)){
    $c->println('La base de données est bien accessible!', $c::BG_GREEN);
}else{
    $c->println("Impossible d'accéder à la base de données : $msg", $c::BG_RED);
}