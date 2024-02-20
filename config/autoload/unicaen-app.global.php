<?php
$versionFile = dirname(dirname(__DIR__)) . '/VERSION';
if (file_exists($versionFile)) {
    $version = file_get_contents($versionFile);
} else {
    $version = OseAdmin::instance()->env()->getEnv();
}

$config = OseAdmin::instance()->config();

return [
    'unicaen-app' => [

        'proxies' => [
            '10.14.128.39',
            '10.14.128.100',
            '10.14.128.100',
            '10.14.128.101',
            '10.14.128.137',
        ],

        'reverse-proxies' => [
            '193.55.120.23',
            '193.55.120.24',
            '193.55.120.25',
        ],

        'masque-ip'              => '10.',
        /**
         * Informations concernant cette application
         */
        'app_infos'              => [
            'nom'                    => "OSE",
            'desc'                   => "Organisation des Services d'Enseignement",
            'version'                => $version,
            'date'                   => '31/05/2018',
            'contact'                => ['mail' => null],
            'mentionsLegales'        => $config->get('etablissement', 'mentionsLegales'),
            'informatiqueEtLibertes' => $config->get('etablissement', 'informatiqueEtLibertes'),
        ],

        /**
         * Période d'exécution de la requête de rafraîchissement de la session utilisateur, en millisecondes.
         */
        'session_refresh_period' => 10 * 60 * 1000, // 10*60*1000 ms = 10 min

        'ldap' => [
            'connection'  => [
                'default' => [
                    'params' => [
                        'host'                => ($config->get('ldap', 'actif', true) || $config->get('cas', 'actif', true)) ? $config->get('ldap', 'host') : null,
                        'username'            => $config->get('ldap', 'username'),
                        'password'            => $config->get('ldap', 'password'),
                        'baseDn'              => $config->get('ldap', 'baseDn'),
                        'bindRequiresDn'      => $config->get('ldap', 'bindRequiresDn'),
                        'accountFilterFormat' => "(&(objectClass=" . $config->get('ldap', 'loginObjectClass', 'posixAccount') . ")(" . $config->get('ldap', 'loginAttribute') . "=%s))",
                        'port'                => $config->get('ldap', 'port'),
                        'useSsl'              => $config->get('ldap', 'useSsl', false)
                    ],
                ],
            ],
            'dn'          => [
                'UTILISATEURS_BASE_DN'            => $config->get('ldap', 'utilisateursBaseDN'),
                'UTILISATEURS_DESACTIVES_BASE_DN' => $config->get('ldap', 'utilisateursDesactivesBaseDN'),
                'GROUPS_BASE_DN'                  => $config->get('ldap', 'groupsBaseDN'),
                'STRUCTURES_BASE_DN'              => $config->get('ldap', 'structuresBaseDN'),
            ],
            'filters'     => [
                'LOGIN_FILTER'                 => '(' . $config->get('ldap', 'loginAttribute') . '=%s)',
                'LOGIN_OR_NAME_FILTER'         => '(|(' . $config->get('ldap', 'loginAttribute') . '=%s)(cn=%s*))',
                'FILTER_STRUCTURE_DN'          => '(%s)',
                'FILTER_STRUCTURE_CODE_ENTITE' => '(' . $config->get('ldap', 'structureCode') . '=%s)',
                'NO_INDIVIDU_FILTER'           => '(' . $config->get('ldap', 'utilisateurCode') . '=%s)',
            ],
            'utilisateur' => [
                'LOGIN'      => $config->get('ldap', 'loginAttribute'),
                'FILTER'     => $config->get('ldap', 'utilisateurFiltre'),
                'CODE'       => $config->get('ldap', 'utilisateurCode'),
                'CODEFILTER' => $config->get('ldap', 'utilisateurCodeFiltre'),
            ],
        ],

        /**
         * Options concernant l'envoi de mail par l'application
         */
        'mail' => [
            // transport des mails
            'transport_options' => [
                'host' => $config->get('mail', 'smtpHost'),
                'port' => $config->get('mail', 'smtpPort'),
            ],
            // adresses à substituer à celles des destinataires originaux ('CURRENT_USER' équivaut à l'utilisateur connecté)
            'redirect_to'       => $config->get('mail', 'redirection'),
            // adresse d'expéditeur par défaut
            'from'              => $config->get('mail', 'from'),
            // désactivation totale de l'envoi de mail par l'application
            'do_not_send'       => $config->get('mail', 'envoiDesactive'),
        ],
    ],

    'view_helpers' => [
        'invokables' => [
            'formSearchAndSelect' => 'Application\Form\View\Helper\FormSearchAndSelect',
        ],
    ],
];
