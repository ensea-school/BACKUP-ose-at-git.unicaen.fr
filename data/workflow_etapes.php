<?php

use Application\Entity\Db\Perimetre;
use Intervenant\Entity\Db\TypeIntervenant;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Entity\Db\WorkflowEtapeDependance;


return [
    WorkflowEtape::CANDIDATURE_SAISIE              => [
        'libelle_intervenant' => "Je postule à une ou plusieurs offres d'emploi",
        'libelle_autres'      => "J'accède aux candidatures",
        'route'               => 'intervenant/candidature',
        'desc_non_franchie'   => "Aucune candidature n'a été faite",
        'perimetre'           => Perimetre::COMPOSANTE,
        'contraintes'         => [],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => 'Une ou plusieurs candidatures ont été faites',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => null,
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => null,
        ],
        'dependances'         => [],
    ],
    WorkflowEtape::DONNEES_PERSO_SAISIE            => [
        'libelle_intervenant' => "Je saisis mes données personnelles",
        'libelle_autres'      => "J'accède aux données personnelles",
        'route'               => 'intervenant/dossier',
        'desc_non_franchie'   => "Les données personnelles n'ont pas été saisies",
        'perimetre'           => Perimetre::ETABLISSEMENT,
        'contraintes'         => [],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => 'Les données personnelles sont complétées en partie',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => null,
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'Les données personnelles sont toutes renseignées',
        ],
        'dependances'         => [],
    ],
    WorkflowEtape::PJ_SAISIE                       => [
        'libelle_intervenant' => "Je fournis les pièces justificatives",
        'libelle_autres'      => "J'accède aux pièces justificatives",
        'route'               => 'piece-jointe/intervenant',
        'desc_non_franchie'   => "Les pièces justificatives n'ont pas été fournies",
        'perimetre'           => Perimetre::ETABLISSEMENT,
        'contraintes'         => [],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => 'Au moins une des pièces justificatives a été fournie',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => 'Toutes les pièces justificatives obligatoires doivent être fournies',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'Toutes les pièces justificatives, mêmes les facultatives, doivent être fournies',
        ],
        'dependances'         => [
            WorkflowEtape::DONNEES_PERSO_SAISIE => [
                'perimetre'  => Perimetre::ETABLISSEMENT,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_DEBUTE,
            ],
        ],
    ],
    WorkflowEtape::DONNEES_PERSO_VALIDATION        => [
        'libelle_intervenant' => "Je visualise la validation de mes données personnelles",
        "libelle_autres"      => "Je visualise la validation des données personnelles",
        "route"               => "intervenant/dossier",
        "desc_non_franchie"   => "Les données personnelles n'ont pas été validées",
        "perimetre"           => Perimetre::ETABLISSEMENT,
        "contraintes"         => [WorkflowEtape::DONNEES_PERSO_SAISIE],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => 'Une partie des données personnelles doivent avoir été validées',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => null,
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'Toutes les données personnelles doivent avoir été validées',
        ],
        'dependances'         => [
            WorkflowEtape::DONNEES_PERSO_SAISIE => [
                'perimetre'  => Perimetre::ETABLISSEMENT,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_DEBUTE,
            ],
        ],
    ],
    WorkflowEtape::PJ_VALIDATION                   => [
        'libelle_intervenant' => "Je visualise la validation des pièces justificatives",
        "libelle_autres"      => "Je visualise la validation des pièces justificatives",
        "route"               => "piece-jointe/intervenant",
        "desc_non_franchie"   => "Les pièces justificatives n'ont pas été validées",
        "perimetre"           => Perimetre::ETABLISSEMENT,
        "contraintes"         => [WorkflowEtape::PJ_SAISIE],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => 'Au moins une des pièces justificatives doit avoir été validée',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => 'Toutes les pièces justificatives obligatoires doivent avoir été validées',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'Toutes les pièces justificatives, y compris facultatives, doivent avoir été validées',
        ],
        'dependances'         => [
            WorkflowEtape::PJ_SAISIE => [
                'perimetre'  => Perimetre::ETABLISSEMENT,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_DEBUTE,
            ],
        ],
    ],
    WorkflowEtape::CANDIDATURE_VALIDATION          => [
        'libelle_intervenant' => "Je visualise la validation de mes candidatures",
        "libelle_autres"      => "J'accède à la validation des candidatures",
        "route"               => "intervenant/candidature",
        "desc_non_franchie"   => "Certaines candidatures attendent une réponse",
        "perimetre"           => Perimetre::COMPOSANTE,
        "contraintes"         => [WorkflowEtape::CANDIDATURE_SAISIE],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => 'Au moins une candidature doit avoir été acceptée ou refusée',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => null,
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'Toutes les candidatures doivent avoir été acceptées ou refusées',
        ],
        'dependances'         => [
            WorkflowEtape::CANDIDATURE_SAISIE => [
                'perimetre'  => Perimetre::COMPOSANTE,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_DEBUTE,
            ],
        ],
    ],
    WorkflowEtape::DONNEES_PERSO_COMPL_SAISIE      => [
        'libelle_intervenant' => "Je saisis mes données personnelles complémentaires",
        "libelle_autres"      => "J'accède aux données personnelles complémentaires",
        "route"               => "intervenant/dossier",
        "desc_non_franchie"   => "Les données personnelles complémentaires n'ont pas été saisies",
        "perimetre"           => Perimetre::ETABLISSEMENT,
        "contraintes"         => [WorkflowEtape::CANDIDATURE_VALIDATION],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => 'Les données personnelles complémentaires sont complétées en partie',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => null,
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'Les données personnelles complémentaires sont toutes renseignées',
        ],
        'dependances'         => [
            WorkflowEtape::CANDIDATURE_VALIDATION => [
                'perimetre'  => Perimetre::ETABLISSEMENT,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT,
            ],
        ],
    ],
    WorkflowEtape::PJ_COMPL_SAISIE                 => [
        'libelle_intervenant' => "Je fournis les pièces justificatives complémentaires",
        "libelle_autres"      => "J'accède aux pièces justificatives complémentaires",
        "route"               => "piece-jointe/intervenant",
        "desc_non_franchie"   => "Les pièces justificatives complémentaires n'ont pas été fournies",
        "perimetre"           => Perimetre::ETABLISSEMENT,
        "contraintes"         => [WorkflowEtape::CANDIDATURE_VALIDATION],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => 'Au moins une pièce justificative complémentaire a été fournie',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => 'Toutes les pièces justificatives complémentaires obligatoires doivent être fournies',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'Toutes les pièces justificatives complémentaires, mêmes les facultatives, doivent être fournies',
        ],
        'dependances'         => [
            WorkflowEtape::CANDIDATURE_VALIDATION => [
                'perimetre'  => Perimetre::ETABLISSEMENT,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT,
            ],
        ],
    ],
    WorkflowEtape::DONNEES_PERSO_COMPL_VALIDATION  => [
        'libelle_intervenant' => "Je visualise la validation de mes données personnelles complémentaires",
        "libelle_autres"      => "Je visualise la validation des données personnelles complémentaires",
        "route"               => "intervenant/dossier",
        "desc_non_franchie"   => "Les données personnelles complémentaires n'ont pas été validées",
        "perimetre"           => Perimetre::ETABLISSEMENT,
        "contraintes"         => [WorkflowEtape::DONNEES_PERSO_COMPL_SAISIE],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => 'Une partie des données personnelles complémentaires doivent avoir été validées',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => null,
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'Toutes les données personnelles complémentaires doivent avoir été validées',
        ],
        'dependances'         => [
            WorkflowEtape::DONNEES_PERSO_COMPL_SAISIE => [
                'perimetre'  => Perimetre::ETABLISSEMENT,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_DEBUTE,
            ],
        ],
    ],
    WorkflowEtape::PJ_COMPL_VALIDATION             => [
        'libelle_intervenant' => "Je visualise la validation des pièces justificatives complémentaires",
        "libelle_autres"      => "Je visualise la validation des pièces justificatives complémentaires",
        "route"               => "piece-jointe/intervenant",
        "desc_non_franchie"   => "Les pièces justificatives complémentaires n'ont pas été validées",
        "perimetre"           => Perimetre::ETABLISSEMENT,
        "contraintes"         => [WorkflowEtape::PJ_COMPL_SAISIE],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => 'Au moins une des pièces justificatives complémentaires doit avoir été validée',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => 'Toutes les pièces justificatives complémentaires obligatoires doivent avoir été validées',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'Toutes les pièces justificatives complémentaires, y compris facultatives, doivent avoir été validées',
        ],
        'dependances'         => [
            WorkflowEtape::PJ_COMPL_SAISIE => [
                'perimetre'  => Perimetre::ETABLISSEMENT,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_DEBUTE,
            ],
        ],
    ],
    WorkflowEtape::ENSEIGNEMENT_SAISIE             => [
        'libelle_intervenant' => "Je saisis mes enseignements prévisionnels",
        "libelle_autres"      => "J'accède aux enseignements prévisionnels",
        "route"               => "intervenant/services-prevus",
        "desc_non_franchie"   => "Aucun enseignement prévisionnel n'a été saisi",
        "perimetre"           => Perimetre::COMPOSANTE,
        "contraintes"         => [],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => 'Au moins 1h d\'enseignement prévisionnel a été saisie',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => null,
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'Le service dû doit avoir été complété',
        ],
        'dependances'         => [
            WorkflowEtape::DONNEES_PERSO_SAISIE => [
                'perimetre'        => Perimetre::ETABLISSEMENT,
                'type_intervenant' => TypeIntervenant::CODE_EXTERIEUR,
                'avancement'       => WorkflowEtapeDependance::AVANCEMENT_DEBUTE,
            ],
        ],
    ],
    WorkflowEtape::REFERENTIEL_SAISIE              => [
        'libelle_intervenant' => "Je saisis mon référentiel prévisionnel",
        "libelle_autres"      => "J'accède au référentiel prévisionnel",
        "route"               => "intervenant/services-prevus",
        "desc_non_franchie"   => "Aucun référentiel prévisionnel n'a été saisi",
        "perimetre"           => Perimetre::COMPOSANTE,
        "contraintes"         => [],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => 'Au moins 1h de référentiel prévisionnel a été saisie',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => null,
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'Le service dû doit avoir été complété',
        ],
        'dependances'         => [
            WorkflowEtape::DONNEES_PERSO_SAISIE => [
                'perimetre'        => Perimetre::ETABLISSEMENT,
                'type_intervenant' => TypeIntervenant::CODE_EXTERIEUR,
                'avancement'       => WorkflowEtapeDependance::AVANCEMENT_DEBUTE,
            ],
        ],
    ],
    WorkflowEtape::MISSION_SAISIE                  => [
        'libelle_intervenant' => "Je visualise mes missions",
        "libelle_autres"      => "J'accède aux missions",
        "route"               => "intervenant/missions",
        "desc_non_franchie"   => "Aucune mission attribuée",
        "perimetre"           => Perimetre::COMPOSANTE,
        "contraintes"         => [WorkflowEtape::CANDIDATURE_VALIDATION],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => 'Au moins une mission doit avoir été saisie',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => null,
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => null,
        ],
        'dependances'         => [
            WorkflowEtape::CANDIDATURE_VALIDATION => [
                'perimetre'  => Perimetre::COMPOSANTE,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT,
            ],
        ],
    ],
    WorkflowEtape::ENSEIGNEMENT_VALIDATION         => [
        'libelle_intervenant' => "Je visualise la validation de mes enseignements prévisionnels",
        "libelle_autres"      => "Je visualise la validation des enseignements prévisionnels",
        "route"               => "intervenant/validation/enseignement/prevu",
        "desc_non_franchie"   => "Les enseignements prévisionnels n'ont pas été validés",
        "perimetre"           => Perimetre::COMPOSANTE,
        "contraintes"         => [WorkflowEtape::ENSEIGNEMENT_SAISIE],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => 'Une partie des heures d\'enseignements prévisionnels doit avoir été validée',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => null,
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'La totalité des heures d\'enseignements prévisionnels doit avoir été validée',
        ],
        'dependances'         => [
            WorkflowEtape::ENSEIGNEMENT_SAISIE => [
                'perimetre'  => Perimetre::COMPOSANTE,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_DEBUTE,
            ],
        ],
    ],
    WorkflowEtape::REFERENTIEL_VALIDATION          => [
        'libelle_intervenant' => "Je visualise la validation de mon référentiel prévisionnel",
        "libelle_autres"      => "Je visualise la validation du référentiel prévisionnel",
        "route"               => "intervenant/validation/referentiel/prevu",
        "desc_non_franchie"   => "Le référentiel prévisionnel n'a pas été validé",
        "perimetre"           => Perimetre::COMPOSANTE,
        "contraintes"         => [WorkflowEtape::REFERENTIEL_SAISIE],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => 'Une partie des heures de référentiel prévisionnel doit avoir été validée',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => null,
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'La totalité des heures de référentiel prévisionnel doit avoir été validée',
        ],
        'dependances'         => [
            WorkflowEtape::REFERENTIEL_SAISIE => [
                'perimetre'  => Perimetre::COMPOSANTE,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_DEBUTE,
            ],
        ],
    ],
    WorkflowEtape::MISSION_VALIDATION              => [
        'libelle_intervenant' => "Je visualise la validation de mes missions",
        "libelle_autres"      => "Je valide les missions saisies",
        "route"               => "intervenant/missions",
        "desc_non_franchie"   => "Certaines missions n'ont pas été validées",
        "perimetre"           => Perimetre::COMPOSANTE,
        "contraintes"         => [WorkflowEtape::MISSION_SAISIE],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => 'Au moins une des missions doit avoir été validée',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => 'La totalité des missions doit avoir été validée',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'La totalité des missions doit avoir été validée, y compris les heures prévisionnelles ajoutées',
        ],
        'dependances'         => [
            WorkflowEtape::MISSION_SAISIE => [
                'perimetre'  => Perimetre::COMPOSANTE,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_DEBUTE,
            ],
        ],
    ],
    WorkflowEtape::CONSEIL_RESTREINT               => [
        'libelle_intervenant' => "Je visualise l'agrément 'Conseil restreint'",
        "libelle_autres"      => "Je visualise l'agrément 'Conseil restreint'",
        "route"               => "intervenant/agrement/conseil-restreint",
        "desc_non_franchie"   => "L'agrément du Conseil restreint n'a pas été saisi",
        "perimetre"           => Perimetre::COMPOSANTE, // par défaut, adaptable selon paramétrage...
        "contraintes"         => [],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => null,
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => 'L\'agrément en conseil restreint doit avoir été donné pour une composante au moins',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'L\'agrément en conseil restreint doit avoir été donné pour toutes les composantes concernées',
        ],
        'dependances'         => [
            WorkflowEtape::PJ_SAISIE                => [
                'perimetre'  => Perimetre::ETABLISSEMENT,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_DEBUTE,
            ],
            WorkflowEtape::PJ_VALIDATION            => [
                'perimetre'  => Perimetre::ETABLISSEMENT,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_DEBUTE,
            ],
            WorkflowEtape::DONNEES_PERSO_VALIDATION => [
                'perimetre'  => Perimetre::ETABLISSEMENT,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT,
            ],
            WorkflowEtape::ENSEIGNEMENT_VALIDATION  => [
                'perimetre'  => Perimetre::COMPOSANTE,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_DEBUTE,
            ],
        ],
    ],
    WorkflowEtape::CONSEIL_ACADEMIQUE              => [
        'libelle_intervenant' => "Je visualise l'agrément 'Conseil académique'",
        "libelle_autres"      => "Je visualise l'agrément 'Conseil académique'",
        "route"               => "intervenant/agrement/conseil-academique",
        "desc_non_franchie"   => "L'agrément du Conseil académique n'a pas été saisi",
        "perimetre"           => Perimetre::ETABLISSEMENT, // par défaut, adaptable selon paramétrage...
        "contraintes"         => [WorkflowEtape::CONSEIL_RESTREINT],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => null,
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => 'L\'agrément en conseil académique doit avoir été donné pour une composante au moins',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'L\'agrément en conseil académique doit avoir été donné pour toutes les composantes concernées',
        ],
        'dependances'         => [
            WorkflowEtape::DONNEES_PERSO_VALIDATION => [
                'perimetre'  => Perimetre::ETABLISSEMENT,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT,
            ],
        ],
    ],
    WorkflowEtape::CONTRAT                         => [
        'libelle_intervenant' => "Je visualise mes contrat/avenants",
        "libelle_autres"      => "Je visualise le contrat et les avenants",
        "route"               => "intervenant/contrat",
        "desc_non_franchie"   => "Le contrat n'a pas été établi",
        "perimetre"           => Perimetre::COMPOSANTE,
        "contraintes"         => [WorkflowEtape::DONNEES_PERSO_VALIDATION, WorkflowEtape::DONNEES_PERSO_COMPL_VALIDATION],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => 'Au moins un projet de contrat doit avoir été créé',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => 'Au moins un contrat ou avenant nécessaire doit avoir été finalisé',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'Tous les contrats ou avenants y compris complémentaires doivent être finalisés',
        ],
        'dependances'         => [
            WorkflowEtape::DONNEES_PERSO_VALIDATION       => [
                'perimetre'  => Perimetre::ETABLISSEMENT,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT,
            ],
            WorkflowEtape::DONNEES_PERSO_COMPL_VALIDATION => [
                'perimetre'  => Perimetre::ETABLISSEMENT,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT,
            ],
            WorkflowEtape::CONSEIL_RESTREINT              => [
                'perimetre'  => Perimetre::COMPOSANTE,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT,
            ],
            WorkflowEtape::CONSEIL_ACADEMIQUE             => [
                'perimetre'  => Perimetre::ETABLISSEMENT,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT,
            ],
        ],
    ],
    WorkflowEtape::EXPORT_RH                       => [
        'libelle_intervenant' => "J'exporte vers le logiciel RH",
        "libelle_autres"      => "J'exporte vers le logiciel RH",
        "route"               => "intervenant/exporter",
        "desc_non_franchie"   => "L'export vers le logiciel RH n'a pas été fait",
        "perimetre"           => Perimetre::ETABLISSEMENT,
        "contraintes"         => [WorkflowEtape::DONNEES_PERSO_VALIDATION, WorkflowEtape::DONNEES_PERSO_COMPL_VALIDATION],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => null,
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => null,
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'L\'export RH doit avoir été fait',
        ],
        'dependances'         => [
            WorkflowEtape::DONNEES_PERSO_VALIDATION       => [
                'perimetre'  => Perimetre::ETABLISSEMENT,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT,
            ],
            WorkflowEtape::DONNEES_PERSO_COMPL_VALIDATION => [
                'perimetre'  => Perimetre::ETABLISSEMENT,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT,
            ],
            WorkflowEtape::CONTRAT                        => [
                'perimetre'  => Perimetre::ETABLISSEMENT,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT,
            ],
        ],
    ],
    WorkflowEtape::ENSEIGNEMENT_SAISIE_REALISE     => [
        'libelle_intervenant' => "Je saisis mes enseignements réalisés",
        "libelle_autres"      => "J'accède aux enseignements réalisés",
        "route"               => "intervenant/services-realises",
        "desc_non_franchie"   => "Aucun enseignement réalisé n'a été saisi",
        "perimetre"           => Perimetre::COMPOSANTE,
        "contraintes"         => [WorkflowEtape::CONTRAT],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => 'Au moins 1h d\'enseignement réalisé a été saisie',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => null,
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'Le service dû doit avoir été complété',
        ],
        'dependances'         => [
            WorkflowEtape::CONTRAT => [
                'type_intervenant' => TypeIntervenant::CODE_EXTERIEUR,
                'perimetre'        => Perimetre::COMPOSANTE,
                'avancement'       => WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT,
            ],
        ],
    ],
    WorkflowEtape::REFERENTIEL_SAISIE_REALISE      => [
        'libelle_intervenant' => "Je saisis mon référentiel réalisé",
        "libelle_autres"      => "J'accède au référentiel réalisé",
        "route"               => "intervenant/services-realises",
        "desc_non_franchie"   => "Aucun enseignement réalisé n'a été saisi",
        "perimetre"           => Perimetre::COMPOSANTE,
        "contraintes"         => [WorkflowEtape::CONTRAT],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => 'Au moins 1h de référentiel réalisé a été saisie',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => null,
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'Le service dû doit avoir été complété',
        ],
        'dependances'         => [],
    ],
    WorkflowEtape::MISSION_SAISIE_REALISE          => [
        'libelle_intervenant' => "Je renseigne mon suivi de mission",
        "libelle_autres"      => "J'accède au suivi de mission",
        "route"               => "intervenant/missions-suivi",
        "desc_non_franchie"   => "Aucune heure de mission réalisée n'a été renseignée",
        "perimetre"           => Perimetre::COMPOSANTE,
        "contraintes"         => [WorkflowEtape::CONTRAT],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => 'Au moins 1h de suivi de mission doit avoir été renseignée',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => null,
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'Le nombre d\'heures de suivi saisi doit être au moins équivalent au nombre d\'heures prévues',
        ],
        'dependances'         => [
            WorkflowEtape::CONTRAT => [
                'perimetre'  => Perimetre::COMPOSANTE,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT,
            ],
        ],
    ],
    WorkflowEtape::CLOTURE_REALISE                 => [
        'libelle_intervenant' => "Je visualise la clôture de la saisie de mes services réalisés",
        "libelle_autres"      => "Je visualise la clôture de la saisie des services réalisés",
        "route"               => "intervenant/services-realises",
        "desc_non_franchie"   => "La clôture de saisie des services réalisés n'a pas été effectuée",
        "perimetre"           => Perimetre::ETABLISSEMENT,
        "contraintes"         => [WorkflowEtape::ENSEIGNEMENT_SAISIE_REALISE, WorkflowEtape::REFERENTIEL_SAISIE_REALISE, WorkflowEtape::MISSION_SAISIE_REALISE],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => null,
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => null,
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'La clôture de saisie de service doit avoir été faite',
        ],
        'dependances'         => [
            WorkflowEtape::ENSEIGNEMENT_SAISIE_REALISE => [
                'type_intervenant' => TypeIntervenant::CODE_PERMANENT,
                'perimetre'        => Perimetre::ETABLISSEMENT,
                'avancement'       => WorkflowEtapeDependance::AVANCEMENT_DEBUTE,
            ],
        ],
    ],
    WorkflowEtape::ENSEIGNEMENT_VALIDATION_REALISE => [
        'libelle_intervenant' => "Je visualise la validation de mes enseignements réalisés",
        "libelle_autres"      => "Je visualise la validation des enseignements réalisés",
        "route"               => "intervenant/validation/enseignement/realise",
        "desc_non_franchie"   => "Le service réalisé n'a été intégralement validé",
        "perimetre"           => Perimetre::COMPOSANTE,
        "contraintes"         => [WorkflowEtape::ENSEIGNEMENT_SAISIE_REALISE],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => 'Une partie des heures d\'enseignements réalisés doit avoir été validée',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => null,
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'La totalité des heures d\'enseignements réalisés doit avoir été validée',
        ],
        'dependances'         => [
            WorkflowEtape::ENSEIGNEMENT_SAISIE_REALISE => [
                'perimetre'  => Perimetre::COMPOSANTE,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_DEBUTE,
            ],
        ],
    ],
    WorkflowEtape::REFERENTIEL_VALIDATION_REALISE  => [
        'libelle_intervenant' => "Je visualise la validation de mon référentiel réalisé",
        "libelle_autres"      => "Je visualise la validation du référentiel réalisé",
        "route"               => "intervenant/validation/referentiel/realise",
        "desc_non_franchie"   => "Le référentiel réalisé n'a pas été intégralement validé",
        "perimetre"           => Perimetre::COMPOSANTE,
        "contraintes"         => [WorkflowEtape::REFERENTIEL_SAISIE_REALISE],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => 'Une partie des heures de référentiel réalisé doit avoir été validée',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => null,
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'La totalité des heures de référentiel réalisé doit avoir été validée',
        ],
        'dependances'         => [
            WorkflowEtape::REFERENTIEL_SAISIE_REALISE => [
                'perimetre'  => Perimetre::COMPOSANTE,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_DEBUTE,
            ],
        ],
    ],
    WorkflowEtape::MISSION_VALIDATION_REALISE      => [
        'libelle_intervenant' => "Je visualise la validation de mon suivi de mission",
        "libelle_autres"      => "J'accède à la validation du suivi de mission",
        "route"               => "intervenant/missions-suivi",
        "desc_non_franchie"   => "Des heures de mission réalisées n'ont pas été validées",
        "perimetre"           => Perimetre::COMPOSANTE,
        "contraintes"         => [WorkflowEtape::MISSION_SAISIE_REALISE],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => 'Au moins une intervention réalisée doit avoir été validée',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => null,
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'La totalité des interventions réalisées doit avoir été validée',
        ],
        'dependances'         => [
            WorkflowEtape::MISSION_SAISIE_REALISE => [
                'perimetre'  => Perimetre::COMPOSANTE,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_DEBUTE,
            ],
        ],
    ],
    WorkflowEtape::MISSION_PRIME                   => [
        'libelle_intervenant' => "Je visualise mes indemnités de fin de contrat",
        "libelle_autres"      => "Je visualise mes indemnités de fin de contrat",
        "route"               => "intervenant/prime-mission",
        "desc_non_franchie"   => "Aucune indemnité de fin de contrat à gérer",
        "perimetre"           => Perimetre::ETABLISSEMENT,
        "contraintes"         => [WorkflowEtape::MISSION_VALIDATION_REALISE],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => 'Au moins une indemnité requise doit avoir été traitée',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => null,
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'La totalité des indemnités requises doivent avoir été traitées',
        ],
        'dependances'         => [
            WorkflowEtape::MISSION_VALIDATION_REALISE => [
                'perimetre'  => Perimetre::ETABLISSEMENT,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_DEBUTE,
            ],
        ],
    ],
    WorkflowEtape::DEMANDE_MEP                     => [
        'libelle_intervenant' => "Je visualise les demandes de mise en paiement me concernant",
        "libelle_autres"      => "J'accède aux demandes de mise en paiement",
        "route"               => "intervenant/mise-en-paiement/demande",
        "route_intervenant"   => "intervenant/mise-en-paiement/visualisation",
        "desc_non_franchie"   => "Aucune demande de mise en paiement n'a été faite",
        "desc_sans_objectif"  => "Le nombre d'heures de service réalisées ET validées n'est pas suffisant pour déclencher le paiement d'heures complémentaires.",
        "perimetre"           => Perimetre::COMPOSANTE,
        "contraintes"         => [WorkflowEtape::CLOTURE_REALISE, WorkflowEtape::ENSEIGNEMENT_VALIDATION_REALISE, WorkflowEtape::REFERENTIEL_VALIDATION_REALISE, WorkflowEtape::MISSION_VALIDATION_REALISE],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => 'Au moins une demande de mise en paiement doit avoir été faite',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => null,
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'La totalité des heures réalisées validées doit avoir fait l\'objet d\'une demande de MEP',
        ],
        'dependances'         => [
            WorkflowEtape::CLOTURE_REALISE                 => [
                'type_intervenant' => TypeIntervenant::CODE_PERMANENT,
                'perimetre'        => Perimetre::ETABLISSEMENT,
                'avancement'       => WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT,
            ],
            WorkflowEtape::ENSEIGNEMENT_VALIDATION_REALISE => [
                'type_intervenant' => TypeIntervenant::CODE_PERMANENT,
                'perimetre'        => Perimetre::COMPOSANTE,
                'avancement'       => WorkflowEtapeDependance::AVANCEMENT_DEBUTE,
            ],
            WorkflowEtape::REFERENTIEL_VALIDATION_REALISE  => [
                'type_intervenant' => TypeIntervenant::CODE_PERMANENT,
                'perimetre'        => Perimetre::COMPOSANTE,
                'avancement'       => WorkflowEtapeDependance::AVANCEMENT_DEBUTE,
            ],
        ],
    ],
    WorkflowEtape::SAISIE_MEP                      => [
        'libelle_intervenant' => "Je visualise les mises en paiement me concernant",
        "libelle_autres"      => "J'accède aux mises en paiement",
        "route"               => "intervenant/mise-en-paiement/visualisation",
        "desc_non_franchie"   => "Aucune mise en paiement n'a été faite",
        "perimetre"           => Perimetre::COMPOSANTE,
        "contraintes"         => [WorkflowEtape::DEMANDE_MEP],
        'avancements'         => [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => 'Quelques demandes de mise ne paiement doivent avoir été traitées',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => null,
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'La totalité des demandes de mise en paiement doit avoir été traitée',
        ],
        'dependances'         => [
            WorkflowEtape::DEMANDE_MEP => [
                'perimetre'  => Perimetre::ETABLISSEMENT,
                'avancement' => WorkflowEtapeDependance::AVANCEMENT_DEBUTE,
            ],
        ],
    ],
];