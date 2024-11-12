<?php

$conf = AppAdmin::config();

return [
    'unicaen-siham' => [

        // Options concernant l'appel du web service .
        'api' => $conf['export-rh']['api'] ?? false,

        'debug'       => $conf['export-rh']['debug'] ?? true,


        // Options du client SOAP utilisé pour appeler le web service.
        'soap_client' => $conf['export-rh']['soap_client'] ?? null,

        'code-nomenclature' => $conf['export-rh']['code-nomenclature'] ?? null,

        'code-type-structure-affectation' => $conf['export-rh']['code-type-structure-affectation'] ?? null,
        'code-administration'             => $conf['export-rh']['code-administration'] ?? null,
        'code-etablissement'              => $conf['export-rh']['code-etablissement'] ?? null,

        'contrat' => $conf['export-rh']['contrat'] ?? false,

        'cloture' => $conf['export-rh']['cloture'] ?? false,

        'filters' => $conf['export-rh']['filters'] ?? [],

        'exclude-statut-ose' => $conf['export-rh']['exclude-statut-ose'] ?? false,

        'type-affectation' => $conf['export-rh']['type-affectation'] ?? null,

        'unites-organisationelles' => $conf['export-rh']['unites-organisationelles'] ?? [],

    ],
];

