<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */

use Formule\Model\Arrondisseur\Ligne;



$typesHetd = [
    'Total'                        => true,
    'HeuresService'                => true,
    'HeuresServiceEnseignement'    => true,
    'HeuresServiceFi'              => true,
    'HeuresServiceFa'              => true,
    'HeuresServiceFc'              => false,
    'HeuresServiceReferentiel'     => false,
    'HeuresCompl'                  => true,
    'HeuresComplEnseignement'      => true,
    'HeuresComplFi'                => true,
    'HeuresComplFa'                => false,
    'HeuresComplFc'                => false,
    'HeuresComplReferentiel'       => false,
    'HeuresPrimes'                 => true,
    'HeuresNonPayable'             => true,
    'HeuresNonPayableEnseignement' => true,
    'HeuresNonPayableFi'           => false,
    'HeuresNonPayableFa'           => false,
    'HeuresNonPayableFc'           => false,
    'HeuresNonPayableReferentiel'  => true,
];


$l = new Ligne();
$typesHetd = $l->getValeursUtilisees($typesHetd);

var_dump($typesHetd);

$res = finaliserTypesHetd($typesHetd);

var_dump($res);

function finaliserTypesHetd(array $typesHetd): array
{
    $tree = [];

    if (in_array(Ligne::TOTAL,$typesHetd)){
        $tree['Total'] = [];
    }

    $tradCats  = [
        Ligne::CAT_SERVICE     => 'Service',
        Ligne::CAT_COMPL       => 'Heures compl.',
        Ligne::CAT_NON_PAYABLE => 'Non payable',
    ];
    $tradTypes = [
        Ligne::TYPE_FI          => 'FI',
        Ligne::TYPE_FA          => 'FA',
        Ligne::TYPE_FC          => 'FC',
        Ligne::TYPE_REFERENTIEL => 'Référentiel',
    ];

    foreach (Ligne::CATEGORIES as $cat) {
        $tcat = $tradCats[$cat];
        if (in_array($cat,$typesHetd)){
            $tree[$tcat][] = 'Total';
        }
        if (in_array($cat.Ligne::TYPE_ENSEIGNEMENT, $typesHetd)){
            $tree[$tcat][] = 'Tot. Ens.';
        }
        foreach (Ligne::TYPES as $type) {
            if (in_array($cat . $type, $typesHetd)) {
                $ttype = $tradTypes[$type];
                if (!array_key_exists($tcat, $tree)) {
                    $tree[$tcat] = [];
                }
                $tree[$tcat][] = $ttype;
            }
        }
    }

    if (in_array(Ligne::CAT_TYPE_PRIME, $typesHetd)) {
        $tree['Primes'] = [];
    }

    // on repasse le non payable en dernier
    if (isset($tree[$tradCats[Ligne::CAT_NON_PAYABLE]])){
        $np = $tree[$tradCats[Ligne::CAT_NON_PAYABLE]];
        unset($tree[$tradCats[Ligne::CAT_NON_PAYABLE]]);
        $tree[$tradCats[Ligne::CAT_NON_PAYABLE]] = $np;
    }

    return $tree;
}