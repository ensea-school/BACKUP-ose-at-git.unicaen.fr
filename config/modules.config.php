<?php

$modules = [
    'Laminas\Cache',
    'Laminas\Cache\Storage\Adapter\Memory',
    'Laminas\Cache\Storage\Adapter\Filesystem',
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
    'BjyAuthorize',
    'DoctrineModule',
    'DoctrineORMModule',
    'ZfcUser',
    'UnicaenApp',
    'UnicaenAuthentification',
    'UnicaenMail',
    'UnicaenUtilisateur',
    'UnicaenPrivilege',
    'UnicaenImport',
    'UnicaenTbl',
    'UnicaenSiham',
    'UnicaenVue',
    'Unicaen\BddAdmin',
    'Framework',
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

if (AppAdmin::config()['actul']['host'] ?? null){
    $modules[] = 'Connecteur\\Actul';
}

if (AppAdmin::config()['pegase']['actif'] ?? false){
    $modules[] = 'Connecteur\\Pegase';
}

return $modules;