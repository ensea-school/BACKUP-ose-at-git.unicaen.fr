<?php

$localConfig = require(__DIR__ . '/application.local.php');

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
            'contact'                => ['mail' => $localConfig['liens']['contactAssistance']],
            'mentionsLegales'        => $localConfig['liens']['mentionsLegales'],
            'informatiqueEtLibertes' => $localConfig['liens']['informatiqueEtLibertes'],
        ],

        /**
         * Période d'exécution de la requête de rafraîchissement de la session utilisateur, en millisecondes.
         */
        'session_refresh_period' => 600000, // 10*60*1000 ms = 10 min

        'ldap' => [
            'connection'  => [
                'default' => [
                    'params' => [
                        'host'                => $localConfig['ldap']['host'],
                        'username'            => $localConfig['ldap']['username'],
                        'password'            => $localConfig['ldap']['password'],
                        'baseDn'              => $localConfig['ldap']['baseDn'],
                        'bindRequiresDn'      => $localConfig['ldap']['bindRequiresDn'],
                        'accountFilterFormat' => "(&(objectClass=posixAccount)(" . $localConfig['ldap']['loginAttribute'] . "=%s))",
                        'port'                => $localConfig['ldap']['port'],
                    ],
                ],
            ],
            'dn'          => [
                'UTILISATEURS_BASE_DN'            => $localConfig['ldap']['utilisateursBaseDN'],
                'UTILISATEURS_DESACTIVES_BASE_DN' => $localConfig['ldap']['utilisateursDesactivesBaseDN'],
                'GROUPS_BASE_DN'                  => $localConfig['ldap']['groupsBaseDN'],
                'STRUCTURES_BASE_DN'              => $localConfig['ldap']['structuresBaseDN'],
            ],
            'filters'     => [
                'LOGIN_FILTER'                 => '(' . $localConfig['ldap']['loginAttribute'] . '=%s)',
                'LOGIN_OR_NAME_FILTER'         => '(|(' . $localConfig['ldap']['loginAttribute'] . '=%s)(cn=%s*))',
                'FILTER_STRUCTURE_DN'          => '(%s)',
                'FILTER_STRUCTURE_CODE_ENTITE' => '(' . $localConfig['ldap']['structureCode'] . '=%s)',
                'NO_INDIVIDU_FILTER'           => '(' . $localConfig['ldap']['utilisateurCode'] . '=%08s)',
            ],
            'utilisateur' => [
                'LOGIN'  => $localConfig['ldap']['loginAttribute'],
                'FILTER' => $localConfig['ldap']['utilisateurFiltre'],
                'CODE'   => $localConfig['ldap']['utilisateurCode'],
            ],
        ],

        /**
         * Options concernant l'envoi de mail par l'application
         */
        'mail' => [
            // transport des mails
            'transport_options' => [
                'host' => $localConfig['mail']['smtpHost'],
                'port' => $localConfig['mail']['smtpPort'],
            ],
            // adresses à substituer à celles des destinataires originaux ('CURRENT_USER' équivaut à l'utilisateur connecté)
            'redirect_to'       => $localConfig['mail']['redirection'],
            // désactivation totale de l'envoi de mail par l'application
            'do_not_send'       => $localConfig['mail']['envoiDesactive'],
        ],
    ],
];