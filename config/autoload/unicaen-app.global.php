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
    'app_infos' => [
        'nom'     => "OSE",
        'desc'    => "Organisation des Services d'Enseignement",
        'version' => "4.2.0",
        'date'    => "23/08/2016",
        'contact' => ['mail' => "Contactez votre composante.", /*'tel' => "01 02 03 04 05"*/],
        'mentionsLegales'        => "http://www.unicaen.fr/outils-portail-institutionnel/mentions-legales/",
        'informatiqueEtLibertes' => "http://www.unicaen.fr/outils-portail-institutionnel/informatique-et-libertes/",
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