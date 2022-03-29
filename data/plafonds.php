<?php

$sqlDir = __DIR__ . '/plafonds-sql/';

return [
    'etats'      => [
        'desactive'  => ['libelle' => 'Désactivé', 'bloquant' => false],
        'indicateur' => ['libelle' => 'Indicateur', 'bloquant' => false],
        'informatif' => ['libelle' => 'Informatif', 'bloquant' => false],
        'bloquant'   => ['libelle' => 'Bloquant', 'bloquant' => true],
    ],
    'perimetres' => [
        'structure'      => 'Composante',
        'intervenant'    => 'Intervenant',
        'element'        => 'Elément pédagogique',
        'volume_horaire' => 'Volume horaire',
        'referentiel'    => 'Fonction référentielle',
    ],
    'plafonds'   => [
        10 => [
            'libelle'   => 'Nombre d\'heures complémentaires maximum (hors rémunération au titre de l\'article d714-60 du code de l\'éducation)',
            'perimetre' => 'intervenant',
            'requete'   => file_get_contents($sqlDir . '10.sql'),
        ],
        11 => [
            'libelle'   => 'Nombre maximum d\'heures équivalent TD par intervenant selon son statut',
            'perimetre' => 'intervenant',
            'requete'   => file_get_contents($sqlDir . '11.sql'),
        ],
        12 => [
            'libelle'   => 'Montant maximal par intervenant de la prime D714-60 du code de l\'éducation',
            'perimetre' => 'intervenant',
            'requete'   => file_get_contents($sqlDir . '12.sql'),
        ],
        13 => [
            'libelle'   => 'HETD max. en formation initiale hors EAD',
            'perimetre' => 'intervenant',
            'requete'   => file_get_contents($sqlDir . '13.sql'),
        ],
        14 => [
            'libelle'   => 'Plafond des services par rapport à la charge d\'enseignement',
            'perimetre' => 'volume_horaire',
            'message'   => 'Dépassement de la charge pour :sujet',
            'requete'   => file_get_contents($sqlDir . '14.sql'),
        ],
        15 => [
            'libelle'   => 'Heures max. de référentiel par structure',
            'perimetre' => 'structure',
            'message'   => 'Heures max. de référentiel pour :sujet',
            'requete'   => file_get_contents($sqlDir . '15.sql'),
        ],
        17 => [
            'libelle'   => 'Heures max. de référentiel par intervenant et par fonction référentielle',
            'perimetre' => 'referentiel',
            'message'   => 'Heures max. de référentiel par intervenant pour :sujet',
            'requete'   => file_get_contents($sqlDir . '17.sql'),
        ],
        18 => [
            'libelle'   => 'Heures max. de référentiel par intervenant selon son statut',
            'perimetre' => 'intervenant',
            'requete'   => file_get_contents($sqlDir . '18.sql'),
        ],

    ],
];