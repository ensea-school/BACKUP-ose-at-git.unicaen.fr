<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

use UnicaenCode\Util;

$name = trim(strtoupper($_POST['name'] ?? ''));

?>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="formule-name">Nom de la formule (pour nommer le package)</label>
            <input class="form-control" id="formule-name" type="text" name="name" value="<?= $name ?>"/>
        </div>

        <div class="form-group">
            <label for="formule-name">Feuille de calcul de la formule (format Excell ou OpenDocument Calc)</label>
            <input class="form-control" id="formule-fichier" type="file" name="fichier"/>
        </div>

        <div class="form-group">
            <button type="submit" name="action" value="gen" class="btn btn-primary">Générer le package et l'implenter en BDD</button>
            <button type="submit" name="action" value="aff" class="btn btn-primary">Simple affichage du package généré</button>
            <button type="submit" name="action" value="feuille" class="btn btn-default">Voir la feuille de calcul</button>
        </div>
    </form>
<?php
$action  = $_POST['action'] ?? 'gen';
$fichier = $_FILES['fichier'] ?? null;


$bdd = oseAdmin()->getBdd();


if (!$fichier) {
    return;
}

if (!file_exists($fichier['tmp_name'])) {
    ?>
    <div class="alert alert-danger">Fichier non fourni ou erroné</div>
    <?php

    return;
}

$fc                         = new \Application\Model\FormuleCalcul($fichier['tmp_name'], $name);

switch ($action) {
    case 'feuille':
        echo $fc->getSheet(1)->html();
    break;
    case 'aff':
    case 'gen':
        $intervenantQuery = null;
        $volumeHoraireQuery = null;
        try {
            $packageName = 'FORMULE_' . $fc->getName();
            $pe          = $bdd->select('SELECT id FROM formule WHERE package_name = :pn', ['pn' => $packageName]);
            if (!empty($pe)) {
                $intervenantQuery   = trim(@$bdd->select("SELECT $packageName.INTERVENANT_QUERY Q FROM dual")[0]['Q']);
                $volumeHoraireQuery = trim(@$bdd->select("SELECT $packageName.VOLUME_HORAIRE_QUERY Q FROM dual")[0]['Q']);

                $intervenantQuery   = str_replace("'", "''", $intervenantQuery);
                $volumeHoraireQuery = str_replace("'", "''", $volumeHoraireQuery);
            }
        } catch (\Exception $e) {

        }

        $def  = $fc->makePackageDef();
        $body = $fc->makePackageBody($intervenantQuery, $volumeHoraireQuery);

        if ($action === 'gen') {
            try {
                $bdd->exec($def);
                $bdd->exec($body);
                echo '<div class="alert alert-success">Package de formule correctement implanté dans la BDD</div>';
            } catch (\Exception $e) {
                ?>
                <div class="alert alert-danger">
                    <?php
                    $msg = $e->getMessage();
                    if (str_starts_with($msg, 'ORA-24344')) {
                        $msg = 'ERREUR : Le package ne compile pas';
                    }
                    echo nl2br($msg) ?>
                </div>
                <?php
            }
        }
        affCreateData($fc);

        echo '<h2>Code généré</h2>';

        Util::highlight($def, 'plsql', true, ['show-line-numbers' => true]);
        Util::highlight($body, 'plsql', true, ['show-line-numbers' => true]);
    break;
}


function affCreateData(\Application\Model\FormuleCalcul $fc)
{
    $bdd         = oseAdmin()->getBdd();
    $packageName = 'FORMULE_' . $fc->getName();
    $pe          = $bdd->select('SELECT id FROM formule WHERE package_name = :pn', ['pn' => $packageName]);
    if (empty($pe)) {
        $newFormuleId = $bdd->select('SELECT max(id) + 1 nid FROM formule')[0]['NID'];
    } else {
        $newFormuleId = $pe[0]['ID'];
    }

    $array  = [
        'LIBELLE'      => '...',
        'PACKAGE_NAME' => $packageName,
    ];
    $params = $fc->getParams();
    $plibs  = [
        'I_PARAM_1_LIBELLE'  => 'i.param_1',
        'I_PARAM_2_LIBELLE'  => 'i.param_2',
        'I_PARAM_3_LIBELLE'  => 'i.param_3',
        'I_PARAM_4_LIBELLE'  => 'i.param_4',
        'I_PARAM_5_LIBELLE'  => 'i.param_5',
        'VH_PARAM_1_LIBELLE' => 'vh.param_1',
        'VH_PARAM_2_LIBELLE' => 'vh.param_2',
        'VH_PARAM_3_LIBELLE' => 'vh.param_3',
        'VH_PARAM_4_LIBELLE' => 'vh.param_4',
        'VH_PARAM_5_LIBELLE' => 'vh.param_5',
    ];
    foreach ($plibs as $col => $param) {
        if (isset($params[$param])) {
            $array[$col] = $params[$param];
        }
    }

    $kl = 0;
    foreach ($array as $k => $v) {
        if (strlen($k) > $kl) $kl = strlen($k);
    }

    echo '<div class="alert alert-info">';
    echo 'Cette formule n\'est pas encore déclarée en BDD. Vous devez l\'ajouter dans le fichier /data/formules.php';
    echo '<pre>';
    echo "$newFormuleId => [\n";
    foreach ($array as $k => $v) {
        $pad = str_pad('', $kl - strlen($k), ' ');
        echo "\t'$k'$pad => '$v',\n";
    }
    echo "],";
    echo '</pre>';

    if (!empty($params)) {
        echo '<div class="alert alert-warning">Attention : cette formule a besoin de paramètres. Vous devrez adapter le package et écrire les requêtes correspondantes</div>';
    }

    echo 'Puis lancer<pre>./bin/ose update-bdd-formules</pre>';
    echo '</div>';
}