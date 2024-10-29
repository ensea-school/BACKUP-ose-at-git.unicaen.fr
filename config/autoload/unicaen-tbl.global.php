<?php

return [
    'unicaen-tbl' => [
        'tableaux_bord' => [
            'chargens_seuils_def' => [
                'order'   => 1,
                'process' => 'DbDiff',
                'cols'    => [
                    'ANNEE_ID',
                    'SCENARIO_ID',
                    'STRUCTURE_ID',
                    'GROUPE_TYPE_FORMATION_ID',
                    'TYPE_INTERVENTION_ID',
                    'DEDOUBLEMENT',
                ],
                'key'     => [
                    'SCENARIO_ID',
                    'TYPE_INTERVENTION_ID',
                    'STRUCTURE_ID',
                    'GROUPE_TYPE_FORMATION_ID',
                    'ANNEE_ID',
                ],
            ],

            'chargens' => [
                'order'   => 1,
                'process' => 'DbDiff',
                'cols'    => [
                    'ANNEE_ID',
                    'NOEUD_ID',
                    'SCENARIO_ID',
                    'TYPE_HEURES_ID',
                    'TYPE_INTERVENTION_ID',
                    'ELEMENT_PEDAGOGIQUE_ID',
                    'ETAPE_ID',
                    'ETAPE_ENS_ID',
                    'STRUCTURE_ID',
                    'GROUPE_TYPE_FORMATION_ID',
                    'OUVERTURE',
                    'DEDOUBLEMENT',
                    'ASSIDUITE',
                    'EFFECTIF',
                    'HEURES_ENS',
                    'GROUPES',
                    'HEURES',
                    'HETD',
                ],
                'key'     => [
                    'ANNEE_ID',
                    'NOEUD_ID',
                    'SCENARIO_ID',
                    'TYPE_HEURES_ID',
                    'TYPE_INTERVENTION_ID',
                    'ELEMENT_PEDAGOGIQUE_ID',
                    'ETAPE_ID',
                    'ETAPE_ENS_ID',
                    'STRUCTURE_ID',
                    'GROUPE_TYPE_FORMATION_ID',
                ],
            ],

            'formule' => [
                'order'   => 1,
                'process' => 'Plsql',
                'command' => 'OSE_FORMULE.CALCULER_TBL',
            ],

            'dmep_liquidation' => [
                'order'   => 1,
                'process' => 'DbDiff',
                'cols'    => [
                    'ANNEE_ID',
                    'TYPE_RESSOURCE_ID',
                    'STRUCTURE_ID',
                    'STRUCTURE_IDS',
                    'HEURES',
                ],
                'key'     => [
                    'ANNEE_ID',
                    'TYPE_RESSOURCE_ID',
                    'STRUCTURE_ID',
                ],
            ],

            'candidature' => [
                'order'              => 1,
                'process'            => 'DbDiff',
                'cols'               => [
                    'ANNEE_ID',
                    'INTERVENANT_ID',
                    'STRUCTURE_ID',
                    'OFFRE_EMPLOI_ID',
                    'CANDIDATURE_ID',
                    'VALIDATION_ID',
                    'ACTIF',
                    'REPONSE',
                    'ACCEPTEE',
                    'REFUSEE',
                ],
                'key'                => [
                    'ANNEE_ID',
                    'INTERVENANT_ID',
                    'OFFRE_EMPLOI_ID',
                ],
                'key_values_if_null' => [
                    'OFFRE_EMPLOI_ID' => 0,
                ],
            ],

            'piece_jointe_demande' => [
                'order'   => 2,
                'process' => 'DbDiff',
                'cols'    => [
                    'ANNEE_ID',
                    'CODE_INTERVENANT',
                    'INTERVENANT_ID',
                    'TYPE_PIECE_JOINTE_ID',
                    'HEURES_POUR_SEUIL',
                    'OBLIGATOIRE',
                    'HEURES_POUR_SEUIL_HETD',
                    'DUREE_VIE',
                ],
                'key'     => [
                    'INTERVENANT_ID',
                    'TYPE_PIECE_JOINTE_ID',
                ],
            ],

            'piece_jointe_fournie' => [
                'order'              => 3,
                'process'            => 'DbDiff',
                'cols'               => [
                    'ANNEE_ID',
                    'CODE_INTERVENANT',
                    'TYPE_PIECE_JOINTE_ID',
                    'INTERVENANT_ID',
                    'PIECE_JOINTE_ID',
                    'VALIDATION_ID',
                    'FICHIER_ID',
                    'DUREE_VIE',
                    'DATE_VALIDITE',
                    'DATE_ARCHIVE',
                ],
                'key'                => [
                    'TYPE_PIECE_JOINTE_ID',
                    'INTERVENANT_ID',
                    'VALIDATION_ID',
                    'FICHIER_ID',
                ],
                'key_values_if_null' => [
                    'VALIDATION_ID' => 0,
                    'FICHIER_ID'    => 0,
                ],
            ],

            'agrement' => [
                'order'              => 4,
                'process'            => 'DbDiff',
                'cols'               => [
                    'ANNEE_ID',
                    'ANNEE_AGREMENT',
                    'TYPE_AGREMENT_ID',
                    'INTERVENANT_ID',
                    'CODE_INTERVENANT',
                    'STRUCTURE_ID',
                    'AGREMENT_ID',
                    'DUREE_VIE',
                ],
                'key'                => [
                    'ANNEE_AGREMENT',
                    'TYPE_AGREMENT_ID',
                    'INTERVENANT_ID',
                    'STRUCTURE_ID',
                ],
                'key_values_if_null' => [
                    'ANNEE_AGREMENT' => 0,
                    'STRUCTURE_ID'   => 0,
                ],
            ],

            'cloture_realise' => [
                'order'   => 5,
                'process' => 'DbDiff',
                'cols'    => [
                    'ANNEE_ID',
                    'INTERVENANT_ID',
                    'ACTIF',
                    'CLOTURE',
                ],
                'key'     => [
                    'INTERVENANT_ID',
                ],
            ],

            'mission'       => [
                'order'              => 6,
                'process'            => 'DbDiff',
                'cols'               => [
                    'ANNEE_ID',
                    'INTERVENANT_ID',
                    'ACTIF',
                    'MISSION_ID',
                    'STRUCTURE_ID',
                    'INTERVENANT_STRUCTURE_ID',
                    'VALIDE',
                    'VALIDATION_ID',
                    'CONTRACTUALISE',
                    'CONTRAT_ID',
                    'HEURES_PREVUES_SAISIES',
                    'HEURES_PREVUES_VALIDEES',
                    'HEURES_REALISEES_SAISIES',
                    'HEURES_REALISEES_VALIDEES',
                ],
                'key'                => [
                    'INTERVENANT_ID',
                    'MISSION_ID',
                ],
                'key_values_if_null' => [
                    'MISSION_ID' => 0,
                ],
            ],

            'contrat' => [
                'order'              => 7,
                'process'            => \Contrat\Tbl\Process\ContratProcess::class,
                'cols'               => [
                    "ID",
                    "ANNEE_ID",
                    "INTERVENANT_ID",
                    "ACTIF",
                    "STRUCTURE_ID",
                    "NBVH",
                    "EDITE",
                    "SIGNE",
                    "UUID",
                    "CONTRAT_ID",
                    "CONTRAT_PARENT_ID",
                    "TYPE_CONTRAT_ID",
                    "TYPE_SERVICE_ID",
                    "MISSION_ID",
                    "SERVICE_ID",
                    "SERVICE_REFERENTIEL_ID",
                    "VOLUME_HORAIRE_MISSION_ID",
                    "VOLUME_HORAIRE_ID",
                    "VOLUME_HORAIRE_REF_ID",
                    "DATE_DEBUT",
                    "DATE_FIN",
                    "DATE_CREATION",
                    "CM",
                    "TD",
                    "TP",
                    "AUTRES",
                    "HEURES",
                    "HETD",
                    "AUTRE_LIBELLE",
                    "TAUX_REMU_ID",
                    "TAUX_REMU_VALEUR",
                    "TAUX_REMU_DATE",
                    "TAUX_REMU_MAJORE_ID",
                    "TAUX_REMU_MAJORE_VALEUR",
                    "TAUX_REMU_MAJORE_DATE",
                    "TAUX_CONGES_PAYES",
                ],
                'key'                => [
                    'INTERVENANT_ID',
                    'STRUCTURE_ID',
                    'CONTRAT_ID',
                    "VOLUME_HORAIRE_MISSION_ID",
                    "VOLUME_HORAIRE_ID",
                    "VOLUME_HORAIRE_REF_ID",
                ],
                'key_values_if_null' => [
                    'STRUCTURE_ID' => 0,
                ],
            ],

            'dossier' => [
                'order'   => 8,
                'process' => 'DbDiff',
                'cols'    => [
                    'ANNEE_ID',
                    'INTERVENANT_ID',
                    'ACTIF',
                    'DOSSIER_ID',
                    'VALIDATION_ID',
                    'COMPLETUDE_STATUT',
                    'COMPLETUDE_IDENTITE',
                    'COMPLETUDE_IDENTITE_COMP',
                    'COMPLETUDE_CONTACT',
                    'COMPLETUDE_ADRESSE',
                    'COMPLETUDE_INSEE',
                    'COMPLETUDE_BANQUE',
                    'COMPLETUDE_EMPLOYEUR',
                    'COMPLETUDE_AUTRES',
                ],
                'key'     => [
                    'INTERVENANT_ID',
                ],
            ],

            'paiement' => [
                'order'              => 9,
                'process'            => \Paiement\Tbl\Process\PaiementProcess::class,
                'cols'               => [
                    'ANNEE_ID',
                    'SERVICE_ID',
                    'SERVICE_REFERENTIEL_ID',
                    'FORMULE_RES_SERVICE_ID',
                    'FORMULE_RES_SERVICE_REF_ID',
                    'MISSION_ID',
                    'TYPE_HEURES_ID',
                    'TYPE_INTERVENANT_ID',
                    'INTERVENANT_ID',
                    'STRUCTURE_ID',
                    'MISE_EN_PAIEMENT_ID',
                    'PERIODE_PAIEMENT_ID',
                    'CENTRE_COUT_ID',
                    'DOMAINE_FONCTIONNEL_ID',
                    'PERIODE_ENS_ID',
                    'HEURES_A_PAYER_AA',
                    'HEURES_A_PAYER_AC',
                    'HEURES_DEMANDEES_AA',
                    'HEURES_DEMANDEES_AC',
                    'HEURES_PAYEES_AA',
                    'HEURES_PAYEES_AC',
                    'TAUX_REMU_ID',
                    'TAUX_HORAIRE',
                    'TAUX_CONGES_PAYES',
                ],
                'key'                => [
                    'FORMULE_RES_SERVICE_ID',
                    'FORMULE_RES_SERVICE_REF_ID',
                    'MISSION_ID',
                    'TYPE_HEURES_ID',
                    'INTERVENANT_ID',
                    'PERIODE_ENS_ID',
                    'MISE_EN_PAIEMENT_ID',
                    'TAUX_REMU_ID',
                    'TAUX_HORAIRE',
                ],
                'key_values_if_null' => [
                    'FORMULE_RES_SERVICE_ID'     => 0,
                    'FORMULE_RES_SERVICE_REF_ID' => 0,
                    'MISSION_ID'                 => 0,
                    'MISE_EN_PAIEMENT_ID'        => 0,
                    'PERIODE_ENS_ID'             => 0,
                ],
            ],

            'piece_jointe' => [
                'order'   => 10,
                'process' => 'DbDiff',
                'cols'    => [
                    'ANNEE_ID',
                    'TYPE_PIECE_JOINTE_ID',
                    'PIECE_JOINTE_ID',
                    'INTERVENANT_ID',
                    'DEMANDEE',
                    'FOURNIE',
                    'VALIDEE',
                    'HEURES_POUR_SEUIL',
                    'OBLIGATOIRE',
                ],
                'key'     => [
                    'TYPE_PIECE_JOINTE_ID',
                    'INTERVENANT_ID',
                ],
            ],

            'referentiel' => [
                'order'   => 11,
                'process' => 'DbDiff',
                'cols'    => [
                    'ANNEE_ID',
                    'INTERVENANT_ID',
                    'TYPE_VOLUME_HORAIRE_ID',
                    'ACTIF',
                    'STRUCTURE_ID',
                    'INTERVENANT_STRUCTURE_ID',
                    'SERVICE_REFERENTIEL_ID',
                    'FONCTION_REFERENTIEL_ID',
                    'TYPE_INTERVENANT_ID',
                    'TYPE_INTERVENANT_CODE',
                    'TYPE_VOLUME_HORAIRE_CODE',
                    'NBVH',
                    'HEURES',
                    'VALIDE',
                ],
                'key'     => [
                    'INTERVENANT_ID',
                    'TYPE_VOLUME_HORAIRE_ID',
                    'STRUCTURE_ID',
                    'SERVICE_REFERENTIEL_ID',
                ],
            ],

            'validation_enseignement' => [
                'order'              => 12,
                'process'            => 'DbDiff',
                'cols'               => [
                    'ANNEE_ID',
                    'INTERVENANT_ID',
                    'STRUCTURE_ID',
                    'TYPE_VOLUME_HORAIRE_ID',
                    'SERVICE_ID',
                    'VOLUME_HORAIRE_ID',
                    'AUTO_VALIDATION',
                    'VALIDATION_ID',
                ],
                'key'                => [
                    'INTERVENANT_ID',
                    'STRUCTURE_ID',
                    'TYPE_VOLUME_HORAIRE_ID',
                    'SERVICE_ID',
                    'VOLUME_HORAIRE_ID',
                    'VALIDATION_ID',
                ],
                'key_values_if_null' => [
                    'VALIDATION_ID' => 0,
                ],
            ],

            'validation_referentiel' => [
                'order'              => 13,
                'process'            => 'DbDiff',
                'cols'               => [
                    'ANNEE_ID',
                    'INTERVENANT_ID',
                    'STRUCTURE_ID',
                    'TYPE_VOLUME_HORAIRE_ID',
                    'SERVICE_REFERENTIEL_ID',
                    'VOLUME_HORAIRE_REF_ID',
                    'AUTO_VALIDATION',
                    'VALIDATION_ID',
                ],
                'key'                => [
                    'INTERVENANT_ID',
                    'STRUCTURE_ID',
                    'TYPE_VOLUME_HORAIRE_ID',
                    'SERVICE_REFERENTIEL_ID',
                    'VOLUME_HORAIRE_REF_ID',
                    'VALIDATION_ID',
                ],
                'key_values_if_null' => [
                    'VALIDATION_ID' => 0,
                ],
            ],

            'service' => [
                'order'   => 14,
                'process' => 'DbDiff',
                'cols'    => [
                    'ANNEE_ID',
                    'INTERVENANT_ID',
                    'ACTIF',
                    'SERVICE_ID',
                    'ELEMENT_PEDAGOGIQUE_ID',
                    'TYPE_INTERVENANT_ID',
                    'TYPE_INTERVENANT_CODE',
                    'STRUCTURE_ID',
                    'INTERVENANT_STRUCTURE_ID',
                    'ELEMENT_PEDAGOGIQUE_PERIODE_ID',
                    'ETAPE_ID',
                    'TYPE_VOLUME_HORAIRE_ID',
                    'TYPE_VOLUME_HORAIRE_CODE',
                    'ELEMENT_PEDAGOGIQUE_HISTO',
                    'ETAPE_HISTO',
                    'HAS_HEURES_MAUVAISE_PERIODE',
                    'NBVH',
                    'HEURES',
                    'VALIDE',
                ],
                'key'     => [
                    'SERVICE_ID',
                    'TYPE_VOLUME_HORAIRE_ID',
                ],
            ],

            'mission_prime' => [
                'order'   => 15,
                'process' => 'DbDiff',
                'cols'    => [
                    'ANNEE_ID',
                    'INTERVENANT_ID',
                    'STRUCTURE_ID',
                    'ACTIF',
                    'PRIME',
                    'DECLARATION',
                    'VALIDATION',
                    'REFUS',
                ],
                'key'     => [
                    'INTERVENANT_ID',
                ],

            ],

            'workflow' => [
                'order'   => 16,
                'process' => 'Plsql',
                'command' => 'OSE_WORKFLOW.CALCULER_TBL',
            ],

            'plafond_structure' => [
                'order'              => 17,
                'process'            => 'DbDiff',
                'cols'               => [
                    'PLAFOND_ID',
                    'ANNEE_ID',
                    'TYPE_VOLUME_HORAIRE_ID',
                    'INTERVENANT_ID',
                    'STRUCTURE_ID',
                    'HEURES',
                    'PLAFOND',
                    'PLAFOND_ETAT_ID',
                    'DEROGATION',
                    'DEPASSEMENT',
                ],
                'key'                => [
                    'PLAFOND_ID',
                    'ANNEE_ID',
                    'TYPE_VOLUME_HORAIRE_ID',
                    'INTERVENANT_ID',
                    'STRUCTURE_ID',
                ],
                'key_values_if_null' => [
                    'PLAFOND_ID'             => 0,
                    'INTERVENANT_ID'         => 0,
                    'TYPE_VOLUME_HORAIRE_ID' => 0,
                ],
            ],

            'plafond_intervenant' => [
                'order'              => 18,
                'process'            => 'DbDiff',
                'cols'               => [
                    'PLAFOND_ID',
                    'ANNEE_ID',
                    'TYPE_VOLUME_HORAIRE_ID',
                    'INTERVENANT_ID',
                    'HEURES',
                    'PLAFOND',
                    'PLAFOND_ETAT_ID',
                    'DEROGATION',
                    'DEPASSEMENT',
                ],
                'key'                => [
                    'PLAFOND_ID',
                    'ANNEE_ID',
                    'TYPE_VOLUME_HORAIRE_ID',
                    'INTERVENANT_ID',
                ],
                'key_values_if_null' => [
                    'PLAFOND_ID'             => 0,
                    'TYPE_VOLUME_HORAIRE_ID' => 0,
                ],
            ],

            'plafond_element' => [
                'order'              => 19,
                'process'            => 'DbDiff',
                'cols'               => [
                    'PLAFOND_ID',
                    'ANNEE_ID',
                    'TYPE_VOLUME_HORAIRE_ID',
                    'INTERVENANT_ID',
                    'ELEMENT_PEDAGOGIQUE_ID',
                    'HEURES',
                    'PLAFOND',
                    'PLAFOND_ETAT_ID',
                    'DEROGATION',
                    'DEPASSEMENT',
                ],
                'key'                => [
                    'PLAFOND_ID',
                    'ANNEE_ID',
                    'TYPE_VOLUME_HORAIRE_ID',
                    'INTERVENANT_ID',
                    'ELEMENT_PEDAGOGIQUE_ID',
                ],
                'key_values_if_null' => [
                    'PLAFOND_ID'             => 0,
                    'TYPE_VOLUME_HORAIRE_ID' => 0,
                ],
            ],

            'plafond_volume_horaire' => [
                'order'              => 20,
                'process'            => 'DbDiff',
                'cols'               => [
                    'PLAFOND_ID',
                    'ANNEE_ID',
                    'TYPE_VOLUME_HORAIRE_ID',
                    'INTERVENANT_ID',
                    'ELEMENT_PEDAGOGIQUE_ID',
                    'TYPE_INTERVENTION_ID',
                    'HEURES',
                    'PLAFOND',
                    'PLAFOND_ETAT_ID',
                    'DEROGATION',
                    'DEPASSEMENT',
                ],
                'key'                => [
                    'PLAFOND_ID',
                    'ANNEE_ID',
                    'TYPE_VOLUME_HORAIRE_ID',
                    'INTERVENANT_ID',
                    'ELEMENT_PEDAGOGIQUE_ID',
                    'TYPE_INTERVENTION_ID',
                ],
                'key_values_if_null' => [
                    'PLAFOND_ID'             => 0,
                    'TYPE_VOLUME_HORAIRE_ID' => 0,
                    'TYPE_INTERVENTION_ID'   => 0,
                ],
            ],

            'plafond_referentiel' => [
                'order'              => 21,
                'process'            => 'DbDiff',
                'cols'               => [
                    'PLAFOND_ID',
                    'ANNEE_ID',
                    'TYPE_VOLUME_HORAIRE_ID',
                    'INTERVENANT_ID',
                    'FONCTION_REFERENTIEL_ID',
                    'HEURES',
                    'PLAFOND',
                    'PLAFOND_ETAT_ID',
                    'DEROGATION',
                    'DEPASSEMENT',
                ],
                'key'                => [
                    'PLAFOND_ID',
                    'ANNEE_ID',
                    'TYPE_VOLUME_HORAIRE_ID',
                    'INTERVENANT_ID',
                    'FONCTION_REFERENTIEL_ID',
                ],
                'key_values_if_null' => [
                    'PLAFOND_ID'             => 0,
                    'TYPE_VOLUME_HORAIRE_ID' => 0,
                ],
            ],

            'plafond_mission' => [
                'order'              => 22,
                'process'            => 'DbDiff',
                'cols'               => [
                    'PLAFOND_ID',
                    'ANNEE_ID',
                    'TYPE_VOLUME_HORAIRE_ID',
                    'INTERVENANT_ID',
                    'TYPE_MISSION_ID',
                    'HEURES',
                    'PLAFOND',
                    'PLAFOND_ETAT_ID',
                    'DEROGATION',
                    'DEPASSEMENT',
                ],
                'key'                => [
                    'PLAFOND_ID',
                    'ANNEE_ID',
                    'TYPE_VOLUME_HORAIRE_ID',
                    'INTERVENANT_ID',
                    'TYPE_MISSION_ID',
                ],
                'key_values_if_null' => [
                    'PLAFOND_ID'             => 0,
                    'TYPE_VOLUME_HORAIRE_ID' => 0,
                ],
            ],
        ],
    ],
];