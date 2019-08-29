<?php

return [
    "CM" => [
        "LIBELLE"                       => "Cours magistraux",
        "TAUX_HETD_SERVICE"             => 1.5,
        "TAUX_HETD_COMPLEMENTAIRE"      => 1.5,
        "VISIBLE"                       => true,
        "REGLE_FOAD"                    => false,
        "REGLE_FC"                      => false,
        "TYPE_INTERVENTION_MAQUETTE_ID" => 0,
        "VISIBLE_EXTERIEUR"             => true,
    ],
    "TD" => [
        "LIBELLE"                       => "Travaux dirigés",
        "TAUX_HETD_SERVICE"             => 1.0,
        "TAUX_HETD_COMPLEMENTAIRE"      => 1.0,
        "VISIBLE"                       => true,
        "REGLE_FOAD"                    => false,
        "REGLE_FC"                      => false,
        "TYPE_INTERVENTION_MAQUETTE_ID" => 0,
        "VISIBLE_EXTERIEUR"             => true,
    ],
    "TP" => [
        "LIBELLE"                       => "Travaux pratiques",
        "TAUX_HETD_SERVICE"             => 1.0,
        "TAUX_HETD_COMPLEMENTAIRE"      => 2 / 3,
        "VISIBLE"                       => true,
        "REGLE_FOAD"                    => false,
        "REGLE_FC"                      => false,
        "TYPE_INTERVENTION_MAQUETTE_ID" => 0,
        "VISIBLE_EXTERIEUR"             => true,
    ],
];