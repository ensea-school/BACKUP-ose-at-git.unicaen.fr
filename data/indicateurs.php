<?php

return [
    'Missions'             => [
        'id'          => 18,
        'indicateurs' => [
            270 => [
                'enabled'           => true,
                'libelle_singulier' => '%s offre d\'emploi est en attente de validation pour publication',
                'libelle_pluriel'   => '%s offres d\'emploi sont en attente de validation pour publication',
                'route'             => 'offre-emploi',
                'irrecevables'      => false,
                'special'           => true,
            ],
            280 => [
                'enabled'           => true,
                'libelle_singulier' => '%s intervenant est en attente de validation d\'une candidature',
                'libelle_pluriel'   => '%s intervenants sont en attente de validation d\'une candidature',
                'route'             => 'intervenant/candidature',
                'irrecevables'      => false,
            ],
            350 => [
                'enabled'           => true,
                'libelle_singulier' => '%s intervenant est en attente de validation d\'une mission',
                'libelle_pluriel'   => '%s intervenants sont en attente de validation d\'une mission',
                'route'             => 'intervenant/missions',
                'irrecevables'      => false,
            ],
            360 => [
                'enabled'           => true,
                'libelle_singulier' => '%s intervenant n\'a saisi aucune heure de suivi de mission',
                'libelle_pluriel'   => '%s intervenants n\'ont saisi aucune heure de suivi de mission',
                'route'             => 'intervenant/missions-suivi',
                'irrecevables'      => false,
            ],
            370 => [
                'enabled'           => true,
                'libelle_singulier' => '%s intervenant est en attente de validation d\'heures de suivi de mission',
                'libelle_pluriel'   => '%s intervenants sont en attente de validation d\'heures de suivi de mission',
                'route'             => 'intervenant/missions-suivi',
                'irrecevables'      => false,
            ],
            380 => [
                'enabled'           => true,
                'libelle_singulier' => '%s intervenant est en attente de son contrat',
                'libelle_pluriel'   => '%s intervenants sont en attente de leur contrat',
                'route'             => 'intervenant/contrat',
                'irrecevables'      => false,
            ],
            390 => [
                'enabled'           => true,
                'libelle_singulier' => '%s intervenant est en attente de son avenant',
                'libelle_pluriel'   => '%s intervenants sont en attente de leur avenant',
                'route'             => 'intervenant/contrat',
                'irrecevables'      => false,
            ],
            391 => [
                'enabled'           => true,
                'libelle_singulier' => '%s intervenant n\'a actuellement aucune indemnité de fin de contrat',
                'libelle_pluriel'   => '%s intervenants n\'ont actuellement aucune indemnité de fin de contrat',
                'route'             => 'intervenant/prime-mission',
                'irrecevables'      => false,
            ],
            392 => [
                'enabled'           => true,
                'libelle_singulier' => '%s intervenant n\'a pas déclaré sur l\'honneur ses indémnités de fin de contrat',
                'libelle_pluriel'   => '%s intervenants n\'ont pas déclaré sur l\'honneur leurs indémnités de fin de contrat',
                'route'             => 'intervenant/prime-mission',
                'irrecevables'      => false,
            ],
            393 => [
                'enabled'           => true,
                'libelle_singulier' => '%s intervenant est en attente de validation d\'une indemnité de fin de contrat',
                'libelle_pluriel'   => '%s intervenants sont en attente de validation d\'une indemnité de fin de contrat',
                'route'             => 'intervenant/prime-mission',
                'irrecevables'      => false,
            ],
        ],
    ],
    'Données personnelles' => [
        'id'          => 1,
        'indicateurs' => [
            110 => [
                'enabled'           => true,
                'libelle_singulier' => '%s intervenant est en attente de validation de ses données personnelles',
                'libelle_pluriel'   => '%s intervenants sont en attente de validation de leurs données personnelles',
                'route'             => 'intervenant/dossier',
                'irrecevables'      => false,
            ],
            120 => [
                'enabled'           => true,
                'libelle_singulier' => '%s intervenant a saisi des données personnelles qui diffèrent de celles importées',
                'libelle_pluriel'   => '%s intervenants ont saisi des données personnelles qui diffèrent de celles importées',
                'route'             => 'intervenant/dossier/differences',
                'irrecevables'      => false,
            ],
        ],
    ],


    'Pièces justificatives' => [
        'id'          => 2,
        'indicateurs' => [
            210 => [
                'enabled'           => true,
                'libelle_singulier' => '%s vacataire n\'a pas fourni toutes les pièces justificatives obligatoires',
                'libelle_pluriel'   => '%s vacataires n\'ont pas fourni toutes les pièces justificatives obligatoires',
                'route'             => 'piece-jointe/intervenant',
                'irrecevables'      => false,
            ],
            220 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanent n\'a pas fourni toutes ses pièces justificatives obligatoires',
                'libelle_pluriel'   => '%s permanents n\'ont pas fourni toutes leurs pièces justificatives obligatoires',
                'route'             => 'piece-jointe/intervenant',
                'irrecevables'      => false,
            ],
            230 => [
                'enabled'           => true,
                'libelle_singulier' => '%s vacataire est en attente de validation de ses pièces justificatives obligatoires',
                'libelle_pluriel'   => '%s vacataires sont en attente de validation de leurs pièces justificatives obligatoires',
                'route'             => 'piece-jointe/intervenant',
                'irrecevables'      => false,
            ],
            231 => [
                'enabled'           => true,
                'libelle_singulier' => '%s vacataire est en attente de validation de ses pièces justificatives facultatives',
                'libelle_pluriel'   => '%s vacataires sont en attente de validation de leurs pièces justificatives facultatives',
                'route'             => 'piece-jointe/intervenant',
                'irrecevables'      => false,
            ],
            240 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanent est en attente de validation de ses pièces justificatives obligatoires',
                'libelle_pluriel'   => '%s permanents sont en attente de validation de leurs pièces justificatives obligatoires',
                'route'             => 'piece-jointe/intervenant',
                'irrecevables'      => false,
            ],
            241 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanent est en attente de validation de ses pièces justificatives facultatives',
                'libelle_pluriel'   => '%s permanents sont en attente de validation de leurs pièces justificatives facultatives',
                'route'             => 'piece-jointe/intervenant',
                'irrecevables'      => false,
            ],
            250 => [
                'enabled'           => true,
                'libelle_singulier' => '%s étudiant n\'a pas fourni toutes les pièces justificatives obligatoires',
                'libelle_pluriel'   => '%s étudiants n\'ont pas fourni toutes les pièces justificatives obligatoires',
                'route'             => 'piece-jointe/intervenant',
                'irrecevables'      => false,
            ],
            260 => [
                'enabled'           => true,
                'libelle_singulier' => '%s étudiant est en attente de validation de ses pièces justificatives obligatoires',
                'libelle_pluriel'   => '%s étudiants sont en attente de validation de leurs pièces justificatives obligatoires',
                'route'             => 'piece-jointe/intervenant',
                'irrecevables'      => false,
            ],
        ],
    ],


    'Agrément' => [
        'id'          => 3,
        'indicateurs' => [
            310 => [
                'enabled'           => true,
                'libelle_singulier' => '%s vacataire est en attente d\'agrément du conseil restreint',
                'libelle_pluriel'   => '%s vacataires sont en attente d\'agrément du conseil restreint',
                'route'             => 'intervenant/agrement/conseil-restreint',
                'irrecevables'      => false,
            ],
            320 => [
                'enabled'           => true,
                'libelle_singulier' => '%s vacataire est en attente d\'agrément du conseil académique',
                'libelle_pluriel'   => '%s vacataires sont en attente d\'agrément du conseil académique',
                'route'             => 'intervenant/agrement/conseil-academique',
                'irrecevables'      => false,
            ],
            330 => [
                'enabled'           => true,
                'libelle_singulier' => '%s dossier d\'intervenant est considéré comme irrecevable',
                'libelle_pluriel'   => '%s dossiers d\'intervenants sont considérés comme irrecevables',
                'route'             => 'intervenant/voir',
                'irrecevables'      => true,
            ],
        ],
    ],


    'Contrat / avenant' => [
        'id'          => 4,
        'indicateurs' => [
            410 => [
                'enabled'           => true,
                'libelle_singulier' => '%s vacataire a reçu l\'agrément du Conseil Académique et n\'a pas encore de contrat/avenant',
                'libelle_pluriel'   => '%s vacataires ont reçu l\'agrément du Conseil Académique et n\'ont pas encore de contrat/avenant',
                'route'             => 'intervenant/contrat',
                'irrecevables'      => false,
            ],
            420 => [
                'enabled'           => true,
                'libelle_singulier' => '%s est en attente de son contrat initial',
                'libelle_pluriel'   => '%s sont en attente de leur contrat initial',
                'route'             => 'intervenant/contrat',
                'irrecevables'      => false,
            ],
            430 => [
                'enabled'           => true,
                'libelle_singulier' => '%s est en attente de son avenant',
                'libelle_pluriel'   => '%s sont en attente de leur avenant',
                'route'             => 'intervenant/contrat',
                'irrecevables'      => false,
            ],
            440 => [
                'enabled'           => true,
                'libelle_singulier' => '%s intervenant a saisi des heures d\'enseignements prévisionnels supplémentaires depuis l\'édition de son contrat ou avenant',
                'libelle_pluriel'   => '%s intervenants ont saisi des heures d\'enseignements <i>prévisionnels</i> supplémentaires depuis l\'édition de leur contrat ou avenant',
                'route'             => 'intervenant/validation/enseignement/prevu',
                'irrecevables'      => false,
            ],
            450 => [
                'enabled'           => true,
                'libelle_singulier' => '%s contrat/avenant a été déposé',
                'libelle_pluriel'   => '%s contrats/avenants ont été déposés',
                'route'             => 'intervenant/contrat',
                'irrecevables'      => false,
            ],
            460 => [
                'enabled'           => true,
                'libelle_singulier' => '%s contrat est en attente de retour',
                'libelle_pluriel'   => '%s contrats sont en attente de retour',
                'route'             => 'intervenant/contrat',
                'irrecevables'      => false,
            ],
            461 => [
                'enabled'           => true,
                'libelle_singulier' => '%s contrat peut être soumis à une signature électronique',
                'libelle_pluriel'   => '%s contrats peuvent être soumis à une signature électronique',
                'route'             => 'intervenant/contrat',
                'irrecevables'      => false,
            ],
            470 => [
                'enabled'           => true,
                'libelle_singulier' => '%s contrat  <i>envoyé par e-mail</i> est en attente de retour',
                'libelle_pluriel'   => '%s contrats  <i>envoyés par e-mail</i> sont en attente de retour',
                'route'             => 'intervenant/contrat',
                'irrecevables'      => false,
            ],
            471 => [
                'enabled'           => true,
                'libelle_singulier' => '%s contrat  n\'a pas été <i>envoyé par e-mail</i> à l\'intervenant',
                'libelle_pluriel'   => '%s contrats  n\'ont pas été <i>envoyés par e-mail</i> aux intervenants',
                'route'             => 'intervenant/contrat',
                'irrecevables'      => false,
            ],
            480 => [
                'enabled'           => true,
                'libelle_singulier' => '%s contrat n\'a pas de date de retour',
                'libelle_pluriel'   => '%s contrats n\'ont pas de date de retour',
                'route'             => 'intervenant/contrat',
                'irrecevables'      => false,
            ],
            481 => [
                'enabled'           => true,
                'libelle_singulier' => '%s contrat est en attente d\'une signature électronique',
                'libelle_pluriel'   => '%s contrats sont en attente d\'une signature électronique',
                'route'             => 'intervenant/contrat',
                'irrecevables'      => false,
            ],
            490 => [
                'enabled'           => true,
                'libelle_singulier' => '%s vacataire doit être pris en charge ou renouvelés dans le SI RH',
                'libelle_pluriel'   => '%s vacataires doivent être pris en charge ou renouvelés dans le SI RH',
                'route'             => 'intervenant/exporter',
                'irrecevables'      => false,
            ],
        ],
    ],


    'Enseignements et référentiel <em>Permanents</em>' => [
        'id'          => 6,
        'indicateurs' => [
            500 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanent n\'a saisi aucun service <i>prévisionnels</i>',
                'libelle_pluriel'   => '%s permanents n\'ont saisi aucun service <i>prévisionnels</i>',
                'route'             => 'intervenant/voir',
                'irrecevables'      => false,
            ],
            505 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanent n\'a saisi aucun service <i>réalisés</i>',
                'libelle_pluriel'   => '%s permanents n\'ont saisi aucun service <i>réalisés</i>',
                'route'             => 'intervenant/voir',
                'irrecevables'      => false,
            ],
            510 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanent est en attente de validation de ses enseignements <i>prévisionnels</i>',
                'libelle_pluriel'   => '%s permanents sont en attente de validation de leurs enseignements <i>prévisionnels</i>',
                'route'             => 'intervenant/validation/enseignement/prevu',
                'irrecevables'      => false,
            ],
            520 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanent est en attente de validation de son référentiel <i>prévisionnel</i>',
                'libelle_pluriel'   => '%s permanents sont en attente de validation de leur référentiel <i>prévisionnel</i>',
                'route'             => 'intervenant/validation/referentiel/prevu',
                'irrecevables'      => false,
            ],
            530 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanent n\'a pas clôturé la saisie de ses services <i>réalisés</i>',
                'libelle_pluriel'   => '%s permanents n\'ont pas clôturé la saisie de leurs services <i>réalisés</i>',
                'route'             => 'intervenant/services-realises',
                'irrecevables'      => false,
            ],
            540 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanent a clôturé la saisie de ses services réalisés et est en attente de validation de ses enseignements <i>réalisés</i>',
                'libelle_pluriel'   => '%s permanents ont clôturé la saisie de leurs services réalisés et sont en attente de validation de leurs enseignements <i>réalisés</i>',
                'route'             => 'intervenant/validation/enseignement/realise',
                'irrecevables'      => false,
            ],
            550 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanent a clôturé la saisie de ses services réalisés et est en attente de validation de ses enseignements <i>réalisés</i> par d\'autres composantes',
                'libelle_pluriel'   => '%s permanents ont clôturé la saisie de leurs services réalisés et sont en attente de validation de leurs enseignements <i>réalisés</i> par d\'autres composantes',
                'route'             => 'intervenant/validation/enseignement/realise',
                'irrecevables'      => false,
            ],
            560 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanent a clôturé la saisie de ses services réalisés et est en attente de validation de son référentiel <i>réalisé</i>',
                'libelle_pluriel'   => '%s permanents ont clôturé la saisie de leurs services réalisés et sont en attente de validation de leur référentiel <i>réalisé</i>',
                'route'             => 'intervenant/validation/referentiel/realise',
                'irrecevables'      => false,
            ],
            570 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanent a clôturé la saisie de ses services réalisés et est en attente de validation de son référentiel <i>réalisé</i> par d\'autres composantes',
                'libelle_pluriel'   => '%s permanents ont clôturé la saisie de leurs services réalisés et sont en attente de validation de leur référentiel <i>réalisé</i> par d\'autres composantes',
                'route'             => 'intervenant/validation/referentiel/realise',
                'irrecevables'      => false,
            ],
        ],
    ],

    'Enseignements <em>Permanents</em> ou <em>Vacataires</em>' => [
        'id'          => 7,
        'indicateurs' => [
            610 => [
                'enabled'           => true,
                'libelle_singulier' => '%s intervenant a saisi des enseignements dont l\'étape, l\'élément pédagogique ou la période ont disparu',
                'libelle_pluriel'   => '%s intervenants ont saisi des enseignements dont l\'étape, l\'élément pédagogique ou la période ont disparu',
                'route'             => 'intervenant/services-prevus',
                'irrecevables'      => false,
            ],
            620 => [
                'enabled'           => true,
                'libelle_singulier' => '%s vacataire est en attente de validation de ses enseignements <i>prévisionnels</i>',
                'libelle_pluriel'   => '%s vacataires sont en attente de validation de leurs enseignements <i>prévisionnels</i>',
                'route'             => 'intervenant/validation/enseignement/prevu',
                'irrecevables'      => false,
            ],
            630 => [
                'enabled'           => true,
                'libelle_singulier' => '%s vacataire est en attente de validation de ses enseignements <i>réalisés</i>',
                'libelle_pluriel'   => '%s vacataires sont en attente de validation de leurs enseignements <i>réalisés</i>',
                'route'             => 'intervenant/validation/enseignement/realise',
                'irrecevables'      => false,
            ],
            640 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanents est en attente de validation de ses enseignements <i>prévisionnels</i>',
                'libelle_pluriel'   => '%s permanents sont en attente de validation de leurs enseignements <i>prévisionnels</i>',
                'route'             => 'intervenant/validation/enseignement/prevu',
                'irrecevables'      => false,
            ],
            650 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanents est en attente de validation de ses enseignements <i>réalisés</i>',
                'libelle_pluriel'   => '%s permanents sont en attente de validation de leurs enseignements <i>réalisés</i>',
                'route'             => 'intervenant/validation/enseignement/realise',
                'irrecevables'      => false,
            ],
        ],
    ],


    'Affectation' => [
        'id'          => 8,
        'indicateurs' => [
            710 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanent affecté dans une autre structure a des enseignements <i>prévisionnels validés</i> dans ma structure',
                'libelle_pluriel'   => '%s permanents affectés dans une autre structure ont des enseignements <i>prévisionnels validés</i> dans ma structure',
                'route'             => 'intervenant/validation/enseignement/prevu',
                'irrecevables'      => false,
            ],
            720 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanent affecté dans ma structure a des enseignements <i>prévisionnels validés</i> dans une autre structure',
                'libelle_pluriel'   => '%s permanents affectés dans ma structure ont des enseignements <i>prévisionnels validés</i> dans une autre structure',
                'route'             => 'intervenant/validation/enseignement/prevu',
                'irrecevables'      => false,
            ],
            730 => [
                'enabled'           => true,
                'libelle_singulier' => '%s intervenant \'BIATSS\' affecté dans ma structure a des enseignements <i>prévisionnels validés</i> dans une autre structure',
                'libelle_pluriel'   => '%s intervenants \'BIATSS\' affectés dans ma structure ont des enseignements <i>prévisionnels validés</i> dans une autre structure',
                'route'             => 'intervenant/validation/enseignement/prevu',
                'irrecevables'      => false,
            ],
        ],
    ],


    'Charges d\'enseignement' => [
        'id'          => 9,
        'indicateurs' => [
            810 => [
                'enabled'           => true,
                'libelle_singulier' => '%s intervenant a des heures d\'enseignement <i>prévisionnel</i> dépassant la charge programmée au <i>premier semestre</i>',
                'libelle_pluriel'   => '%s intervenants ont des heures d\'enseignement <i>prévisionnel</i> dépassant la charge programmée au <i>premier semestre</i>',
                'route'             => 'indicateur/depassement-charges/prevu/s1',
                'irrecevables'      => false,
            ],
            820 => [
                'enabled'           => true,
                'libelle_singulier' => '%s intervenant a des heures d\'enseignement <i>prévisionnel</i> dépassant la charge programmée au <i>second semestre</i>',
                'libelle_pluriel'   => '%s intervenants ont des heures d\'enseignement <i>prévisionnel</i> dépassant la charge programmée au <i>second semestre</i>',
                'route'             => 'indicateur/depassement-charges/prevu/s2',
                'irrecevables'      => false,
            ],
            830 => [
                'enabled'           => true,
                'libelle_singulier' => '%s intervenant a des heures d\'enseignement <i>réalisé</i> dépassant la charge programmée au <i>premier semestre</i>',
                'libelle_pluriel'   => '%s intervenants ont des heures d\'enseignement <i>réalisé</i> dépassant la charge programmée au <i>premier semestre</i>',
                'route'             => 'indicateur/depassement-charges/realise/s1',
                'irrecevables'      => false,
            ],
            840 => [
                'enabled'           => true,
                'libelle_singulier' => '%s intervenant a des heures d\'enseignement <i>réalisé</i> dépassant la charge programmée au <i>second semestre</i>',
                'libelle_pluriel'   => '%s intervenants ont des heures d\'enseignement <i>réalisé</i> dépassant la charge programmée au <i>second semestre</i>',
                'route'             => 'indicateur/depassement-charges/realise/s2',
                'irrecevables'      => false,
            ],
        ],
    ],


    'Mises en paiement' => [
        'id'          => 10,
        'indicateurs' => [
            910 => [
                'enabled'           => true,
                'libelle_singulier' => '%s vacataire peut faire l\'objet d\'une demande de mise en paiement',
                'libelle_pluriel'   => '%s vacataires peuvent faire l\'objet d\'une demande de mise en paiement',
                'route'             => 'intervenant/mise-en-paiement/demande',
                'irrecevables'      => false,
            ],
            920 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanent peut faire l\'objet d\'une demande de mise en paiement',
                'libelle_pluriel'   => '%s permanents peuvent faire l\'objet d\'une demande de mise en paiement',
                'route'             => 'intervenant/mise-en-paiement/demande',
                'irrecevables'      => false,
            ],
            930 => [
                'enabled'           => true,
                'libelle_singulier' => '%s vacataire peut faire l\'objet d\'une mise en paiement',
                'libelle_pluriel'   => '%s vacataires peuvent faire l\'objet d\'une mise en paiement',
                'route'             => 'paiement/etat-demande-paiement',
                'irrecevables'      => false,
            ],
            940 => [
                'enabled'           => true,
                'libelle_singulier' => '%s permanent peut faire l\'objet d\'une mise en paiement',
                'libelle_pluriel'   => '%s permanents peuvent faire l\'objet d\'une mise en paiement',
                'route'             => 'paiement/etat-demande-paiement',
                'irrecevables'      => false,
            ],
            950 => [
                'enabled'           => true,
                'libelle_singulier' => '%s étudiant peut faire l\'objet d\'une demande de mise en paiement',
                'libelle_pluriel'   => '%s étudiants peuvent faire l\'objet d\'une demande de mise en paiement',
                'route'             => 'intervenant/mise-en-paiement/demande',
                'irrecevables'      => false,
            ],
            960 => [
                'enabled'           => true,
                'libelle_singulier' => '%s étudiant peut faire l\'objet d\'une mise en paiement',
                'libelle_pluriel'   => '%s étudiants peuvent faire l\'objet d\'une mise en paiement',
                'route'             => 'paiement/etat-demande-paiement',
                'irrecevables'      => false,
            ],
        ],
    ],


    'Plafonds par intervenants' => [
        'id'          => 12,
        'indicateurs' => [
        ],
    ],


    'Plafonds par structures' => [
        'id'          => 13,
        'indicateurs' => [
        ],
    ],


    'Plafonds par fonctions référentielles' => [
        'id'          => 14,
        'indicateurs' => [
        ],
    ],


    'Plafonds par enseignements' => [
        'id'          => 15,
        'indicateurs' => [
        ],
    ],


    'Plafonds par volumes horaires d\'enseignements' => [
        'id'          => 16,
        'indicateurs' => [
        ],
    ],


    'Plafonds par types de missions' => [
        'id'          => 17,
        'indicateurs' => [
        ],
    ],
];