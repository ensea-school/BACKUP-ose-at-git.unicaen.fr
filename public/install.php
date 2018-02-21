<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Meta -->
    <meta charset="utf-8">

    <title>Installation - OSE</title>
</head>

<body>
<div id="navbar">
    <h1 class="title">OSE - Mode Installation</h1>
    <p class="info">&nbsp;
    </p>
</div>

<div id="contenu">

    <h1>Contrôle des dépendences</h1>
    <p class="lead">

        <?php

        use Application\Connecteur\LdapConnecteur;
        use Application\Constants;
        use Doctrine\ORM\EntityManager;

        $installateur = new Installateur();
        $installateur->affTests();

        ?>
    </p>

</div>
<style>

    body {
        margin: 0px;
        padding: 0px;
    }

    #navbar {

        font-size: 14px;
        line-height: 1.42857;
        color: #333;
        background-image: linear-gradient(to bottom, #3C3C3C 0px, #222 100%);
        background-repeat: repeat-x;
        background-color: #222;
        border: 1px #080808 solid;
        box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
    }

    #navbar h1 {
        float: left;
        color: #9D9D9D;
        margin-top: 5px;
        margin-left: 5px;
    }

    #navbar .info {
        color: #555;
        text-align: right;
        margin-right: 5px;
    }

    #contenu {
        margin: 2em;
        padding: 1em;
        border-radius: 6px;
        border: 1px #ccc solid;
        box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
    }

    #contenu h1 {
        margin-top: -0.5em;
        margin-left: -0.5em;
        margin-right: -0.5em;
        padding-left: 1em;
        padding-right: 1em;
        padding-top: 10px;
        padding-bottom: 10px;
        background-color: #f0f0f0;
        border-bottom: 1px #ccc solid;
    }

    table.test {
        border-collapse: collapse;
        border: 1px gray solid;
        width: 80%;
        margin: auto;
    }

    table.test td.test {
        width: 70%;
    }

    table.test td.res {
        width: 30%;
        text-align: center;
    }

    table.test tr.passed td.res {
        background-color: lightgreen;
    }

    table.test tr.error td.res {
        background-color: lightpink;
    }

</style>
</body>
</html>
<?php die();





class Installateur
{
    private $stop = false;



    private function makeTests()
    {
        return [
            'Configuration d\'Apache'                        => [
                'Réécriture d\'URL activée' => function () {
                    return in_array('mod_rewrite', apache_get_modules()) ? true : 'Non activée';
                },
            ],
            'Modules PHP nécessaires'                        => [
                'Curl'                 => function () {
                    return in_array('curl', get_loaded_extensions()) ? true : 'Non installé';
                },
                'Intl'                 => function () {
                    return in_array('intl', get_loaded_extensions()) ? true : 'Non installé';
                },
                'Json'                 => function () {
                    return in_array('json', get_loaded_extensions()) ? true : 'Non installé';
                },
                'Ldap'                 => function () {
                    return in_array('ldap', get_loaded_extensions()) ? true : 'Non installé';
                },
                'MbString'             => function () {
                    return in_array('mbstring', get_loaded_extensions()) ? true : 'Non installé';
                },
                'MCrypt'               => function () {
                    return in_array('mcrypt', get_loaded_extensions()) ? true : 'Non installé';
                },
                'Zend OPcache'         => function () {
                    return in_array('Zend OPcache', get_loaded_extensions()) ? true : 'Non installé';
                },
                'XML'                  => function () {
                    return in_array('xml', get_loaded_extensions()) ? true : 'Non installé';
                },
                'ZIP'                  => function () {
                    return in_array('zip', get_loaded_extensions()) ? true : 'Non installé';
                },
                'OCI8 (Pilote Oracle)' => function () {
                    return in_array('oci8', get_loaded_extensions()) ? true : 'Non installé';
                },
            ],
            'Environnement'                                  => [
                'Droit d\'écriture sur le dossier data/cache' => function () {
                    $cacheDir = __DIR__ . '/../data/cache';

                    return file_exists($cacheDir) ? true : 'Répertoire ' . $cacheDir . ' non trouvé';
                },
                'Présence d\'UnoConv'                         => function () {
                    return substr(shell_exec('unoconv --version'), 0, 7) == 'unoconv' ? true : 'Commande "unoconv" introuvable';
                },
            ],
            'Configuration' => [
                'Fichier de configuration local' => function () {
                    $configFile = __DIR__ . '/../config/autoload/application.local.php';
                    if (!file_exists($configFile)) {
                        $this->stop = true;

                        return '<abbr title="Renommez config/autoload/application.local.php.dist en config/autoload/application.local.php">Fichier introuvable</abbr>';
                    }

                    return true;
                },
                'Accès à la base de données' => function () {
                    $container = Application::$container;

                    /** @var EntityManager $entityManager */
                    $entityManager = $container->get(Constants::BDD);

                    try{
                        $entityManager->beginTransaction();
                        return true;
                    }catch(\Exception $e){
                        $this->stop = true;
                        return $e->getMessage();
                    }
                },
                'Accès au serveur LDAP' => function(){
                    $container = Application::$container;

                    /** @var LdapConnecteur $ldap */
                    $ldap = $container->get(LdapConnecteur::class);

                    try{
                        @$ldap->rechercheUtilisateurs('e');
                        return true;
                    }catch(\Exception $e){
                        $this->stop = true;
                        return $e->getMessage();
                    }
                }
            ],
        ];
    }



    public function affTests()
    {
        $tests = $this->makeTests();
        foreach ($tests as $cat => $ts) {
            echo '<h2>' . $cat . '</h2>';
            echo '<table class="test" style="" border="1" cellpadding="5">';
            foreach ($ts as $test => $res) {
                if ($this->stop) {
                    $r = 'Test impossible';
                } else {
                    $r = $res();
                }

                echo '<tr class="' . ($r === true ? 'passed' : 'error') . '"><td class="test">' . $test . '</td><td class="res">' . ($r === true ? 'OK' : $r) . '</td></tr>';
            }
            echo '</table>';
        }
    }
}