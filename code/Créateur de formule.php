<?php

use UnicaenCode\Util;

$name = strtoupper($_POST['name'] ?? '');

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
            <button type="submit" name="action" value="generer" class="btn btn-primary">Générer le package</button>
            <button type="submit" name="action" value="voir" class="btn btn-default">Voir la feuille de calcul</button>
        </div>
    </form>
<?php
$action  = $_POST['action'] ?? 'generer';
$fichier = $_FILES['fichier'] ?? null;

if (!$fichier) {
    return;
}

$fc = new \Application\Model\FormuleCalcul($fichier['tmp_name'], $name);


switch ($action) {
    case 'voir':
        echo $fc->getSheet(1)->html();
    break;
    case 'generer':
        Util::highlight($fc->makePackageDef(), 'plsql', true, ['show-line-numbers' => true]);
        Util::highlight($fc->makePackageBody(), 'plsql', true, ['show-line-numbers' => true]);
    break;
}
