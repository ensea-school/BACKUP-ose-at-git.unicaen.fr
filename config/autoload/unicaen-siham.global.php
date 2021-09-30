<?php

return [
    'unicaen-siham' => [

        // Options concernant l'appel du web service .
        'api' => [
            'base_url' => 'https://sihamt.unicaen.fr/',
            'wsdl'     => [
                'DossierAgentWebService'       => 'DossierAgentWebService/DossierAgentWebService?wsdl',
                'RechercheAgentWebService'     => 'RechercheAgentWebService/RechercheAgentWebService?wsdl',
                'ListeAgentsWebService'        => 'ListeAgentsWebService/ListeAgentsWebService?wsdl',
                'DossierParametrageWebService' => 'DossierParametrageWebService/DossierParametrageWebService?wsdl',
                'PECWebService'                => 'PECWebService/PECWebService?wsdl',


            ],
        ],

        'debug'       => [
            'activate'      => true,
            /*Debug uniquement si une exception est levée*/
            'onlyException' => false,
            /* IP ou nom DNS du serveur SMTP */
            'smtpHost'      => 'smtpcatcher',
            /* Port du serveur SMTP (généralement, 25) */
            'smtpPort'      => 25,
            /* Adresse de l'expéditeur par défaut */
            'from'          => 'anthony.lecourtes@gmail.com',
            /* Adresse du destinataire*/
            'to'            => 'anthony.lecourtes@gmail.com',
        ],
        // Options du client SOAP utilisé pour appeler le web service.
        'soap_client' => [
            'params' => [
                'login'      => 'siham',
                'password'   => 'hello',
                'version'    => SOAP_1_1,
                'cache_wsdl' => 0,
                'trace'      => 1,
                //'proxy_host' => 'host.domain.fr',
                //'proxy_port' => 3128,
            ],

        ],

        'code-nomenclature' => [
            'grades'                     => 'HJB',
            'corps'                      => 'HJV',
            'section-cnu'                => 'VSP',
            'specialites'                => 'HIS',
            'familles-proffessionnelles' => 'VFP',
            'qualites-statutaires'       => 'HJK',
            'categories'                 => 'HKE',
            'type-contrats'              => 'UIP',
            'statuts'                    => 'HJ8',
            'modalites'                  => 'UHU',
            'positions'                  => 'HKK',
            'echelons'                   => 'HKM',
            'administration'             => 'UAA',
            'etablissements'             => 'DRE',
            'mode-paiement'              => 'DRN',
            'pays'                       => 'UIN',

        ],

        'code-administration' => 'UCN',
        'code-etablissement'  => '0141408E',

        'filters'            => [
            'HKK'     => [
                'ACI01' => 'ACI01 - Affecté dans l\'administration',
            ],
            'emplois' => [
                'UCNVCE' => 'UCNVCE - Vacataire chargé d\'enseignement',
                'UCNVA'  => 'UCNVA - Vacataire administratif',
            ],
            'UHU'     => [
                'MS100' => 'MS100 - Temps plein',
            ],
            'HJ8'     => [
                'C2038' => 'C2038 - Chargé d\'enseignement',
                'C2041' => 'C2041 - Agent temporaire vacataire',
                'C2052' => 'C2052 - Chargé d\'enseignement vacataire fonctionnaire',
                'C1201' => 'C1201 - Intermittent spectacle',
                'C1204' => 'C1204 - Autre personnel payé acte / tâche',
                'C1210' => 'C1210 - Praticien agréé - Maître de stage',
            ],
        ],
        'exclude-statut-ose' => [
            'BIATSS'             => 'BIATSS',
            'SS_EMPLOI_NON_ETUD' => 'Sans emploi, non étudiant',
            'IMP'                => 'Vacataire académique sur convention',

        ],

    ],
];