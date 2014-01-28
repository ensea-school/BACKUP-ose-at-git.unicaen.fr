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
        'version' => "0.0.1",
        'date'    => "28/01/2014",
        'contact' => array('mail' => "dsi.applications@unicaen.fr", /*'tel' => "01 02 03 04 05"*/),
        'mentionsLegales'        => "http://www.unicaen.fr/outils-portail-institutionnel/mentions-legales/",
        'informatiqueEtLibertes' => "http://www.unicaen.fr/outils-portail-institutionnel/informatique-et-libertes/",
    ),
);

/**
 * You do not need to edit below this line
 */
return array(
    'unicaen-app' => $settings,
);