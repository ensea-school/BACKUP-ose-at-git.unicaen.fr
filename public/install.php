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

    <h1>Contrôle des dépendances</h1>
    <p class="lead">
        <?php

        use Application\Connecteur\LdapConnecteur;
        use Application\Constants;
        use Application\Entity\Db\Utilisateur;
        use Application\Service\UtilisateurService;
        use Doctrine\ORM\EntityManager;

        $installateur = new Installateur();
        $installateur->affTests();

        ?>
    </p>
</div>
<?php if (!$installateur->stop): ?>
    <div id="contenu">

        <h1>Changer le mot de passe de l'utilisateur OSE</h1>
        <p class="lead">
        <p class="mdp-warning">Le mot de passe doit faire au moins 8 caractères. Veillez à choisir un mot de passe
            suffisemment
            complexe, car l'utilisateur
            ose est par défaut administrateur de l'application.</p>
        <?php $installateur->changementMotDePasse() ?>
        <form method="post">
            <table>
                <tr>
                    <td><label for="mdp1">Mot de passe :</label></td>
                    <td><input type="password" name="mdp1" style="width:35em"/></td>
                </tr>
                <tr>
                    <td><label for="mdp2">Veuillez répéter la saisie :</label></td>
                    <td><input type="password" name="mdp2" style="width:35em"/></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button type="submit">Changer le mot de passe</button>
                    </td>
                </tr>
            </table>
        </form>
        </p>
    </div>
<?php endif; ?>
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

    .mdp-warning {
        background-color: lightgoldenrodyellow;
        border: 1px goldenrod solid;
        border-radius: 6px;
        padding: 10px;
    }

    .mdp-error {
        background-color: lightpink;
        border: 1px red solid;
        border-radius: 6px;
        padding: 10px;
    }

    .mdp-success {
        background-color: lightgreen;
        border: 1px green solid;
        border-radius: 6px;
        padding: 10px;
    }

    b.username {
        background-color: yellow;
        padding-left:1em;
        padding-right:1em;
        border: 1px darkgoldenrod solid;
        font-family: "Courier New", Courier, monospace;
    }

    span.val {
        font-family: "Courier New", Courier, monospace;
    }

</style>
</body>
</html>
<?php die();





class Installateur
{
    public $stop = false;

    /**
     * @var EntityManager
     */
    private $entityManager;



    private function makeTests()
    {
        return [
            'Configuration d\'Apache' => [
                'Réécriture d\'URL activée' => function () {
                    return in_array('mod_rewrite', apache_get_modules()) ? true : 'Non activée';
                },
            ],
            'Modules PHP nécessaires' => [
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
            'Environnement'           => [
                'Droit d\'écriture sur le dossier data/cache' => function () {
                    $cacheDir = __DIR__ . '/../data/cache';

                    return is_writable($cacheDir) ? true : 'Répertoire data/cache non accessible en écriture';
                },
                'Présence d\'UnoConv'                         => function () {
                    return substr(shell_exec('unoconv --version'), 0, 7) == 'unoconv' ? true : 'Commande "unoconv" introuvable';
                },
            ],
            'Configuration'           => [
                'Fichier de configuration local'  => function () {
                    if (!Application::getConfig()) {
                        $this->stop = true;

                        return '<abbr title="Renommez '.Application::LOCAL_APPLICATION_CONFIG_FILE.'.dist en '.Application::LOCAL_APPLICATION_CONFIG_FILE.'">Fichier introuvable</abbr>';
                    }

                    return true;
                },
                'Scheme' => function () {
                    $config = require(__DIR__ . '/../config/autoload/application.local.php');
                    $value = $_SERVER['REQUEST_SCHEME'];

                    if (!Application::getConfig('global','scheme', null)){
                        return 'La variable globale "scheme" n\'est pas configurée. Y placer la valeur <span class="val">'.$value.'</span>';
                    }

                    return true;
                },
                'Domain' => function () {
                    $value = $_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0, -10);

                    if (!Application::getConfig('global','domain', null)){
                        return 'La variable globale "domain" n\'est pas configurée. Y placer la valeur <span class="val">'.$value.'</span>';
                    }

                    return true;
                },
                'Accès à la base de données'      => function () {
                    $this->getEntityManager()->beginTransaction();
                    $this->getEntityManager()->rollback();

                    return true;
                },
                'Recherche de l\'utilisateur OSE' => function () {
                    /** @var UtilisateurService $serviceUtilisateur */
                    $serviceUtilisateur = Application::$container->get(UtilisateurService::class);

                    if ($serviceUtilisateur->getOse() instanceof Utilisateur) {
                        return true;
                    } else {
                        return 'Utilisateur OSE introuvable';
                    }
                },
                'Accès au serveur LDAP'           => function () {
                    /** @var LdapConnecteur $ldap */
                    $ldap = Application::$container->get(LdapConnecteur::class);
                    @$ldap->rechercheUtilisateurs('e');

                    return true;
                },
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
                    try {
                        $r = $res();
                    } catch (\Exception $e) {
                        $this->stop = true;
                        $r          = $e->getMessage();
                    }
                }

                echo '<tr class="' . ($r === true ? 'passed' : 'error') . '"><td class="test">' . $test . '</td><td class="res">' . ($r === true ? 'OK' : $r) . '</td></tr>';
            }
            echo '</table>';
        }
    }



    public function changementMotDePasse()
    {
        $error = null;
        $mdp1  = isset($_POST['mdp1']) ? $_POST['mdp1'] : null;
        $mdp2  = isset($_POST['mdp2']) ? $_POST['mdp2'] : null;

        if ($mdp1 && $mdp2) {
            if ($mdp1 != $mdp2) {
                $error = 'Les deux mots de passe saisis diffèrent.';
            }
            if (strlen($mdp1) < 8) {
                $error = 'Le mot de passe doit faire au moins 8 caractères';
            }
            if (!$error) {
                try {
                    $oseUser = $this->updPwd($mdp1);
                } catch (\Exception $e) {
                    $error = $e->getMessage();
                }
            }

            if ($error) {
                echo '<p class="mdp-error">' . $error . '</p>';
            } else {
                echo '<p class="mdp-success">Le mot de passe de l\'utilisateur OSE a été réinitialisé avec succès.<br />Pour rappel, le login de l\'utilisateur OSE est <b class="username">'.$oseUser->getUsername().'</b></p>';
            }
        }
    }



    private function updPwd($password)
    {
        /** @var UtilisateurService $serviceUtilisateur */
        $serviceUtilisateur = Application::$container->get(UtilisateurService::class);
        $ose                = $serviceUtilisateur->getOse();

        $ose->setPassword($password, true);
        $serviceUtilisateur->save($ose);

        return $ose;
    }



    protected function getEntityManager(): EntityManager
    {
        if (!$this->entityManager) {
            $this->entityManager = Application::$container->get(Constants::BDD);
        }

        return $this->entityManager;
    }
}