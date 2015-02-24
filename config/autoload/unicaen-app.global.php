<?php
/**
 * UnicaenApp Global Configuration
 *
 * If you have a ./config/autoload/ directory set up for your project, 
 * drop this config file in it and change the values as you wish.
 */
$settings = array(
    /**
     * Informations concernant cette application
     */
    'app_infos' => array(
        'nom'     => "OSE",
        'desc'    => "Organisation des Services d'Enseignement",
        'version' => "1.4.0 béta",
        'date'    => "23/02/2015",
        'contact' => array('mail' => "Contactez votre composante.", /*'tel' => "01 02 03 04 05"*/),
        'mentionsLegales'        => "http://www.unicaen.fr/outils-portail-institutionnel/mentions-legales/",
        'informatiqueEtLibertes' => "http://www.unicaen.fr/outils-portail-institutionnel/informatique-et-libertes/",
    ),
    /**
     * Période d'exécution de la requête de rafraîchissement de la session utilisateur, en millisecondes.
     */
    'session_refresh_period' => 600000, // 10*60*1000 ms = 10 min
);

/**
 * You do not need to edit below this line
 */
return array(
    'unicaen-app' => $settings,
);