<?php
/**
 * Configuration globale du module UnicaenApp.
 *
 * Copiez ce fichier dans le répertoire "config/autoload" de l'application,
 * enlevez l'extension ".dist" et adaptez son contenu à vos besoins.
 */
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
            'contact'                => ['mail' => "assistance-ose@unicaen.fr", /*'tel' => "01 02 03 04 05"*/],
            'mentionsLegales'        => "http://gest.unicaen.fr/acces-direct/mentions-legales/",
            'informatiqueEtLibertes' => "http://gest.unicaen.fr/acces-direct/informatique-et-libertes/",
        ],

        /**
         * Période d'exécution de la requête de rafraîchissement de la session utilisateur, en millisecondes.
         */
        'session_refresh_period' => 600000, // 10*60*1000 ms = 10 min
    ],
];