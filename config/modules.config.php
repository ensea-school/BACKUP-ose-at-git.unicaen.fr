<?php

use Unicaen\Framework\Application\Application;

$config = Application::getInstance()->config();

$modules = [

    'Laminas\Filter',
    'Laminas\Form',
    'Laminas\Hydrator',
    'Laminas\I18n',
    'Laminas\InputFilter',
    'Laminas\Mvc\I18n',
    'Laminas\Mvc\Plugin\FlashMessenger',
    'Laminas\Navigation',
    'Laminas\Paginator',
    'Laminas\Router',
    'Laminas\Session',
    'Laminas\Validator',
    'DoctrineModule',
    'DoctrineORMModule',
    'ZfcUser',
    'UnicaenApp',
    'UnicaenAuthentification',
    'UnicaenMail',
    'UnicaenImport',
    'UnicaenTbl',
    'UnicaenSiham',
    'UnicaenVue',
    'Unicaen\BddAdmin',
    'Unicaen\Framework',
    'Application',
    'Administration',
    'Agrement',
    'Intervenant',
    'Service',
    'EtatSortie',
    'Chargens',
    'Enseignement',
    'Referentiel',
    'Mission',
    'Paiement',
    'OffreFormation',
    'PieceJointe',
    'Plafond',
    'Indicateur',
    'ExportRh',
    'Dossier',
    'Contrat',
    'Lieu',
    'Formule',
    'UnicaenSignature',
    'Signature',
    'Workflow',
    'Utilisateur',
];

if ($config['actul']['host'] ?? null){
    $modules[] = 'Connecteur\\Actul';
}

if ($config['pegase']['actif'] ?? false){
    $modules[] = 'Connecteur\\Pegase';
}

return $modules;