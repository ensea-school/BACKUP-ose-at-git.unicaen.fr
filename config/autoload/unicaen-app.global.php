<?php

use Unicaen\Framework\Application\Application;

$conf = Application::getInstance()->config();

return [
    'unicaen-app' => [

        'app_infos'              => [
            'nom' => 'OSE',
        ],

        /**
         * Période d'exécution de la requête de rafraîchissement de la session utilisateur, en millisecondes.
         */
        'session_refresh_period' => 10 * 60 * 1000, // 10*60*1000 ms = 10 min

        'ldap' => [
            'connection'  => [
                'default' => [
                    'params' => [
                        'host'                => (($conf['ldap']['actif'] ?? true) || ($conf['cas']['actif'] ?? true)) ? ($conf['ldap']['host'] ?? null) : null,
                        'username'            => $conf['ldap']['username'] ?? null,
                        'password'            => $conf['ldap']['password'] ?? null,
                        'baseDn'              => $conf['ldap']['baseDn'] ?? null,
                        'bindRequiresDn'      => $conf['ldap']['bindRequiresDn'] ?? null,
                        'accountFilterFormat' => "(&(objectClass=" . ($conf['ldap']['loginObjectClass'] ?? 'posixAccount') . ")(" . ($conf['ldap']['loginAttribute'] ?? null) . "=%s))",
                        'port'                => $conf['ldap']['port'] ?? null,
                        'useSsl'              => $conf['ldap']['useSsl'] ?? false,
                    ],
                ],
            ],
            'dn'          => [
                'UTILISATEURS_BASE_DN'            => $conf['ldap']['utilisateursBaseDN'] ?? null,
                'UTILISATEURS_DESACTIVES_BASE_DN' => $conf['ldap']['utilisateursDesactivesBaseDN'] ?? null,
                'GROUPS_BASE_DN'                  => $conf['ldap']['groupsBaseDN'] ?? null,
                'STRUCTURES_BASE_DN'              => $conf['ldap']['structuresBaseDN'] ?? null,
            ],
            'filters'     => [
                'LOGIN_FILTER'                 => '(' . ($conf['ldap']['loginAttribute'] ?? null) . '=%s)',
                'LOGIN_OR_NAME_FILTER'         => '(|(' . ($conf['ldap']['loginAttribute'] ?? null) . '=%s)(cn=%s*))',
                'FILTER_STRUCTURE_DN'          => '(%s)',
                'FILTER_STRUCTURE_CODE_ENTITE' => '(' . ($conf['ldap']['structureCode'] ?? null) . '=%s)',
                'NO_INDIVIDU_FILTER'           => '(' . ($conf['ldap']['utilisateurCode'] ?? null) . '=%s)',
            ],
            'utilisateur' => [
                'LOGIN'      => $conf['ldap']['loginAttribute'] ?? null,
                'FILTER'     => $conf['ldap']['utilisateurFiltre'] ?? null,
                'CODE'       => $conf['ldap']['utilisateurCode'] ?? null,
                'CODEFILTER' => $conf['ldap']['utilisateurCodeFiltre'] ?? null,
            ],
        ],
    ],
];
