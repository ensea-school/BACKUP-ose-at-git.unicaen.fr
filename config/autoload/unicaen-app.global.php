<?php

$config = require __dir__ . '/../config.local.php';

return [
    'unicaen-app' => [

        /**
         * Informations concernant cette application
         */
        'app_infos'              => [
            'nom'                    => "OSE",
            'desc'                   => "Organisation des Services d'Enseignement",
            'version'                => "6.1",
            'date'                   => "20/12/2017",
            'contact'                => ['mail' => $config['liens']['contactAssistance']],
            'mentionsLegales'        => $config['liens']['mentionsLegales'],
            'informatiqueEtLibertes' => $config['liens']['informatiqueEtLibertes'],
        ],

        /**
         * Période d'exécution de la requête de rafraîchissement de la session utilisateur, en millisecondes.
         */
        'session_refresh_period' => 600000, // 10*60*1000 ms = 10 min

        'ldap' => [
            'connection'  => [
                'default' => [
                    'params' => [
                        'host'                => $config['ldap']['host'],
                        'username'            => $config['ldap']['username'],
                        'password'            => $config['ldap']['password'],
                        'baseDn'              => $config['ldap']['baseDn'],
                        'bindRequiresDn'      => $config['ldap']['bindRequiresDn'],
                        'accountFilterFormat' => "(&(objectClass=posixAccount)(" . $config['ldap']['loginAttribute'] . "=%s))",
                        'port'                => $config['ldap']['port'],
                    ],
                ],
            ],
            'dn'          => [
                'UTILISATEURS_BASE_DN'            => $config['ldap']['utilisateursBaseDN'],
                'UTILISATEURS_DESACTIVES_BASE_DN' => $config['ldap']['utilisateursDesactivesBaseDN'],
                'GROUPS_BASE_DN'                  => $config['ldap']['groupsBaseDN'],
                'STRUCTURES_BASE_DN'              => $config['ldap']['structuresBaseDN'],
            ],
            'filters'     => [
                'LOGIN_FILTER'                 => '(' . $config['ldap']['loginAttribute'] . '=%s)',
                'LOGIN_OR_NAME_FILTER'         => '(|(' . $config['ldap']['loginAttribute'] . '=%s)(cn=%s*))',
                'FILTER_STRUCTURE_DN'          => '(%s)',
                'FILTER_STRUCTURE_CODE_ENTITE' => '(' . $config['ldap']['structureCode'] . '=%s)',
            ],
            'utilisateur' => [
                'LOGIN'  => $config['ldap']['loginAttribute'],
                'FILTER' => $config['ldap']['utilisateurFiltre'],
                'CODE'   => $config['ldap']['utilisateurCode'],
            ],
        ],

        /**
         * Options concernant l'envoi de mail par l'application
         */
        'mail' => [
            // transport des mails
            'transport_options' => [
                'host' => $config['mail']['smtpHost'],
                'port' => $config['mail']['smtpPort'],
            ],
            // adresses à substituer à celles des destinataires originaux ('CURRENT_USER' équivaut à l'utilisateur connecté)
            'redirect_to'       => $config['mail']['redirection'],
            // désactivation totale de l'envoi de mail par l'application
            'do_not_send'       => $config['mail']['envoiDesactive'],
        ],
    ],
];