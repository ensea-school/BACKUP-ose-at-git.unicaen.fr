<?php

use UnicaenCode\Form\Form;
use UnicaenCode\Form\ElementMakerForm;
use UnicaenCode\Util;

$cg = Util::codeGenerator();

$generator = function (array $params): array {
    $params['type']            = 'Service';
    $params['entityClassname'] = Util::classClassname($params['entityClass']);
    $params['class']           = Util::classModule($params['entityClass']) . '\Service\\' . $params['entityClassname'] . 'Service';
    $params['template']        = 'EntityService';
    Util::codeGenerator()->generateFrom($params, 'factory');
    $params['factory']['template'] = 'EntityServiceFactory';

    $params['namespace'] = Util::classNamespace($params['class']);
    $params['classname'] = Util::classClassname($params['class']);
    $params['author']    = Util::getAuthor();

    return $params;
};

$params = [
    'title'          => 'Création d\'un nouveau service d\'entité OSE',
    'generator'      => $generator,
    'factory'        => true,
    'useGetter'      => true,
    'entityClass'    => [
        'type'  => 'selectEntity',
        'label' => 'Entité du service',
    ],
    'alias'          => [
        'label' => 'Alias d\'entité',
    ],
    'awareTrait'     => [
        'type'  => 'checkbox',
        'label' => 'Générer un trait',
        'value' => true,
    ],
    'awareInterface' => [
        'type'  => 'checkbox',
        'label' => 'Générer une interface',
        'value' => false,
    ],
    'subDir'         => [
        'type'  => 'checkbox',
        'label' => 'Les traits, interfaces et Factory seront placés dans des sous-dossiers dédiés',
        'value' => false,
    ],
];

$cg->generer($params);