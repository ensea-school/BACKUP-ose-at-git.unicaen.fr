<?php

$c->println('Test d\'accès à la base de données...', $c::COLOR_LIGHT_CYAN);

try {
    $ok = $oa->bddIsOk();
}catch(\Exception $e){
    $c->println($e->getMessage());
    $ok = false;
}

if ($ok){
    $c->println('La base de données est bien accessible!', $c::BG_GREEN);
}else{
    $c->println('Impossible d\'accéder à la base de données!', $c::BG_RED);
}