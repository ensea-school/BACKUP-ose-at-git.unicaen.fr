<?php

return [
    /* Paramètres généraux */
    'global' => [
        /* Détermine si l'installation de OSE est faite ou non. Pour pouvoir lancer OSE, positionnez à false */
        'modeInstallation' => false,

        /* Accès en mode HTTP ou HTTPS */
        'scheme'           => 'http',

        /* Adresse d'accès à OSE (par exemple ose.unicaen.fr)
         * Correspond à l'url à entrer pour accéder à la page d'accueil de OSE, sans http:// ou https:// */
        'domain'           => 'n301z-dsi007.campus.unicaen.fr/ose/dev',

        /* Affichage complet des erreurs (false en production) */
        'affichageErreurs' => true,
    ],


    /* Configuration du mode maintenance */
    'maintenance' => [
        /* Détermine si le mode maintenante est activé ou non */
        'modeMaintenance' => false,

        /* Message précisant pourquoi l'application est en maintenance */
        'messageInfo'  => 'OSE est actuellement en cours de mise à jour. Veuillez nous excuser pour ce déagrément.',

        /* Liste blanche d'adresses IP pouvant accéder à l'application, même en mode maintenance */
        'whiteList'    => [
  //          ['127.0.0.1'], // localhost
    //        ['10.26.24.16'], // Olivier
//            ['10.26.4.17'], // Laurent
      //      ['10.26.24.39'], // Anthony
        ],
    ],


    /* Liens divers */
    'liens'       => [
        /* Mail d'assistance OSE de votre établissement */
        'contactAssistance'      => 'assistance-ose@unicaen.fr',

        /* Page web des mentions légales de votre établissement */
        'mentionsLegales'        => "http://gest.unicaen.fr/acces-direct/mentions-legales/",

        /* Page web mentionnant vos règles "informatique et liberté" */
        'informatiqueEtLibertes' => "http://gest.unicaen.fr/acces-direct/informatique-et-libertes/",
    ],


    /* Base de données */
    'bdd'         => [
        /* IP ou nom DNS du serveur de base de données */
        'host'            => 'osedb.unicaen.fr',

        /* Port d'accès au serveur */
        'port'            => 1524,

        /* Nom de la base de données */
        'dbname'          => 'OSEDEV',

        /* Nom d'utilisateur */
        'username'        => 'ose',

        /* Mot de passe */
        'password'        => 'oustBN4',

        /* Générer systématiquement les proxies de la base de données (utile uniquement en mode développement) */
        'generateProxies' => true,

        /* ---- Autres bases de données ---- */
//        /* DEPLOY */ 'username'=> 'deploy', 'password'=> 'mdp_deploy',
        /* TEST */ 'dbname'          => 'OSETEST',
//        /* DEMO */ 'dbname'   => 'OSEDEMO',
//        /* PROD */ 'dbname'   => 'OSEPROD', 'port' => '1523',
    ],


    /* Configuration LDAP */
    'ldap'        => [
        /* IP ou nom DNS de la machine hébergeant le serveur LDAP */
        'host'                         => 'ldap.unicaen.fr',

        /* UID d'accès au serveur (format uid=nom_utilisateur,ou=system,dc=mon_dc,dc=fr*/
        'username'                     => "uid=ose,ou=system,dc=unicaen,dc=fr",

        /* Mot de passe de l'utilisateur système */
        'password'                     => "UtC2AAAsUk3x",

        /* DN par défaut */
        'baseDn'                       => "ou=people,dc=unicaen,dc=fr",

        /* Port d'accès au serveur */
        'port'                         => 389,

        /* bindRequiresDn */
        'bindRequiresDn'               => true,

        /* Attribut LDAP correspondant au LOGIN de l'utilisateur */
        'loginAttribute'               => 'supannAliasLogin',

        /* Code de l'utilisateur.
         * à rappchcher ensuite de intervenant.utilisateurCode pour faire la correspondance utilisateur => intervenant */
        'utilisateurCode'              => 'supannEmpId',

        /* Filtre pour la recherche d'utilisateurs */
        'utilisateurFiltre'            => '(eduPersonAffiliation=member)(!(eduPersonAffiliation=student))',

        /* DN des utilisateurs */
        'utilisateursBaseDN'           => 'ou=people,dc=unicaen,dc=fr',

        /* DN des utilisateurs désactivés (si besoin) */
        'utilisateursDesactivesBaseDN' => 'ou=deactivated,dc=unicaen,dc=fr',

        /* DN pour les groupes d'utilisateurs */
        'groupsBaseDN'                 => 'ou=groups,dc=unicaen,dc=fr',

        /* DN pour les structures présentes dans le LDAP (si besoin) */
        'structuresBaseDN'             => 'ou=structures,dc=unicaen,dc=fr',

        /* Attribut correspondant au code de la structure */
        'structureCode'                => 'supannCodeEntite',
    ],


    /* Envoi de mails */
    'mail'        => [
        /* IP ou nom DNS du serveur SMTP */
        'smtpHost'       => 'smtpcc.unicaen.fr',

        /* Port du serveur SMTP (généralement, 25) */
        'smtpPort'       => 25,

        /* Mails utilisés pour la redirection.
         * Fournir sous forme de tableau, CURRENT_USER enverra les mails à l'utilisateur connecté qui a déclenché l'action) */
        'redirection'    => ['laurent.lecluse@unicaen.fr', /*'CURRENT_USER'*/],

        /* Envoi de mail activé ou non (OUI en production, normalement) */
        'envoiDesactive' => false,
    ],


    /* CAS */
    'cas'         => [
        /* Détermine si OSE est cassifiée ou pas */
        'actif'   => false,

        /* IP ou nom DNS du serveur CAS */
        'host'    => 'cas.unicaen.fr',

        /* Port */
        'port'    => 443,

        /* Version du serveur CAS */
        'version' => '2.0',

        /* URI éventuelle */
        'uri'     => '',

        /* Mode débogage (pour les tests uniquement) */
        'debug'   => false,
    ],

];
