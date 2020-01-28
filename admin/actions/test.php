<?php

//$bdd    = new \BddAdmin\Bdd(Config::get('bdds', 'dev-local'));
//$schema = new \BddAdmin\Schema($bdd);

$ts = $c->exec("git branch", false);

$branch = null;
foreach ($ts as $t) {
    if (0 === strpos($t, '*')) {
        $branch = trim(substr($t, 1));
        break;
    }
}

var_dump($branch);