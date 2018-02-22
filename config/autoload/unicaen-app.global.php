<?php

return [
    'unicaen-app' => [

        /**
         * Informations concernant cette application
         */
        'app_infos'              => [
            'nom'                    => "OSE",
            'desc'                   => "Organisation des Services d'Enseignement",
            'version'                => "6.2",
            'date'                   => "22/02/2018",
            'contact'                => ['mail' => Application::getConfig('liens','contactAssistance')],
            'mentionsLegales'        => Application::getConfig('liens','mentionsLegales'),
            'informatiqueEtLibertes' => Application::getConfig('liens','informatiqueEtLibertes'),
        ],

        /**
         * Période d'exécution de la requête de rafraîchissement de la session utilisateur, en millisecondes.
         */
        'session_refresh_period' => 600000, // 10*60*1000 ms = 10 min

        'ldap' => [
            'connection'  => [
                'default' => [
                    'params' => [
                        'host'                => Application::getConfig('ldap','host'),
                        'username'            => Application::getConfig('ldap','username'),
                        'password'            => Application::getConfig('ldap','password'),
                        'baseDn'              => Application::getConfig('ldap','baseDn'),
                        'bindRequiresDn'      => Application::getConfig('ldap','bindRequiresDn'),
                        'accountFilterFormat' => "(&(objectClass=posixAccount)(" . Application::getConfig('ldap','loginAttribute') . "=%s))",
                        'port'                => Application::getConfig('ldap','port'),
                    ],
                ],
            ],
            'dn'          => [
                'UTILISATEURS_BASE_DN'            => Application::getConfig('ldap','utilisateursBaseDN'),
                'UTILISATEURS_DESACTIVES_BASE_DN' => Application::getConfig('ldap','utilisateursDesactivesBaseDN'),
                'GROUPS_BASE_DN'                  => Application::getConfig('ldap','groupsBaseDN'),
                'STRUCTURES_BASE_DN'              => Application::getConfig('ldap','structuresBaseDN'),
            ],
            'filters'     => [
                'LOGIN_FILTER'                 => '(' . Application::getConfig('ldap','loginAttribute') . '=%s)',
                'LOGIN_OR_NAME_FILTER'         => '(|(' . Application::getConfig('ldap','loginAttribute') . '=%s)(cn=%s*))',
                'FILTER_STRUCTURE_DN'          => '(%s)',
                'FILTER_STRUCTURE_CODE_ENTITE' => '(' . Application::getConfig('ldap','structureCode') . '=%s)',
                'NO_INDIVIDU_FILTER'           => '(' . Application::getConfig('ldap','utilisateurCode') . '=%08s)',
            ],
            'utilisateur' => [
                'LOGIN'  => Application::getConfig('ldap','loginAttribute'),
                'FILTER' => Application::getConfig('ldap','utilisateurFiltre'),
                'CODE'   => Application::getConfig('ldap','utilisateurCode'),
            ],
        ],

        /**
         * Options concernant l'envoi de mail par l'application
         */
        'mail' => [
            // transport des mails
            'transport_options' => [
                'host' => Application::getConfig('mail','smtpHost'),
                'port' => Application::getConfig('mail','smtpPort'),
            ],
            // adresses à substituer à celles des destinataires originaux ('CURRENT_USER' équivaut à l'utilisateur connecté)
            'redirect_to'       => Application::getConfig('mail','redirection'),
            // désactivation totale de l'envoi de mail par l'application
            'do_not_send'       => Application::getConfig('mail','envoiDesactive'),
        ],
    ],
];