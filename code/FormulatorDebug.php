<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

/** @var \Formule\Service\FormulatorService $fs */
$fs = $container->get(\Formule\Service\FormulatorService::class);

$dir = getcwd() . '/data/formules/';
$fichiers = scandir($dir);
$options = [];
foreach ($fichiers as $fichier) {
    if (!str_starts_with($fichier, '.')) {
        $fichier = str_replace('.ods', '', $fichier);
        $options[$fichier] = $fichier;
    }
}

$params = \UnicaenCode\Util::codeGenerator()->generer([
    'formule' => [
        'type'    => 'select',
        'label'   => 'Formule à générer',
        'options' => $options,
    ],
    'cellule' => [
        'type'  => 'text',
        'label' => 'Cellule à traduire (si non renseigné tout sera traduit)',
    ],
]);

if (!$params['formule']) {
    return;
}


/** @var \Formule\Service\FormulatorService $formulator */
$formulator = $container->get(\Formule\Service\FormulatorService::class);

/** @var \Formule\Service\TraducteurService $traducteur */
$traducteur = $container->get(\Formule\Service\TraducteurService::class);
$traducteur->setDebug(true);

$filename = $dir . '/' . $params['formule'] . '.ods';
$tableur = $formulator->charger($filename);

echo "<h1>Informations sur la feuille de calcul</h1>";

echo "Version : ".$tableur->version()."<br />";
echo "Ligne principale : ".$tableur->mainLine()."<br />";

//var_dump($tableur->formuleCells());

$mls = (string)$tableur->mainLine();

$cells = $tableur->formuleCells();
$cellules = [];
foreach ($cells as $cell) {
    $name = $cell->getName();
    if (str_ends_with($name, $mls)) {
        $name = substr($name, 0, -strlen($mls));
    }
    if (!$params['cellule'] || $params['cellule'] == $name) {
        $traducteur->traduire($tableur, $cell);
    }
    $cellules[] = $name;
}

echo '<form method="post">';
echo '<input type="hidden" name="formule" value="' . $params['formule'] . '" />';
echo '<h3>Liste des cellules de la feuille :</h3>';
foreach ($cellules as $cname) {
    echo '<button type="submit" class="btn btn-secondary" style="margin:2px" name="cellule" value="' . $cname . '">' . $cname . '</button>';
}
echo '</form>';
