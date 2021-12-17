<?php

return [
    'Données personnelles' => [
        'id'          => 1,
        'indicateurs' => [
            410 => [
                'enabled'           => true,
                'libelle_singulier' => '%s vacataire est en attente de validation de ses données personnelles',
                'libelle_pluriel'   => '%s vacataires sont en attente de validation de leurs données personnelles',
                'route'             => 'intervenant/dossier',
            ],
            420 => [
                'enabled'           => true,
                'libelle_singulier' => '%s vacataire a saisi des données personnelles qui diffèrent de celles importées',
                'libelle_pluriel'   => '%s vacataires ont saisi des données personnelles qui diffèrent de celles importées',
                'route'             => 'intervenant/dossier/differences',
            ],
        ],
    ],


    'Pièces justificatives' => [
        'id'          => 2,
        'indicateurs' => [
            1010 => [
                'enabled'           => true,
                'libelle_singulier' => '%s vacataire n\'a pas fourni toutes les pièces justificatives obligatoires',
                'libelle_pluriel'   => '%s vacataires n\'ont pas fourni toutes les pièces justificatives obligatoires',
                'route'             => 'piece-jointe/intervenant',
            ],
            1011 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanent n\'a pas fourni toutes ses pièces justificatives obligatoires',
                'libelle_pluriel'   => '%s permanents n\'ont pas fourni toutes leurs pièces justificatives obligatoires',
                'route'             => 'piece-jointe/intervenant',
            ],
            1020 => [
                'enabled'           => true,
                'libelle_singulier' => '%s vacataire est en attente de validation de ses pièces justificatives obligatoires',
                'libelle_pluriel'   => '%s vacataires sont en attente de validation de leurs pièces justificatives obligatoires',
                'route'             => 'piece-jointe/intervenant',
            ],
            1021 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanent est en attente de validation de ses pièces justificatives obligatoires',
                'libelle_pluriel'   => '%s permanents sont en attente de validation de leurs pièces justificatives obligatoires',
                'route'             => 'piece-jointe/intervenant',
            ],
        ],
    ],


    'Agrément' => [
        'id'          => 3,
        'indicateurs' => [
            210 => [
                'enabled'           => true,
                'libelle_singulier' => '%s vacataire est en attente d\'agrément du conseil restreint',
                'libelle_pluriel'   => '%s vacataires sont en attente d\'agrément du conseil restreint',
                'route'             => 'intervenant/agrement/conseil-restreint',
            ],
            220 => [
                'enabled'           => true,
                'libelle_singulier' => '%s vacataire est en attente d\'agrément du conseil académique',
                'libelle_pluriel'   => '%s vacataires sont en attente d\'agrément du conseil académique',
                'route'             => 'intervenant/agrement/conseil-academique',
            ],
        ],
    ],


    'Contrat / avenant' => [
        'id'          => 4,
        'indicateurs' => [
            310 => [
                'enabled'           => true,
                'libelle_singulier' => '%s vacataire a reçu l\'agrément du Conseil Académique et n\'a pas encore de contrat/avenant',
                'libelle_pluriel'   => '%s vacataires ont reçu l\'agrément du Conseil Académique et n\'ont pas encore de contrat/avenant',
                'route'             => 'intervenant/contrat',
            ],
            320 => [
                'enabled'           => true,
                'libelle_singulier' => '%s vacataire est en attente de son contrat initial',
                'libelle_pluriel'   => '%s vacataires sont en attente de leur contrat initial',
                'route'             => 'intervenant/contrat',
            ],
            330 => [
                'enabled'           => true,
                'libelle_singulier' => '%s vacataire est en attente de son avenant',
                'libelle_pluriel'   => '%s vacataires sont en attente de leur avenant',
                'route'             => 'intervenant/contrat',
            ],
            340 => [
                'enabled'           => true,
                'libelle_singulier' => '%s intervenant a saisi des heures d\'enseignements prévisionnels supplémentaires depuis l\'édition de son contrat ou avenant',
                'libelle_pluriel'   => '%s intervenants ont saisi des heures d\'enseignements <i>prévisionnels</i> supplémentaires depuis l\'édition de leur contrat ou avenant',
                'route'             => 'intervenant/validation/service/prevu',
            ],
            350 => [
                'enabled'           => true,
                'libelle_singulier' => '%s contrat/avenant a été déposé',
                'libelle_pluriel'   => '%s contrats/avenants ont été déposés',
                'route'             => 'intervenant/contrat',
            ],
            360 => [
                'enabled'           => true,
                'libelle_singulier' => '%s contrat est en attente de retour',
                'libelle_pluriel'   => '%s contrats sont en attente de retour',
                'route'             => 'intervenant/contrat',
            ],
            361 => [
                'enabled'           => true,
                'libelle_singulier' => '%s contrat  <i>envoyé par e-mail</i> est en attente de retour',
                'libelle_pluriel'   => '%s contrats  <i>envoyés par e-mail</i> sont en attente de retour',
                'route'             => 'intervenant/contrat',
            ],
            370 => [
                'enabled'           => true,
                'libelle_singulier' => '%s contrat n\'a pas de date de retour',
                'libelle_pluriel'   => '%s contrats n\'ont pas de date de retour',
                'route'             => 'intervenant/contrat',
            ],
            380 => [
                'enabled'           => true,
                'libelle_singulier' => '%s vacataire doit être pris en charge ou renouvelés dans le SI RH',
                'libelle_pluriel'   => '%s vacataires doivent être pris en charge ou renouvelés dans le SI RH',
                'route'             => 'intervenant/exporter-rh',
            ],
        ],
    ],


    'Enseignements et référentiel' => [
        'id'          => 5,
        'indicateurs' => [
            510 => [
                'enabled'           => true,
                'libelle_singulier' => '%s intervenant a saisi des enseignements dont l\'étape, l\'élément pédagogique ou la période ont disparu',
                'libelle_pluriel'   => '%s intervenants ont saisi des enseignements dont l\'étape, l\'élément pédagogique ou la période ont disparu',
                'route'             => 'intervenant/services',
            ],
        ],
    ],


    'Enseignements et référentiel <em>Permanents</em>' => [
        'id'          => 6,
        'indicateurs' => [
            610 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanent est en attente de validation de ses enseignements <i>prévisionnels</i>',
                'libelle_pluriel'   => '%s permanents sont en attente de validation de leurs enseignements <i>prévisionnels</i>',
                'route'             => 'intervenant/validation/service/prevu',
            ],
            620 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanent est en attente de validation de son référentiel <i>prévisionnel</i>',
                'libelle_pluriel'   => '%s permanents sont en attente de validation de leur référentiel <i>prévisionnel</i>',
                'route'             => 'intervenant/validation/referentiel/prevu',
            ],
            630 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanent n\'a pas clôturé la saisie de ses services <i>réalisés</i>',
                'libelle_pluriel'   => '%s permanents n\'ont pas clôturé la saisie de leurs services <i>réalisés</i>',
                'route'             => 'intervenant/services-realises',
            ],
            640 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanent a clôturé la saisie de ses services réalisés et est en attente de validation de ses enseignements <i>réalisés</i>',
                'libelle_pluriel'   => '%s permanents ont clôturé la saisie de leurs services réalisés et sont en attente de validation de leurs enseignements <i>réalisés</i>',
                'route'             => 'intervenant/validation/service/realise',
            ],
            650 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanent a clôturé la saisie de ses services réalisés et est en attente de validation de ses enseignements <i>réalisés</i> par d\'autres composantes',
                'libelle_pluriel'   => '%s permanents ont clôturé la saisie de leurs services réalisés et sont en attente de validation de leurs enseignements <i>réalisés</i> par d\'autres composantes',
                'route'             => 'intervenant/validation/service/realise',
            ],
            660 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanent a clôturé la saisie de ses services réalisés et est en attente de validation de son référentiel <i>réalisé</i>',
                'libelle_pluriel'   => '%s permanents ont clôturé la saisie de leurs services réalisés et sont en attente de validation de leur référentiel <i>réalisé</i>',
                'route'             => 'intervenant/validation/referentiel/realise',
            ],
            670 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanent a clôturé la saisie de ses services réalisés et est en attente de validation de son référentiel <i>réalisé</i> par d\'autres composantes',
                'libelle_pluriel'   => '%s permanents ont clôturé la saisie de leurs services réalisés et sont en attente de validation de leur référentiel <i>réalisé</i> par d\'autres composantes',
                'route'             => 'intervenant/validation/referentiel/realise',
            ],
        ],
    ],

    'Enseignements <em>Permanents</em> ou <em>Vacataires</em>' => [
        'id'          => 7,
        'indicateurs' => [
            710 => [
                'enabled'           => true,
                'libelle_singulier' => '%s vacataire est en attente de validation de ses enseignements <i>prévisionnels</i>',
                'libelle_pluriel'   => '%s vacataires sont en attente de validation de leurs enseignements <i>prévisionnels</i>',
                'route'             => 'intervenant/validation/service/prevu',
            ],
            720 => [
                'enabled'           => true,
                'libelle_singulier' => '%s vacataire est en attente de validation de ses enseignements <i>réalisés</i>',
                'libelle_pluriel'   => '%s vacataires sont en attente de validation de leurs enseignements <i>réalisés</i>',
                'route'             => 'intervenant/validation/service/realise',
            ],
            730 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanents est en attente de validation de ses enseignements <i>prévisionnels</i>',
                'libelle_pluriel'   => '%s permanents sont en attente de validation de leurs enseignements <i>prévisionnels</i>',
                'route'             => 'intervenant/validation/service/prevu',
            ],
            740 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanents est en attente de validation de ses enseignements <i>réalisés</i>',
                'libelle_pluriel'   => '%s permanents sont en attente de validation de leurs enseignements <i>réalisés</i>',
                'route'             => 'intervenant/validation/service/realise',
            ],
        ],
    ],


    'Affectation' => [
        'id'          => 8,
        'indicateurs' => [
            110 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanent affecté dans une autre structure a des enseignements <i>prévisionnels validés</i> dans ma structure',
                'libelle_pluriel'   => '%s permanents affectés dans une autre structure ont des enseignements <i>prévisionnels validés</i> dans ma structure',
                'route'             => 'intervenant/validation/service/prevu',
            ],
            120 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanent affecté dans ma structure a des enseignements <i>prévisionnels validés</i> dans une autre structure',
                'libelle_pluriel'   => '%s permanents affectés dans ma structure ont des enseignements <i>prévisionnels validés</i> dans une autre structure',
                'route'             => 'intervenant/validation/service/prevu',
            ],
            130 => [
                'enabled'           => true,
                'libelle_singulier' => '%s intervenant \'BIATSS\' affecté dans ma structure a des enseignements <i>prévisionnels validés</i> dans une autre structure',
                'libelle_pluriel'   => '%s intervenants \'BIATSS\' affectés dans ma structure ont des enseignements <i>prévisionnels validés</i> dans une autre structure',
                'route'             => 'intervenant/validation/service/prevu',
            ],
        ],
    ],


    'Charges d\'enseignement' => [
        'id'          => 9,
        'indicateurs' => [
            1110 => [
                'enabled'           => true,
                'libelle_singulier' => '%s intervenant a des heures d\'enseignement <i>prévisionnel</i> dépassant la charge programmée au <i>premier semestre</i>',
                'libelle_pluriel'   => '%s intervenants ont des heures d\'enseignement <i>prévisionnel</i> dépassant la charge programmée au <i>premier semestre</i>',
                'route'             => 'indicateur/depassement-charges/prevu/s1',
            ],
            1111 => [
                'enabled'           => true,
                'libelle_singulier' => '%s intervenant a des heures d\'enseignement <i>prévisionnel</i> dépassant la charge programmée au <i>second semestre</i>',
                'libelle_pluriel'   => '%s intervenants ont des heures d\'enseignement <i>prévisionnel</i> dépassant la charge programmée au <i>second semestre</i>',
                'route'             => 'indicateur/depassement-charges/prevu/s2',
            ],
            1120 => [
                'enabled'           => true,
                'libelle_singulier' => '%s intervenant a des heures d\'enseignement <i>réalisé</i> dépassant la charge programmée au <i>premier semestre</i>',
                'libelle_pluriel'   => '%s intervenants ont des heures d\'enseignement <i>réalisé</i> dépassant la charge programmée au <i>premier semestre</i>',
                'route'             => 'indicateur/depassement-charges/realise/s1',
            ],
            1121 => [
                'enabled'           => true,
                'libelle_singulier' => '%s intervenant a des heures d\'enseignement <i>réalisé</i> dépassant la charge programmée au <i>second semestre</i>',
                'libelle_pluriel'   => '%s intervenants ont des heures d\'enseignement <i>réalisé</i> dépassant la charge programmée au <i>second semestre</i>',
                'route'             => 'indicateur/depassement-charges/realise/s2',
            ],
        ],
    ],


    'Mise en paiement <em>Vacataires</em>' => [
        'id'          => 10,
        'indicateurs' => [
            910 => [
                'enabled'           => true,
                'libelle_singulier' => '%s vacataire peut faire l\'objet d\'une demande de mise en paiement',
                'libelle_pluriel'   => '%s vacataires peuvent faire l\'objet d\'une demande de mise en paiement',
                'route'             => 'intervenant/mise-en-paiement/demande',
            ],
            920 => [
                'enabled'           => true,
                'libelle_singulier' => '%s vacataire peut faire l\'objet d\'une mise en paiement',
                'libelle_pluriel'   => '%s vacataires peuvent faire l\'objet d\'une mise en paiement',
                'route'             => 'paiement/etat-demande-paiement',
            ],
        ],
    ],


    'Mise en paiement <em>Permanents</em>' => [
        'id'          => 11,
        'indicateurs' => [
            810 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanent peut faire l\'objet d\'une demande de mise en paiement',
                'libelle_pluriel'   => '%s permanents peuvent faire l\'objet d\'une demande de mise en paiement',
                'route'             => 'intervenant/mise-en-paiement/demande',
            ],
            820 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanent peut faire l\'objet d\'une mise en paiement',
                'libelle_pluriel'   => '%s permanents peuvent faire l\'objet d\'une mise en paiement',
                'route'             => 'paiement/etat-demande-paiement',
            ],
        ],
    ],
];