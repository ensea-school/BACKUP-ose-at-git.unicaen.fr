<?php
/**
 * UnicaenApp Global Configuration
 *
 * If you have a ./config/autoload/ directory set up for your project,
 * drop this config file in it and change the values as you wish.
 */
$settings = [
    /**
     * Informations concernant cette application
     */
    'app_infos'              => [
        'nom'                    => "OSE",
        'desc'                   => "Organisation des Services d'Enseignement",
        'version'                => "6.0.1",
        'date'                   => "23/10/2017",
        'contact'                => ['mail' => "assistance-ose@unicaen.fr", /*'tel' => "01 02 03 04 05"*/],
        'mentionsLegales'        => "http://gest.unicaen.fr/acces-direct/mentions-legales/",
        'informatiqueEtLibertes' => "http://gest.unicaen.fr/acces-direct/informatique-et-libertes/",
    ],
    /**
     * Période d'exécution de la requête de rafraîchissement de la session utilisateur, en millisecondes.
     */
    'session_refresh_period' => 600000, // 10*60*1000 ms = 10 min
];

/**
 * You do not need to edit below this line
 */
return [
    'unicaen-app' => $settings,
];