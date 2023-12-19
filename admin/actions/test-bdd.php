<?php

$c->println('Test d\'accès à la base de données...', $c::COLOR_LIGHT_CYAN);

$bddConf = $oa->config()->get('bdd', null, []);

if (empty($bddConf)) {
    $isOk = false;
} else {
    $driver = $bddConf['driver'] ?? 'Oracle';
    $isOk = true;

    if ('Oracle' == $driver) {
        $cs = $bddConf['host'] . ':' . $bddConf['port'] . '/' . $bddConf['dbname'];
        $characterSet = 'AL32UTF8';
        $conn = @oci_pconnect($bddConf['username'], $bddConf['password'], $cs, $characterSet);
        if (!$conn) {
            $msg = oci_error()['message'];

            $isOk = false;
        } else {
            oci_close($conn);

            $isOk = true;
        }
    }
}


if ($oa->bddIsOk($msg)) {
    $c->println('La base de données est bien accessible!', $c::BG_GREEN);
} else {
    $c->println("Impossible d'accéder à la base de données : $msg", $c::BG_RED);
}