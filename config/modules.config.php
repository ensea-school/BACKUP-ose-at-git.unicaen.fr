<?php

use Unicaen\Framework\Application\Application;

$config = Application::getInstance()->config();

$modules = [
    // Dépendances externes
    'Laminas\Filter',
    'Laminas\Form',
    'Laminas\Hydrator',
    'Laminas\I18n',
    'Laminas\InputFilter',
    'Laminas\Mvc\Plugin\FlashMessenger',
    'Laminas\Navigation',
    'Laminas\Router',
    'Laminas\Session',
    'Laminas\Validator',
    'DoctrineModule',
    'DoctrineORMModule',

    // Bibliothèques Unicaen
    'Unicaen\BddAdmin',
    'Unicaen\Framework',
    'ZfcUser',
    'UnicaenApp',
    'UnicaenAuthentification',
    'UnicaenMail',
    'UnicaenImport',
    'UnicaenTbl',
    'UnicaenSiham',
    'UnicaenVue',
    'UnicaenSignature',

    // Modules de l'application
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
    'Signature',
    'Workflow',
    'Utilisateur',
];

// Connecteurs
if ($config['actul']['host'] ?? null){
    $modules[] = 'Connecteur\\Actul';
}

if ($config['pegase']['actif'] ?? false){
    $modules[] = 'Connecteur\\Pegase';
}

return $modules;