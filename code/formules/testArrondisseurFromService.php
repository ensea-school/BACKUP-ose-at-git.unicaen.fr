<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */

use Formule\Entity\FormuleIntervenant;

$bdd = $container->get(\Unicaen\BddAdmin\Bdd::class);

$tvhs                 = $bdd->select('SELECT id "id", libelle "libelle" FROM type_volume_horaire');
$typesVolumesHoraires = [];
foreach ($tvhs as $tvh) {
    $typesVolumesHoraires[$tvh['id']] = $tvh['libelle'];
}

$evhs                 = $bdd->select('SELECT id "id", libelle "libelle" FROM etat_volume_horaire');
$etatsVolumesHoraires = [];
foreach ($evhs as $evh) {
    $etatsVolumesHoraires[$evh['id']] = $evh['libelle'];
}

// récup intervenantId
$pdata  = [
    'intervenantId'       => [
        'type'  => 'text',
        'label' => 'ID de l\'intervenant',
    ],
    'typeVolumeHoraireId' => [
        'type'    => 'select',
        'options' => $typesVolumesHoraires,
        'label'   => 'Type de volume horaire',
    ],
    'etatVolumeHoraireId' => [
        'type'    => 'select',
        'options' => $etatsVolumesHoraires,
        'label'   => 'État de volume horaire',
    ],
    'arrondir'            => [
        'type'    => 'select',
        'options' => [
            FormuleIntervenant::ARRONDISSEUR_NO      => 'Désactivé',
            FormuleIntervenant::ARRONDISSEUR_MINIMAL => 'Minimal',
            FormuleIntervenant::ARRONDISSEUR_FULL    => 'Full',
            FormuleIntervenant::ARRONDISSEUR_CUSTOM  => 'Custom (pour développer)',
        ],
        'label'   => 'Mode de fonctionnement de l\'arrondisseur',
    ],
];
$params = \UnicaenCode\Util::codeGenerator()->generer($pdata);

if (!$params['intervenantId']) {
    return;
}


/** @var \Formule\Service\FormuleService $fs */
$fs = $container->get(\Formule\Service\FormuleService::class);

/** @var \Formule\Service\TestService $ts */
$ts = $container->get(\Formule\Service\TestService::class);


$fi = $fs->getFormuleServiceIntervenant((int)$params['intervenantId'], (int)$params['typeVolumeHoraireId'], (int)$params['etatVolumeHoraireId']);
$fi->setArrondisseur((int)$params['arrondir']);

/* Calcul & affichage des résultats d'arrondissage */
if (isset($fi)) {
    $ts->getServiceFormule()->calculer($fi);
    $aff = new \Formule\Model\Arrondisseur\Afficheur();
    $aff->afficher($fi);
}