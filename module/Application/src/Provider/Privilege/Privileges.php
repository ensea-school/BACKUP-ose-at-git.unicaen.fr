<?php

namespace Application\Provider\Privilege;

/**
 * Description of Privileges
 *
 * Liste des privilèges utilisables dans votre application
 *
 * @author UnicaenCode
 */
class Privileges extends \UnicaenPrivilege\Provider\Privilege\Privileges
{

    const AGREMENT_CONSEIL_ACADEMIQUE_EDITION                 = 'agrement-conseil-academique-edition';
    const AGREMENT_CONSEIL_ACADEMIQUE_SUPPRESSION             = 'agrement-conseil-academique-suppression';
    const AGREMENT_CONSEIL_ACADEMIQUE_VISUALISATION           = 'agrement-conseil-academique-visualisation';
    const AGREMENT_CONSEIL_RESTREINT_EDITION                  = 'agrement-conseil-restreint-edition';
    const AGREMENT_CONSEIL_RESTREINT_SUPPRESSION              = 'agrement-conseil-restreint-suppression';
    const AGREMENT_CONSEIL_RESTREINT_VISUALISATION            = 'agrement-conseil-restreint-visualisation';
    const AGREMENT_EXPORT_CSV                                 = 'agrement-export-csv';
    const BUDGET_CC_ACTIVITE_EDITION                          = 'budget-cc-activite-edition';
    const BUDGET_CC_ACTIVITE_VISUALISATION                    = 'budget-cc-activite-visualisation';
    const BUDGET_EDITION_ENGAGEMENT_COMPOSANTE                = 'budget-edition-engagement-composante';
    const BUDGET_EDITION_ENGAGEMENT_ETABLISSEMENT             = 'budget-edition-engagement-etablissement';
    const BUDGET_EXPORT                                       = 'budget-export';
    const BUDGET_TYPES_RESSOURCES_EDITION                     = 'budget-types-ressources-edition';
    const BUDGET_TYPES_RESSOURCES_VISUALISATION               = 'budget-types-ressources-visualisation';
    const BUDGET_TYPE_DOTATION_EDITION                        = 'budget-type-dotation-edition';
    const BUDGET_TYPE_DOTATION_VISUALISATION                  = 'budget-type-dotation-visualisation';
    const BUDGET_VISUALISATION                                = 'budget-visualisation';
    const CENTRES_COUTS_ADMINISTRATION_EDITION                = 'centres-couts-administration-edition';
    const CENTRES_COUTS_ADMINISTRATION_RECONDUCTION           = 'centres-couts-administration-reconduction';
    const CENTRES_COUTS_ADMINISTRATION_VISUALISATION          = 'centres-couts-administration-visualisation';
    const CHARGENS_DEPASSEMENT_CSV                            = 'chargens-depassement-csv';
    const CHARGENS_EXPORT_CSV                                 = 'chargens-export-csv';
    const CHARGENS_FORMATION_ACTIF_EDITION                    = 'chargens-formation-actif-edition';
    const CHARGENS_FORMATION_ASSIDUITE_EDITION                = 'chargens-formation-assiduite-edition';
    const CHARGENS_FORMATION_CHOIX_EDITION                    = 'chargens-formation-choix-edition';
    const CHARGENS_FORMATION_EFFECTIFS_EDITION                = 'chargens-formation-effectifs-edition';
    const CHARGENS_FORMATION_POIDS_EDITION                    = 'chargens-formation-poids-edition';
    const CHARGENS_FORMATION_SEUILS_EDITION                   = 'chargens-formation-seuils-edition';
    const CHARGENS_FORMATION_VISUALISATION                    = 'chargens-formation-visualisation';
    const CHARGENS_SCENARIO_COMPOSANTE_EDITION                = 'chargens-scenario-composante-edition';
    const CHARGENS_SCENARIO_DUPLICATION                       = 'chargens-scenario-duplication';
    const CHARGENS_SCENARIO_ETABLISSEMENT_EDITION             = 'chargens-scenario-etablissement-edition';
    const CHARGENS_SCENARIO_VISUALISATION                     = 'chargens-scenario-visualisation';
    const CHARGENS_SEUIL_COMPOSANTE_EDITION                   = 'chargens-seuil-composante-edition';
    const CHARGENS_SEUIL_COMPOSANTE_VISUALISATION             = 'chargens-seuil-composante-visualisation';
    const CHARGENS_SEUIL_ETABLISSEMENT_EDITION                = 'chargens-seuil-etablissement-edition';
    const CHARGENS_SEUIL_ETABLISSEMENT_VISUALISATION          = 'chargens-seuil-etablissement-visualisation';
    const CHARGENS_VISUALISATION                              = 'chargens-visualisation';
    const CLOTURE_CLOTURE                                     = 'cloture-cloture';
    const CLOTURE_EDITION_SERVICES                            = 'cloture-edition-services';
    const CLOTURE_EDITION_SERVICES_AVEC_MEP                   = 'cloture-edition-services-avec-mep';
    const CLOTURE_REOUVERTURE                                 = 'cloture-reouverture';
    const CONTRAT_CONTRAT_GENERATION                          = 'contrat-contrat-generation';
    const CONTRAT_CREATION                                    = 'contrat-creation';
    const CONTRAT_DEPOT_RETOUR_SIGNE                          = 'contrat-depot-retour-signe';
    const CONTRAT_DEVALIDATION                                = 'contrat-devalidation';
    const CONTRAT_ENVOI_EMAIL                                 = 'contrat-envoi-email';
    const CONTRAT_PROJET_GENERATION                           = 'contrat-projet-generation';
    const CONTRAT_SAISIE_DATE_RETOUR_SIGNE                    = 'contrat-saisie-date-retour-signe';
    const CONTRAT_SUPPRESSION                                 = 'contrat-suppression';
    const CONTRAT_VALIDATION                                  = 'contrat-validation';
    const CONTRAT_VISUALISATION                               = 'contrat-visualisation';
    const DISCIPLINE_EDITION                                  = 'discipline-edition';
    const DISCIPLINE_GESTION                                  = 'discipline-gestion';
    const DISCIPLINE_VISUALISATION                            = 'discipline-visualisation';
    const DOMAINES_FONCTIONNELS_ADMINISTRATION_EDITION        = 'domaines-fonctionnels-administration-edition';
    const DOMAINES_FONCTIONNELS_ADMINISTRATION_VISUALISATION  = 'domaines-fonctionnels-administration-visualisation';
    const DOSSIER_ADRESSE_EDITION                             = 'dossier-adresse-edition';
    const DOSSIER_ADRESSE_VISUALISATION                       = 'dossier-adresse-visualisation';
    const DOSSIER_BANQUE_EDITION                              = 'dossier-banque-edition';
    const DOSSIER_BANQUE_VISUALISATION                        = 'dossier-banque-visualisation';
    const DOSSIER_CHAMP_AUTRE_1_EDITION                       = 'dossier-champ-autre-1-edition';
    const DOSSIER_CHAMP_AUTRE_1_VISUALISATION                 = 'dossier-champ-autre-1-visualisation';
    const DOSSIER_CHAMP_AUTRE_2_EDITION                       = 'dossier-champ-autre-2-edition';
    const DOSSIER_CHAMP_AUTRE_2_VISUALISATION                 = 'dossier-champ-autre-2-visualisation';
    const DOSSIER_CHAMP_AUTRE_3_EDITION                       = 'dossier-champ-autre-3-edition';
    const DOSSIER_CHAMP_AUTRE_3_VISUALISATION                 = 'dossier-champ-autre-3-visualisation';
    const DOSSIER_CHAMP_AUTRE_4_EDITION                       = 'dossier-champ-autre-4-edition';
    const DOSSIER_CHAMP_AUTRE_4_VISUALISATION                 = 'dossier-champ-autre-4-visualisation';
    const DOSSIER_CHAMP_AUTRE_5_EDITION                       = 'dossier-champ-autre-5-edition';
    const DOSSIER_CHAMP_AUTRE_5_VISUALISATION                 = 'dossier-champ-autre-5-visualisation';
    const DOSSIER_CONTACT_EDITION                             = 'dossier-contact-edition';
    const DOSSIER_CONTACT_VISUALISATION                       = 'dossier-contact-visualisation';
    const DOSSIER_DEVALIDATION                                = 'dossier-devalidation';
    const DOSSIER_DIFFERENCES                                 = 'dossier-differences';
    const DOSSIER_EDITION                                     = 'dossier-edition';
    const DOSSIER_EMPLOYEUR_EDITION                           = 'dossier-employeur-edition';
    const DOSSIER_EMPLOYEUR_VISUALISATION                     = 'dossier-employeur-visualisation';
    const DOSSIER_IDENTITE_EDITION                            = 'dossier-identite-edition';
    const DOSSIER_IDENTITE_VISUALISATION                      = 'dossier-identite-visualisation';
    const DOSSIER_INSEE_EDITION                               = 'dossier-insee-edition';
    const DOSSIER_INSEE_VISUALISATION                         = 'dossier-insee-visualisation';
    const DOSSIER_PURGER_DIFFERENCES                          = 'dossier-purger-differences';
    const DOSSIER_SUPPRESSION                                 = 'dossier-suppression';
    const DOSSIER_VALIDATION                                  = 'dossier-validation';
    const DOSSIER_VISUALISATION                               = 'dossier-visualisation';
    const DROIT_AFFECTATION_EDITION                           = 'droit-affectation-edition';
    const DROIT_AFFECTATION_VISUALISATION                     = 'droit-affectation-visualisation';
    const DROIT_PRIVILEGE_EDITION                             = 'droit-privilege-edition';
    const DROIT_PRIVILEGE_VISUALISATION                       = 'droit-privilege-visualisation';
    const DROIT_ROLE_EDITION                                  = 'droit-role-edition';
    const DROIT_ROLE_VISUALISATION                            = 'droit-role-visualisation';
    const ENSEIGNEMENT_DEVALIDATION                           = 'enseignement-devalidation';
    const ENSEIGNEMENT_EDITION_MASSE                          = 'enseignement-edition-masse';
    const ENSEIGNEMENT_EXPORT_CSV                             = 'enseignement-export-csv';
    const ENSEIGNEMENT_EXPORT_PDF                             = 'enseignement-export-pdf';
    const ENSEIGNEMENT_EXTERIEUR                              = 'enseignement-exterieur';
    const ENSEIGNEMENT_IMPORT_INTERVENANT_PREVISIONNEL_AGENDA = 'enseignement-import-intervenant-previsionnel-agenda';
    const ENSEIGNEMENT_IMPORT_INTERVENANT_REALISE_AGENDA      = 'enseignement-import-intervenant-realise-agenda';
    const ENSEIGNEMENT_PREVU_AUTOVALIDATION                   = 'enseignement-prevu-autovalidation';
    const ENSEIGNEMENT_PREVU_EDITION                          = 'enseignement-prevu-edition';
    const ENSEIGNEMENT_PREVU_VALIDATION                       = 'enseignement-prevu-validation';
    const ENSEIGNEMENT_PREVU_VISUALISATION                    = 'enseignement-prevu-visualisation';
    const ENSEIGNEMENT_REALISE_AUTOVALIDATION                 = 'enseignement-realise-autovalidation';
    const ENSEIGNEMENT_REALISE_EDITION                        = 'enseignement-realise-edition';
    const ENSEIGNEMENT_REALISE_VALIDATION                     = 'enseignement-realise-validation';
    const ENSEIGNEMENT_REALISE_VISUALISATION                  = 'enseignement-realise-visualisation';
    const ETAT_SORTIE_ADMINISTRATION_EDITION                  = 'etat-sortie-administration-edition';
    const ETAT_SORTIE_ADMINISTRATION_VISUALISATION            = 'etat-sortie-administration-visualisation';
    const FORMULE_TESTS                                       = 'formule-tests';
    const IMPORT_ECARTS                                       = 'import-ecarts';
    const IMPORT_MAJ                                          = 'import-maj';
    const IMPORT_SOURCES_EDITION                              = 'import-sources-edition';
    const IMPORT_SOURCES_VISUALISATION                        = 'import-sources-visualisation';
    const IMPORT_TABLES_EDITION                               = 'import-tables-edition';
    const IMPORT_TABLES_VISUALISATION                         = 'import-tables-visualisation';
    const IMPORT_TBL                                          = 'import-tbl';
    const IMPORT_VUES_PROCEDURES                              = 'import-vues-procedures';
    const INDICATEUR_ABONNEMENT                               = 'indicateur-abonnement';
    const INDICATEUR_ABONNEMENTS_EDITION                      = 'indicateur-abonnements-edition';
    const INDICATEUR_ABONNEMENTS_VISUALISATION                = 'indicateur-abonnements-visualisation';
    const INDICATEUR_ENVOI_MAIL_INTERVENANTS                  = 'indicateur-envoi-mail-intervenants';
    const INDICATEUR_VISUALISATION                            = 'indicateur-visualisation';
    const INTERVENANT_ADRESSE                                 = 'intervenant-adresse';
    const INTERVENANT_AJOUT_STATUT                            = 'intervenant-ajout-statut';
    const INTERVENANT_AUTRES_EDITION                          = 'intervenant-autres-edition';
    const INTERVENANT_AUTRES_VISUALISATION                    = 'intervenant-autres-visualisation';
    const INTERVENANT_CALCUL_HETD                             = 'intervenant-calcul-hetd';
    const INTERVENANT_CREATION                                = 'intervenant-creation';
    const INTERVENANT_EDITION                                 = 'intervenant-edition';
    const INTERVENANT_EDITION_AVANCEE                         = 'intervenant-edition-avancee';
    const INTERVENANT_EXPORTER                                = 'intervenant-exporter';
    const INTERVENANT_FICHE                                   = 'intervenant-fiche';
    const INTERVENANT_LIEN_SYSTEME_INFORMATION                = 'intervenant-lien-systeme-information';
    const INTERVENANT_NOTE_ADMINISTRATION                     = 'intervenant-note-administration';
    const INTERVENANT_NOTE_AJOUT                              = 'intervenant-note-ajout';
    const INTERVENANT_NOTE_EMAIL                              = 'intervenant-note-email';
    const INTERVENANT_NOTE_VISUALISATION                      = 'intervenant-note-visualisation';
    const INTERVENANT_RECHERCHE                               = 'intervenant-recherche';
    const INTERVENANT_STATUT_EDITION                          = 'intervenant-statut-edition';
    const INTERVENANT_STATUT_VISUALISATION                    = 'intervenant-statut-visualisation';
    const INTERVENANT_SUPPRESSION                             = 'intervenant-suppression';
    const INTERVENANT_VISUALISATION_HISTORISES                = 'intervenant-visualisation-historises';
    const JOUR_FERIE_EDITION                                  = 'jour-ferie-edition';
    const JOUR_FERIE_VISUALISATION                            = 'jour-ferie-visualisation';
    const MAIL_MAIL_AFFICHER                                  = 'mail-mail_afficher';
    const MAIL_MAIL_INDEX                                     = 'mail-mail_index';
    const MAIL_MAIL_SUPPRIMER                                 = 'mail-mail_supprimer';
    const MAIL_MAIL_TEST                                      = 'mail-mail_test';
    const MISE_EN_PAIEMENT_DEMANDE                            = 'mise-en-paiement-demande';
    const MISE_EN_PAIEMENT_EDITION                            = 'mise-en-paiement-edition';
    const MISE_EN_PAIEMENT_EXPORT_CSV                         = 'mise-en-paiement-export-csv';
    const MISE_EN_PAIEMENT_EXPORT_PAIE                        = 'mise-en-paiement-export-paie';
    const MISE_EN_PAIEMENT_EXPORT_PDF                         = 'mise-en-paiement-export-pdf';
    const MISE_EN_PAIEMENT_EXPORT_PDF_ETAT                    = 'mise-en-paiement-export-pdf-etat';
    const MISE_EN_PAIEMENT_MISE_EN_PAIEMENT                   = 'mise-en-paiement-mise-en-paiement';
    const MISE_EN_PAIEMENT_VISUALISATION_GESTION              = 'mise-en-paiement-visualisation-gestion';
    const MISE_EN_PAIEMENT_VISUALISATION_INTERVENANT          = 'mise-en-paiement-visualisation-intervenant';
    const MISSION_AUTOVALIDATION                              = 'mission-autovalidation';
    const MISSION_AUTOVALIDATION_REALISE                      = 'mission-autovalidation-realise';
    const MISSION_CANDIDATURE_VALIDER                         = 'mission-candidature-valider';
    const MISSION_CANDIDATURE_VISUALISATION                   = 'mission-candidature-visualisation';
    const MISSION_DEVALIDATION                                = 'mission-devalidation';
    const MISSION_DEVALIDATION_REALISE                        = 'mission-devalidation-realise';
    const MISSION_EDITION                                     = 'mission-edition';
    const MISSION_EDITION_REALISE                             = 'mission-edition-realise';
    const MISSION_EDITION_TYPE                                = 'mission-edition-type';
//    const MISSION_EDITION_CENTRE_COUT_TYPE                     = 'mission-edition-centre-cout-type';
    const MISSION_OFFRE_EMPLOI_AJOUTER                         = 'mission-offre-emploi-ajouter';
    const MISSION_OFFRE_EMPLOI_MODIFIER                        = 'mission-offre-emploi-modifier';
    const MISSION_OFFRE_EMPLOI_POSTULER                        = 'mission-offre-emploi-postuler';
    const MISSION_OFFRE_EMPLOI_SUPPRESSION                     = 'mission-offre-emploi-suppression';
    const MISSION_OFFRE_EMPLOI_VALIDER                         = 'mission-offre-emploi-valider';
    const MISSION_OFFRE_EMPLOI_VISUALISATION                   = 'mission-offre-emploi-visualisation';
    const MISSION_PRIME_VALIDER                                = 'mission-prime-valider';
    const MISSION_PRIME_VISUALISATION                          = 'mission-prime-visualisation';
    const MISSION_SUPPRESSION_TYPE                             = 'mission-suppression-type';
    const MISSION_VALIDATION                                   = 'mission-validation';
    const MISSION_VALIDATION_REALISE                           = 'mission-validation-realise';
    const MISSION_VISUALISATION                                = 'mission-visualisation';
    const MISSION_VISUALISATION_REALISE                        = 'mission-visualisation-realise';
    const MISSION_VISUALISATION_TYPE                           = 'mission-visualisation-type';
    const MODIF_SERVICE_DU_ASSOCIATION                         = 'modif-service-du-association';
    const MODIF_SERVICE_DU_EDITION                             = 'modif-service-du-edition';
    const MODIF_SERVICE_DU_EXPORT_CSV                          = 'modif-service-du-export-csv';
    const MODIF_SERVICE_DU_GESTION_EDITION                     = 'modif-service-du-gestion-edition';
    const MODIF_SERVICE_DU_GESTION_VISUALISATION               = 'modif-service-du-gestion-visualisation';
    const MODIF_SERVICE_DU_VISUALISATION                       = 'modif-service-du-visualisation';
    const MODULATEUR_EDITION                                   = 'modulateur-edition';
    const MODULATEUR_VISUALISATION                             = 'modulateur-visualisation';
    const MOTIFS_MODIFICATION_SERVICE_DU_EDITION               = 'motifs-modification-service-du-edition';
    const MOTIFS_MODIFICATION_SERVICE_DU_VISUALISATION         = 'motifs-modification-service-du-visualisation';
    const MOTIF_NON_PAIEMENT_ADMINISTRATION_EDITION            = 'motif-non-paiement-administration-edition';
    const MOTIF_NON_PAIEMENT_ADMINISTRATION_VISUALISATION      = 'motif-non-paiement-administration-visualisation';
    const MOTIF_NON_PAIEMENT_EDITION                           = 'motif-non-paiement-edition';
    const MOTIF_NON_PAIEMENT_VISUALISATION                     = 'motif-non-paiement-visualisation';
    const NOMENCLATURE_RH_CORPS_EDITION                        = 'nomenclature-rh-corps-edition';
    const NOMENCLATURE_RH_CORPS_VISUALISATION                  = 'nomenclature-rh-corps-visualisation';
    const NOMENCLATURE_RH_GRADES_EDITION                       = 'nomenclature-rh-grades-edition';
    const NOMENCLATURE_RH_GRADES_VISUALISATION                 = 'nomenclature-rh-grades-visualisation';
    const ODF_CENTRES_COUT_EDITION                             = 'odf-centres-cout-edition';
    const ODF_ELEMENT_EDITION                                  = 'odf-element-edition';
    const ODF_ELEMENT_SYNCHRONISATION                          = 'odf-element-synchronisation';
    const ODF_ELEMENT_VH_EDITION                               = 'odf-element-vh-edition';
    const ODF_ELEMENT_VH_VISUALISATION                         = 'odf-element-vh-visualisation';
    const ODF_ELEMENT_VISUALISATION                            = 'odf-element-visualisation';
    const ODF_ETAPE_EDITION                                    = 'odf-etape-edition';
    const ODF_ETAPE_VISUALISATION                              = 'odf-etape-visualisation';
    const ODF_EXPORT_CSV                                       = 'odf-export-csv';
    const ODF_GRANDS_TYPES_DIPLOME_EDITION                     = 'odf-grands-types-diplome-edition';
    const ODF_GRANDS_TYPES_DIPLOME_VISUALISATION               = 'odf-grands-types-diplome-visualisation';
    const ODF_MODULATEURS_EDITION                              = 'odf-modulateurs-edition';
    const ODF_RECONDUCTION_CENTRE_COUT                         = 'odf-reconduction-centre-cout';
    const ODF_RECONDUCTION_MODULATEUR                          = 'odf-reconduction-modulateur';
    const ODF_RECONDUCTION_OFFRE                               = 'odf-reconduction-offre';
    const ODF_TAUX_MIXITE_EDITION                              = 'odf-taux-mixite-edition';
    const ODF_TYPES_DIPLOME_EDITION                            = 'odf-types-diplome-edition';
    const ODF_TYPES_DIPLOME_VISUALISATION                      = 'odf-types-diplome-visualisation';
    const ODF_TYPE_FORMATION_EDITION                           = 'odf-type-formation-edition';
    const ODF_TYPE_FORMATION_VISUALISATION                     = 'odf-type-formation-visualisation';
    const ODF_VISUALISATION                                    = 'odf-visualisation';
    const PARAMETRES_ANNEES_EDITION                            = 'parametres-annees-edition';
    const PARAMETRES_ANNEES_VISUALISATION                      = 'parametres-annees-visualisation';
    const PARAMETRES_CAMPAGNES_SAISIE_EDITION                  = 'parametres-campagnes-saisie-edition';
    const PARAMETRES_CAMPAGNES_SAISIE_VISUALISATION            = 'parametres-campagnes-saisie-visualisation';
    const PARAMETRES_DEPARTEMENT_EDITION                       = 'parametres-Departement-edition';
    const PARAMETRES_DEPARTEMENT_VISUALISATION                 = 'parametres-Departement-visualisation';
    const PARAMETRES_ETABLISSEMENT_EDITION                     = 'parametres-etablissement-edition';
    const PARAMETRES_ETABLISSEMENT_VISUALISATION               = 'parametres-etablissement-visualisation';
    const PARAMETRES_GENERAL_EDITION                           = 'parametres-general-edition';
    const PARAMETRES_GENERAL_VISUALISATION                     = 'parametres-general-visualisation';
    const PARAMETRES_PAYS_EDITION                              = 'parametres-Pays-edition';
    const PARAMETRES_PAYS_VISUALISATION                        = 'parametres-Pays-visualisation';
    const PARAMETRES_PERIODES_EDITION                          = 'parametres-periodes-edition';
    const PARAMETRES_PERIODES_VISUALISATION                    = 'parametres-periodes-visualisation';
    const PARAMETRES_REGLES_STRUCTURE_VALIDATION_EDITION       = 'parametres-regles-structure-validation-edition';
    const PARAMETRES_REGLES_STRUCTURE_VALIDATION_VISUALISATION = 'parametres-regles-structure-validation-visualisation';
    const PIECE_JUSTIFICATIVE_ARCHIVAGE                        = 'piece-justificative-archivage';
    const PIECE_JUSTIFICATIVE_DEVALIDATION                     = 'piece-justificative-devalidation';
    const PIECE_JUSTIFICATIVE_EDITION                          = 'piece-justificative-edition';
    const PIECE_JUSTIFICATIVE_GESTION_EDITION                  = 'piece-justificative-gestion-edition';
    const PIECE_JUSTIFICATIVE_GESTION_VISUALISATION            = 'piece-justificative-gestion-visualisation';
    const PIECE_JUSTIFICATIVE_REFUS_PIECE                      = 'piece-justificative-refus-piece';
    const PIECE_JUSTIFICATIVE_TELECHARGEMENT                   = 'piece-justificative-telechargement';
    const PIECE_JUSTIFICATIVE_VALIDATION                       = 'piece-justificative-validation';
    const PIECE_JUSTIFICATIVE_VISUALISATION                    = 'piece-justificative-visualisation';
    const PILOTAGE_ECARTS_ETATS                                = 'pilotage-ecarts-etats';
    const PILOTAGE_VISUALISATION                               = 'pilotage-visualisation';
    const PLAFONDS_CONFIG_MISSION                              = 'plafonds-config-mission';
    const PLAFONDS_CONFIG_REFERENTIEL                          = 'plafonds-config-referentiel';
    const PLAFONDS_CONFIG_STATUT                               = 'plafonds-config-statut';
    const PLAFONDS_CONFIG_STRUCTURE                            = 'plafonds-config-structure';
    const PLAFONDS_DEROGATIONS_EDITION                         = 'plafonds-derogations-edition';
    const PLAFONDS_DEROGATIONS_VISUALISATION                   = 'plafonds-derogations-visualisation';
    const PLAFONDS_EDITION                                     = 'plafonds-edition';
    const PLAFONDS_VISUALISATION                               = 'plafonds-visualisation';
    const REFERENTIEL_ADMIN_EDITION                            = 'referentiel-admin-edition';
    const REFERENTIEL_ADMIN_VISUALISATION                      = 'referentiel-admin-visualisation';
    const REFERENTIEL_COMMUN_EMPLOYEUR_EDITION                 = 'referentiel-commun-employeur-edition';
    const REFERENTIEL_COMMUN_EMPLOYEUR_VISUALISATION           = 'referentiel-commun-employeur-visualisation';
    const REFERENTIEL_COMMUN_VOIRIE_EDITION                    = 'referentiel-commun-voirie-edition';
    const REFERENTIEL_COMMUN_VOIRIE_VISUALISATION              = 'referentiel-commun-voirie-visualisation';
    const REFERENTIEL_DEVALIDATION                             = 'referentiel-devalidation';
    const REFERENTIEL_PREVU_AUTOVALIDATION                     = 'referentiel-prevu-autovalidation';
    const REFERENTIEL_PREVU_EDITION                            = 'referentiel-prevu-edition';
    const REFERENTIEL_PREVU_VALIDATION                         = 'referentiel-prevu-validation';
    const REFERENTIEL_PREVU_VISUALISATION                      = 'referentiel-prevu-visualisation';
    const REFERENTIEL_REALISE_AUTOVALIDATION                   = 'referentiel-realise-autovalidation';
    const REFERENTIEL_REALISE_EDITION                          = 'referentiel-realise-edition';
    const REFERENTIEL_REALISE_VALIDATION                       = 'referentiel-realise-validation';
    const REFERENTIEL_REALISE_VISUALISATION                    = 'referentiel-realise-visualisation';
    const REFERENTIEL_SAISIE_TOUTES_COMPOSANTES                = 'referentiel-saisie-toutes-composantes';
    const STRUCTURES_ADMINISTRATION_EDITION                    = 'structures-administration-edition';
    const STRUCTURES_ADMINISTRATION_VISUALISATION              = 'structures-administration-visualisation';
    const TAG_ADMINISTRATION_EDITION                           = 'tag-administration-edition';
    const TAG_ADMINISTRATION_VISUALISATION                     = 'tag-administration-visualisation';
    const TAG_EDITION                                          = 'tag-edition';
    const TAG_VISUALISATION                                    = 'tag-visualisation';
    const TAUX_EDITION                                         = 'taux-edition';
    const TAUX_SUPPRESSION                                     = 'taux-suppression';
    const TAUX_VISUALISATION                                   = 'taux-visualisation';
    const TYPE_INTERVENTION_EDITION                            = 'type-intervention-edition';
    const TYPE_INTERVENTION_VISUALISATION                      = 'type-intervention-visualisation';
    const TYPE_RESSOURCE_EDITION                               = 'type-ressource-edition';
    const TYPE_RESSOURCE_VISUALISATION                         = 'type-ressource-visualisation';
    const WORKFLOW_DEPENDANCES_EDITION                         = 'workflow-dependances-edition';
    const WORKFLOW_DEPENDANCES_VISUALISATION                   = 'workflow-dependances-visualisation';

}