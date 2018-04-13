<?php
$versionFile = dirname(dirname(__DIR__)).'/VERSION';
if (file_exists($versionFile)){
    $version = file_get_contents($versionFile);
}else{
    $version = 'inconnue';
}
$dateVersion = '13/04/2018';

return [
    'unicaen-app' => [

        /**
         * Informations concernant cette application
         */
        'app_infos'              => [
            'nom'                    => "OSE",
            'desc'                   => "Organisation des Services d'Enseignement",
            'version'                => $version,
            'date'                   => $dateVersion,
            'contact'                => ['mail' => AppConfig::get('etablissement','contactAssistance')],
            'mentionsLegales'        => AppConfig::get('etablissement','mentionsLegales'),
            'informatiqueEtLibertes' => AppConfig::get('etablissement','informatiqueEtLibertes'),
        ],

        /**
         * Période d'exécution de la requête de rafraîchissement de la session utilisateur, en millisecondes.
         */
        'session_refresh_period' => 600000, // 10*60*1000 ms = 10 min

        'ldap' => [
            'connection'  => [
                'default' => [
                    'params' => [
                        'host'                => AppConfig::get('ldap','host'),
                        'username'            => AppConfig::get('ldap','username'),
                        'password'            => AppConfig::get('ldap','password'),
                        'baseDn'              => AppConfig::get('ldap','baseDn'),
                        'bindRequiresDn'      => AppConfig::get('ldap','bindRequiresDn'),
                        'accountFilterFormat' => "(&(objectClass=posixAccount)(" . AppConfig::get('ldap','loginAttribute') . "=%s))",
                        'port'                => AppConfig::get('ldap','port'),
                    ],
                ],
            ],
            'dn'          => [
                'UTILISATEURS_BASE_DN'            => AppConfig::get('ldap','utilisateursBaseDN'),
                'UTILISATEURS_DESACTIVES_BASE_DN' => AppConfig::get('ldap','utilisateursDesactivesBaseDN'),
                'GROUPS_BASE_DN'                  => AppConfig::get('ldap','groupsBaseDN'),
                'STRUCTURES_BASE_DN'              => AppConfig::get('ldap','structuresBaseDN'),
            ],
            'filters'     => [
                'LOGIN_FILTER'                 => '(' . AppConfig::get('ldap','loginAttribute') . '=%s)',
                'LOGIN_OR_NAME_FILTER'         => '(|(' . AppConfig::get('ldap','loginAttribute') . '=%s)(cn=%s*))',
                'FILTER_STRUCTURE_DN'          => '(%s)',
                'FILTER_STRUCTURE_CODE_ENTITE' => '(' . AppConfig::get('ldap','structureCode') . '=%s)',
                'NO_INDIVIDU_FILTER'           => '(' . AppConfig::get('ldap','utilisateurCode') . '=%08s)',
            ],
            'utilisateur' => [
                'LOGIN'  => AppConfig::get('ldap','loginAttribute'),
                'FILTER' => AppConfig::get('ldap','utilisateurFiltre'),
                'CODE'   => AppConfig::get('ldap','utilisateurCode'),
            ],
        ],

        /**
         * Options concernant l'envoi de mail par l'application
         */
        'mail' => [
            // transport des mails
            'transport_options' => [
                'host' => AppConfig::get('mail','smtpHost'),
                'port' => AppConfig::get('mail','smtpPort'),
            ],
            // adresses à substituer à celles des destinataires originaux ('CURRENT_USER' équivaut à l'utilisateur connecté)
            'redirect_to'       => AppConfig::get('mail','redirection'),
            // désactivation totale de l'envoi de mail par l'application
            'do_not_send'       => AppConfig::get('mail','envoiDesactive'),
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'etab' => [
                        'label'    => AppConfig::get('etablissement','nom'),
                        'title'    => AppConfig::get('etablissement','logoDescription'),
                        'uri'      => AppConfig::get('etablissement','logoUri'),
                    ],
                ],
            ],
        ],
    ],
];