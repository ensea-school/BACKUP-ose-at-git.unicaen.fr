<?php

return [
    'unicaen-siham' => [

        // Options concernant l'appel du web service .
        'api' => AppConfig::get('export-rh', 'api'),

        'debug'       => AppConfig::get('export-rh', 'debug'),


        // Options du client SOAP utilisé pour appeler le web service.
        'soap_client' => AppConfig::get('export-rh', 'soap_client'),

        'code-nomenclature' => AppConfig::get('export-rh', 'code-nomenclature'),

        'code-type-structure-affectation' => AppConfig::get('export-rh', 'code-type-structure-affectation'),
        'code-administration'             => AppConfig::get('export-rh', 'code-administration'),
        'code-etablissement'              => AppConfig::get('export-rh', 'code-etablissement'),

        'contrat' => AppConfig::get('export-rh', 'contrat'),

        'filters' => AppConfig::get('export-rh', 'filters'),

        'exclude-statut-ose' => AppConfig::get('export-rh', 'exclude-statut-ose'),

        'type-affectation' => AppConfig::get('export-rh', 'type-affectation'),

        'unites-organisationelles' => AppConfig::get('export-rh', 'unites-organisationelles'),

    ],
];

