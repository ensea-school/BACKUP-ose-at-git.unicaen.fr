<?php

use Unicaen\Framework\Application\Application;

$config = Application::getInstance()->config();

return [
    'unicaen-siham' => [

        // Options concernant l'appel du web service .
        'api' => $config['export-rh']['api'] ?? false,

        'debug'       => $config['export-rh']['debug'] ?? true,


        // Options du client SOAP utilisé pour appeler le web service.
        'soap_client' => $config['export-rh']['soap_client'] ?? null,

        'code-nomenclature' => $config['export-rh']['code-nomenclature'] ?? null,

        'code-type-structure-affectation' => $config['export-rh']['code-type-structure-affectation'] ?? null,
        'code-administration'             => $config['export-rh']['code-administration'] ?? null,
        'code-etablissement'              => $config['export-rh']['code-etablissement'] ?? null,

        'contrat' => $config['export-rh']['contrat'] ?? false,

        'cloture' => $config['export-rh']['cloture'] ?? false,

        'filters' => $config['export-rh']['filters'] ?? [],

        'exclude-statut-ose' => $config['export-rh']['exclude-statut-ose'] ?? false,

        'type-affectation' => $config['export-rh']['type-affectation'] ?? null,

        'unites-organisationelles' => $config['export-rh']['unites-organisationelles'] ?? [],

    ],
];

