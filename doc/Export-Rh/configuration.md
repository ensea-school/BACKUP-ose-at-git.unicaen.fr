**ATTENTION** : cette docmuentation est valable à partir de la version 17 de OSE. Pour les versions antérieures le
module exportRh n'est pas disponible.

# Principe

Le module ExportRh de OSE permet de rendre une disponible au sein de l'applicatif une fonctionnalité d'export des
données intervenants dans le SI RH. Pour le moment, seul SIHAM est pris en charge par ce module.

Si vous activez le module export et que vous avez SIHAM, vous pourrez pour un intervenant, vacataire et uniquement pour
l'année universitaire en cours, lancer une prise en charge ou un renouvellement directement dans SIHAM à partir de OSE.

A noter que le renouvellement, ou la prise en charge SIHAM n'est possible que si le contrat OSE a une date de retour
signée.

# Configuration du connecteur SIHAM

Pour utiliser le module ExportRh Siham vous devez ajouter dans le fichier de configuration config.local.php les lignes
suivantes et faire le paramètrage souhaité :

```php
   'export-rh' => [
        //définition du connecteur SI RH à utiliser pour le module export RH de OSE
        'actif'      => true,//False pour désactiver l'export RH
        'connecteur' => 'siham',//Le nom du connecteur dont vous avez besoin, pour le moment seul le connecteur SIHAM a été développé.
        'sync-code'  => false,//Permet de venir forcer le code de l'intervenant avec le matricule siham en retour d'un renouvellement ou d'une prise en charge
        'sync-source'  => '',//Code de la source à remplacer après la synchronisation

        // Options concernant l'appel du web service .
        'api' => [
            'base_url' => 'https://siham.xxxxx.fr/',//L'url permettant d'accéder aux webservices SIHAM
            'wsdl'     => [//Définition des webservices utilisables
                'DossierAgentWebService'       => 'DossierAgentWebService/DossierAgentWebService?wsdl',
                'RechercheAgentWebService'     => 'RechercheAgentWebService/RechercheAgentWebService?wsdl',
                'ListeAgentsWebService'        => 'ListeAgentsWebService/ListeAgentsWebService?wsdl',
                'DossierParametrageWebService' => 'DossierParametrageWebService/DossierParametrageWebService?wsdl',
                'PECWebService'                => 'PECWebService/PECWebService?wsdl',


            ],
        ],

        'debug'       => [
            /*Active le debug des interactions avec les webservices SIHAM*/
            'activate'      => true,
            /*Debug uniquement si une exception est levée*/
            'onlyException' => false,
            /* IP ou nom DNS du serveur SMTP */
            'smtpHost'      => 'xxxxxxxxxxx',
            /* Port du serveur SMTP (généralement, 25) */
            'smtpPort'      => 25,
            /* Adresse de l'expéditeur par défaut */
            'from'          => 'xxxxx@xxxx.xx',
            /* Adresse du destinataire*/
            'to'            => 'xxxxx@xxxx.xx',
        ],
        // Options du client SOAP utilisé pour appeler le web service.
        'soap_client' => [
            'params' => [
                //Crédentials pour accéder aux webservices SIHAM
                'login'      => 'xxxxx',
                'password'   => 'xxxxx',
                'version'    => SOAP_1_1,
                'cache_wsdl' => 0,
                'trace'      => 1,
                //'proxy_host' => 'host.domain.fr',
                //'proxy_port' => 3128,
            ],

        ],

        'code-nomenclature' => [
            //Code répertoire des différentes nommenclatures SIHAM utilsées
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

        //paramètrage pour le contrat dans siham
        'contrat'                         => [
            'active'     => true,
            'parameters' => [
                'natureContrat'     => 'CO',
                'typeContrat'       => 'TC01',
                'typeLienJuridique' => 'TL01',
                'modeRemuneration'  => 'MR08',
                'modeDeGestion'     => 'MG08',
                'temoinValidite'    => '1',
                'categorieContrat'  => '1',
                'gradeTG'           => [
                    'C2038' => '0499010000',
                    'C2041' => '0499010000',
                    'C2052' => '0499020000',
                    'C1204' => '0499010000',
                ],

            ],
        ],

        'code-administration' => 'UCN',
        'code-etablissement'  => '0141408E',
        
        //Permet de renseigner le code typeUO à remonter dans la liste des structures  sélectionnable dans l'export RH
        //Vous pouvez mettre plusieurs code séparés par des virgules
        'code-type-structure-affectation' => 'CODEA,CODEB,....',
        
         //Parametrage de la cloture d'un dossier
        'cloture'                         => [
            'categorie-situation' => 'MC140',
            'motif-situation'     => 'MC601',
        ],


        
        //Permet de filtrer les valeurs affichées dans le formulaire de prise en charge SIHAM par code répertoire
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
        
        //Permet d'excluse les statuts pour lesquels on ne souhaite pas faire de PEC ou REN
        'exclude-statut-ose' => [
            'BIATSS'             => 'BIATSS',
            'SS_EMPLOI_NON_ETUD' => 'Sans emploi, non étudiant',
            'IMP'                => 'Vacataire académique sur convention',

        ],

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







