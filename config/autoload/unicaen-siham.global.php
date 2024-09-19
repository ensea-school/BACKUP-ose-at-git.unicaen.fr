<?php

$config = OseAdmin::instance()->config();

return [
    'unicaen-siham' => [

        // Options concernant l'appel du web service .
        'api' => $config->get('export-rh', 'api'),

        'debug'       => $config->get('export-rh', 'debug'),


        // Options du client SOAP utilisé pour appeler le web service.
        'soap_client' => $config->get('export-rh', 'soap_client'),

        'code-nomenclature' => $config->get('export-rh', 'code-nomenclature'),

        'code-type-structure-affectation' => $config->get('export-rh', 'code-type-structure-affectation'),
        'code-administration'             => $config->get('export-rh', 'code-administration'),
        'code-etablissement'              => $config->get('export-rh', 'code-etablissement'),

        'contrat' => $config->get('export-rh', 'contrat'),

        'cloture' => $config->get('export-rh', 'cloture'),

        'filters' => $config->get('export-rh', 'filters'),

        'exclude-statut-ose' => $config->get('export-rh', 'exclude-statut-ose'),

        'type-affectation' => $config->get('export-rh', 'type-affectation'),

        'unites-organisationelles' => $config->get('export-rh', 'unites-organisationelles'),

    ],
];

