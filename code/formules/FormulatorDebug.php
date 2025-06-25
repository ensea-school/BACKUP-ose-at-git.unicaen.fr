<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
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
        'label' => 'Cellule à traduire',
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


ob_start();
echo "Version : " . $tableur->version() . "<br />";
echo "Ligne principale : " . $tableur->mainLine() . "<br />";


$mls = (string)$tableur->mainLine();

$cells = $tableur->formuleCells();
$cellules = [];
foreach ($cells as $cell) {
    $name = $cell->getName();
    if (str_ends_with($name, $mls)) {
        $name = substr($name, 0, -strlen($mls));
    }
    if ($params['cellule'] == $name) {
        $traducteur->traduire($tableur, $cell);
    }
    $cellules[] = $name;
}

echo '<form method="post">';
echo '<input type="hidden" name="formule" value="' . $params['formule'] . '" />';
foreach ($cellules as $cname) {
    echo '<button type="submit" class="btn btn-secondary" style="margin:2px" name="cellule" value="' . $cname . '">' . $cname . '</button>';
}
echo '</form>';
$infos = ob_get_clean();
$test = $formulator->test($tableur);
if ($error = $formulator->getLastTestError()) {
    $calc = '<pre class="alert alert-danger">';
    $calc .= '<h2>Erreur lors du test de cohérence tableur/calcul interne</h2>';
    $calc .= $error;
    $calc .= '</pre>';
} else {
    $calc = '<div class="alert alert-success">Calcul conforme aux chiffres du tableur</div>';
}

ob_start();

$cache = $test->getDebugTrace();
if (!empty($cache)) {
    if (isset($cache['vh'])) {
        $cols = [];
        foreach ($cache['vh'] as $l => $vh) {
            foreach ($vh as $col => $val) {
                $col = \Unicaen\OpenDocument\Calc::letterToNumber($col);
                if (!in_array($col, $cols)) {
                    $cols[] = $col;
                }
            }
        }
    }


    $variablesCells = [];
    foreach ($tableur->variables() as $name => $variable) {
        if (isset($variable['cell'])) {
            /** @var \Unicaen\OpenDocument\Calc\Cell $cell */
            $cell = $variable['cell'];
            if ($cell->getRow() == $tableur->mainLine()) {
                $variablesCells[$cell->getCol()] = str_replace('vh.heures', '', $name);
            }

        }
    }


    echo '<table class="table table-bordered table-xs">';
    echo '<tr>';
    echo '<th></th>';
    foreach ($cols as $col) {
        echo '<th>' . ($variablesCells[$col] ?? '') . '</th>';
    }
    echo '</tr>';
    echo '<tr>';
    echo '<th></th>';
    foreach ($cols as $col) {
        $col = \Unicaen\OpenDocument\Calc::numberToLetter($col);

        echo '<th>' . $col . '</th>';
    }
    echo '</tr>';
    foreach ($cache['vh'] as $l => $vh) {
        echo '<tr>';
        echo '<th>' . $l + $tableur->mainLine() . '</th>';
        foreach ($cols as $col) {
            $col = \Unicaen\OpenDocument\Calc::numberToLetter($col);
            if (isset($vh[$col])) {
                $val = $vh[$col];
                $tabVal = $tableur->getCellFloatVal($col . ($l + $tableur->mainLine()));
                if ((int)round($tabVal * 100) != (int)round($val * 100)) {
                    $val = '<span style="color:red" title="' . (string)round($tabVal, 2) . ' sur le tableur">' . (string)round($val, 2) . '</span>';
                } else {
                    $val = (string)round($val, 2);
                }
            } else {
                $val = '';
            }
            echo '<td>' . $val . '</td>';
        }
        echo '</tr>';
    }
    echo '</table>';


    if (isset($cache['global'])) {
        foreach ($cache['global'] as $cell => $val) {
            $tabVal = $tableur->getCellFloatVal($cell);
            if ((int)round($tabVal * 100) != (int)round($val * 100)) {
                $val = '<span style="color:red" title="' . (string)round($tabVal, 2) . ' sur le tableur">' . (string)round($val, 2) . '</span>';
            } else {
                $val = (string)round($val, 2);
            }

            echo '<span class="debug-cell">';
            echo $cell . '<span class="debug-val">' . $val . '</span>';
            echo '</span>';
        }
    }
    echo '<br />';
    echo '<br />';
}else{
    $calc .= '<div class="alert alert-danger">Le calcul de la formule n\'a pas pu être déclenché</div>';
}
echo '<a href="'.$this->url('formule/administration/telecharger-tableur', ['formule' =>$tableur->formule()->getId()]).'">Télécharger le tableur</a>';

$sheet = ob_get_clean();


?>
<h2>Traduction</h2>
<?= $infos; ?>

<h2>Résultats</h2>
<?= $sheet ?>

<h2>Rapport</h2>
<?= $calc; ?>

<style>

    .debug-cell {
        background-color: #ccc;
        color: black;
        margin: 2px;
        padding: 3px;
        border-radius: 5px;
        font-size: 8pt;
        white-space: nowrap;
        float: left;
    }

    .debug-val {
        background-color: white;
        padding: 3px;
        padding-top: 0px;
        padding-bottom: 0px;
        border-top-right-radius: 5px;
        border-bottom-right-radius: 5px;
        color: black;
        font-size: 8pt;
    }

</style>