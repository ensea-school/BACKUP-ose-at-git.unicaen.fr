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
        'version' => "1.2.2",
        'date'    => "30/09/2014",
        'contact' => ['mail' => "Contactez votre composante.", /*'tel' => "01 02 03 04 05"*/],
        'mentionsLegales'        => "http://www.unicaen.fr/outils-portail-institutionnel/mentions-legales/",
        'informatiqueEtLibertes' => "http://www.unicaen.fr/outils-portail-institutionnel/informatique-et-libertes/",
    ],
];

/**
 * You do not need to edit below this line
 */
return [
    'unicaen-app' => $settings,
];