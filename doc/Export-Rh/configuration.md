**ATTENTION** : cette docmuentation est valable à partir de la version 17 de OSE. Pour les versions antérieures le
module exportRh n'est pas disponible.

# Principe

Le module ExportRh de OSE permet de rendre une disponible au sein de l'applicatif une fonctionnalité d'export des
données intervenants dans le SI RH. Pour le moment, seul SIHAM est pris en charge par ce module.

Si vous activez le module export et que vous avez SIHAM, vous pourrez pour un intervenant vacataire lancer une prise en charge ou un renouvellement directement dans SIHAM à partir de OSE. Si l'agent n'existe pas, il sera créé, les données personnelles sont synchronisées et un contrat peut être automatiquement créé.

Par défaut, le renouvellement ou la prise en charge SIHAM n'est possible que si le contrat OSE a une date de retour
signée. Ce paramètre est modifiable via les paramètres généraux de l'application.

# Configuration du connecteur SIHAM

Pour utiliser le module ExportRh Siham vous devez ajouter dans le fichier de configuration config.local.php les lignes
suivantes :

```php
   'export-rh' => [
        //Activation du module export
        'actif'       => true,
        'connecteur'  => 'siham',
        //Synchronisation du code intervenant avec le matricule SIHAM
        'sync-code'   => true,
        //Synchronisation de la source de l'intervenant avec SIHAM
        'sync-source' => 'siham',


         // Options concernant l'appel du web service .
        'api'         => [
            'base_url' => 'https://xxxxxxxxxxxxx/',
            'wsdl'     => [
                'DossierAgentWebService'       => 'chemin vers le wsdl',
                'RechercheAgentWebService'     => 'chemin vers le wsdl',
                'ListeAgentsWebService'        => 'chemin vers le wsdl',
                'DossierParametrageWebService' => 'chemin vers le wsdl',
                'PECWebService'                => 'chemin vers le wsdl',
                'utilisateursService'          => 'chemin vers le wsdl',


            ],
        ],

        //Activation du debug sous forme de trace mail
        'debug'       => [
            //Activation du debug
            'activate'      => true,
            //Debug uniquement si une exception est levée
            'onlyException' => false,
            'smtpHost'      => 'smtp.xxx.fr',
            //Port du serveur SMTP (généralement, 25)
            'smtpPort'      => 25,
            //Adresse de l'expéditeur par défaut
            'from'          => 'ne_pas_repondre@xxxx.fr',
            //Adresse du destinataire du debug
            'to'            => 'xxxx@xxxxx.fr',

        ],

        // Options du client SOAP utilisé pour appeler le web service.
        'soap_client' => [
            'params' => [
                'login'      => 'xxxxxxx',
                'password'   => 'xxxxxxx',
                'version'    => SOAP_1_1,
                'cache_wsdl' => 0,
                'trace'      => 1,
            ],

        ],

        //Ci-dessous l'ensemble du paramètrage spécifique à votre instance SIHAM

        'code-nomenclature' => [
            //Vos différents codes pour les nomenclatures SIHAM
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

        'code-type-structure-affectation' => 'COP',
        'code-administration'             => 'UCN',
        'code-etablissement'              => '0141408E',

        //Activation de la création du contrat automatique dans SIHAM
        'contrat' => [
            'active'     => true,
            'parameters' => [
                'natureContrat'     => 'CO',
                'typeContrat'       => 'TC01',
                'typeLienJuridique' => 'TL01',
                'modeRemuneration'  => 'MR08',
                'modeDeGestion'     => 'MG08',
                //Autovalidation des contrats oui ou non
                'temoinValidite'    => '1',
                'gradeTG'           => [
                    //Code des différents grade TG par statut
                    'C2038' => '0499010000',
                    'C2041' => '0499010000',
                    'C2052' => '0499020000',
                    'C1204' => '0499010000',
                ],

            ],
        ],

        //Différents filtres pour surcharger les listes de valeurs de la PEC ou REN
        'filters'                  => [
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
                'C1204' => 'C1204 - Autre personnel payé acte / tâche',
            ],

        ],
        //Statuts OSE exclus de la PEC/REN de SIHAM
        'exclude-statut-ose'       => [
            'BIATSS'             => 'BIATSS',
            'SS_EMPLOI_NON_ETUD' => 'Sans emploi, non étudiant',
            'IMP'                => 'Vacataire académique sur convention',

        ],
        //Permet de définir le type d'affectation que l'on prend en compte pour vérifier si affectation existe
        'type-affectation'         => [
            'FUN',
        ],
        //Gestion des commposantes
        'unites-organisationelles' => [
            //Composantes qu'on ajoute à la liste de base fournie par SIHAM pour la prise en charge
            'includes'    => [
                'U550000000' => 'U55 Carré international',
                'U610000000' => 'U61 SUAPS',
                'U450000000' => 'U45 SUFCA',
            ],
            //Composantes exclus
            'excludes'    => [
                'U960000000',
                'U970000000',
                'U980000000',
                'U990000000',
                'U560000000',
                'U250000000',
                'U230000000',
            ],
            'regex'       => [
                'search'  => '000000',
                'replace' => '',
            ],
            'mapping-ose' => [
                'code-ose' => 'code-siham',
                'U01'      => 'U010000000',
            ],
        ],

    ],
```







