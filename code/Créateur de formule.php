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

$fc           = new \Application\Model\FormuleCalcul($fichier['tmp_name'], $name);

switch ($action) {
    case 'feuille':
        echo $fc->getSheet(1)->html();
    break;
    case 'aff':
    case 'gen':
        $def = $fc->makePackageDef();
        $body = $fc->makePackageBody();

        if ($action === 'gen') {
            try {
                $bdd->exec($def);
                $bdd->exec($body);

                ?>
                <div class="alert alert-success">Package de formule correctement implanté dans la BDD</div>
                <?php

                $packageName = 'FORMULE_' . $name . '2';
                $pe          = $bdd->select('SELECT id FROM formule WHERE package_name = :pn', ['pn' => $packageName]);
                if (empty($pe)) {
                    $newFormuleId = $bdd->select('SELECT max(id) + 1 nid FROM formule')[0]['NID'];

                    ?>
                    <div class="alert alert-info">
                        Cette formule n'est pas encore déclarée en BDD.
                        Vous devez l'ajouter dans le fichier /data/formules.php
                        <pre>
    <?= $newFormuleId ?> => [
        'LIBELLE'      => '...',
        'PACKAGE_NAME' => '<?= $packageName ?>',
    ],</pre>

                        Puis lancer
                        <pre>./bin/ose update-bdd-formules</pre>

                    </div>
                    <?php
                }
            } catch (\Exception $e) {
                ?>
                <div class="alert alert-danger"><?= $e->getMessage() ?>></div>
                <?php
            }

            echo '<h2>Code généré</h2>';
        }

        Util::highlight($def, 'plsql', true, ['show-line-numbers' => true]);
        Util::highlight($body, 'plsql', true, ['show-line-numbers' => true]);
    break;
}
