<?php

namespace Application\Provider\Privilege;

/**
 * Description of Privileges
 *
 * Liste des privilèges utilisables dans votre application
 *
 * @author UnicaenCode
 */
class Privileges extends \UnicaenAuth\Provider\Privilege\Privileges {

    const AGREMENT_CONSEIL_ACADEMIQUE_EDITION        = 'agrement-conseil-academique-edition';
    const AGREMENT_CONSEIL_ACADEMIQUE_SUPPRESSION    = 'agrement-conseil-academique-suppression';
    const AGREMENT_CONSEIL_ACADEMIQUE_VISUALISATION  = 'agrement-conseil-academique-visualisation';
    const AGREMENT_CONSEIL_RESTREINT_EDITION         = 'agrement-conseil-restreint-edition';
    const AGREMENT_CONSEIL_RESTREINT_SUPPRESSION     = 'agrement-conseil-restreint-suppression';
    const AGREMENT_CONSEIL_RESTREINT_VISUALISATION   = 'agrement-conseil-restreint-visualisation';
    const BUDGET_CC_ACTIVITE_EDITION                 = 'budget-cc-activite-edition';
    const BUDGET_CC_ACTIVITE_VISUALISATION           = 'budget-cc-activite-visualisation';
    const BUDGET_EDITION_ENGAGEMENT_COMPOSANTE       = 'budget-edition-engagement-composante';
    const BUDGET_EDITION_ENGAGEMENT_ETABLISSEMENT    = 'budget-edition-engagement-etablissement';
    const BUDGET_EXPORT                              = 'budget-export';
    const BUDGET_TYPE_DOTATION_EDITION               = 'budget-type-dotation-edition';
    const BUDGET_TYPE_DOTATION_VISUALISATION         = 'budget-type-dotation-visualisation';
    const BUDGET_VISUALISATION                       = 'budget-visualisation';
    const CHARGENS_EXPORT_CSV                        = 'chargens-export-csv';
    const CHARGENS_FORMATION_ACTIF_EDITION           = 'chargens-formation-actif-edition';
    const CHARGENS_FORMATION_ASSIDUITE_EDITION       = 'chargens-formation-assiduite-edition';
    const CHARGENS_FORMATION_CHOIX_EDITION           = 'chargens-formation-choix-edition';
    const CHARGENS_FORMATION_EFFECTIFS_EDITION       = 'chargens-formation-effectifs-edition';
    const CHARGENS_FORMATION_POIDS_EDITION           = 'chargens-formation-poids-edition';
    const CHARGENS_FORMATION_SEUILS_EDITION          = 'chargens-formation-seuils-edition';
    const CHARGENS_FORMATION_VISUALISATION           = 'chargens-formation-visualisation';
    const CHARGENS_SCENARIO_COMPOSANTE_EDITION       = 'chargens-scenario-composante-edition';
    const CHARGENS_SCENARIO_DUPLICATION              = 'chargens-scenario-duplication';
    const CHARGENS_SCENARIO_ETABLISSEMENT_EDITION    = 'chargens-scenario-etablissement-edition';
    const CHARGENS_SCENARIO_VISUALISATION            = 'chargens-scenario-visualisation';
    const CHARGENS_SEUIL_COMPOSANTE_EDITION          = 'chargens-seuil-composante-edition';
    const CHARGENS_SEUIL_COMPOSANTE_VISUALISATION    = 'chargens-seuil-composante-visualisation';
    const CHARGENS_SEUIL_ETABLISSEMENT_EDITION       = 'chargens-seuil-etablissement-edition';
    const CHARGENS_SEUIL_ETABLISSEMENT_VISUALISATION = 'chargens-seuil-etablissement-visualisation';
    const CHARGENS_VISUALISATION                     = 'chargens-visualisation';
    const CLOTURE_CLOTURE                            = 'cloture-cloture';
    const CLOTURE_EDITION_SERVICES                   = 'cloture-edition-services';
    const CLOTURE_REOUVERTURE                        = 'cloture-reouverture';
    const CONTRAT_CREATION                           = 'contrat-creation';
    const CONTRAT_DEPOT_RETOUR_SIGNE                 = 'contrat-depot-retour-signe';
    const CONTRAT_DEVALIDATION                       = 'contrat-devalidation';
    const CONTRAT_SAISIE_DATE_RETOUR_SIGNE           = 'contrat-saisie-date-retour-signe';
    const CONTRAT_SUPPRESSION                        = 'contrat-suppression';
    const CONTRAT_VALIDATION                         = 'contrat-validation';
    const CONTRAT_VISUALISATION                      = 'contrat-visualisation';
    const DISCIPLINE_EDITION                         = 'discipline-edition';
    const DISCIPLINE_GESTION                         = 'discipline-gestion';
    const DISCIPLINE_VISUALISATION                   = 'discipline-visualisation';
    const DOSSIER_DEVALIDATION                       = 'dossier-devalidation';
    const DOSSIER_DIFFERENCES                        = 'dossier-differences';
    const DOSSIER_EDITION                            = 'dossier-edition';
    const DOSSIER_PURGER_DIFFERENCES                 = 'dossier-purger-differences';
    const DOSSIER_SUPPRESSION                        = 'dossier-suppression';
    const DOSSIER_VALIDATION                         = 'dossier-validation';
    const DOSSIER_VISUALISATION                      = 'dossier-visualisation';
    const DROIT_AFFECTATION_EDITION                  = 'droit-affectation-edition';
    const DROIT_AFFECTATION_VISUALISATION            = 'droit-affectation-visualisation';
    const ENSEIGNEMENT_DEVALIDATION                  = 'enseignement-devalidation';
    const ENSEIGNEMENT_EDITION                       = 'enseignement-edition';
    const ENSEIGNEMENT_EXPORT_CSV                    = 'enseignement-export-csv';
    const ENSEIGNEMENT_EXPORT_PDF                    = 'enseignement-export-pdf';
    const ENSEIGNEMENT_EXTERIEUR                     = 'enseignement-exterieur';
    const ENSEIGNEMENT_VALIDATION                    = 'enseignement-validation';
    const ENSEIGNEMENT_VISUALISATION                 = 'enseignement-visualisation';
    const IMPORT_ECARTS                              = 'import-ecarts';
    const IMPORT_MAJ                                 = 'import-maj';
    const IMPORT_TBL                                 = 'import-tbl';
    const IMPORT_VUES_PROCEDURES                     = 'import-vues-procedures';
    const INDICATEUR_ABONNEMENT                      = 'indicateur-abonnement';
    const INDICATEUR_ABONNEMENTS_EDITION             = 'indicateur-abonnements-edition';
    const INDICATEUR_ABONNEMENTS_VISUALISATION       = 'indicateur-abonnements-visualisation';
    const INDICATEUR_ENVOI_MAIL_INTERVENANTS         = 'indicateur-envoi-mail-intervenants';
    const INDICATEUR_VISUALISATION                   = 'indicateur-visualisation';
    const INTERVENANT_CALCUL_HETD                    = 'intervenant-calcul-hetd';
    const INTERVENANT_EDITION                        = 'intervenant-edition';
    const INTERVENANT_FICHE                          = 'intervenant-fiche';
    const INTERVENANT_RECHERCHE                      = 'intervenant-recherche';
    const INTERVENANT_SUPPRESSION                    = 'intervenant-suppression';
    const MISE_EN_PAIEMENT_DEMANDE                   = 'mise-en-paiement-demande';
    const MISE_EN_PAIEMENT_EDITION                   = 'mise-en-paiement-edition';
    const MISE_EN_PAIEMENT_EXPORT_CSV                = 'mise-en-paiement-export-csv';
    const MISE_EN_PAIEMENT_EXPORT_PAIE               = 'mise-en-paiement-export-paie';
    const MISE_EN_PAIEMENT_EXPORT_PDF                = 'mise-en-paiement-export-pdf';
    const MISE_EN_PAIEMENT_MISE_EN_PAIEMENT          = 'mise-en-paiement-mise-en-paiement';
    const MISE_EN_PAIEMENT_VISUALISATION_GESTION     = 'mise-en-paiement-visualisation-gestion';
    const MISE_EN_PAIEMENT_VISUALISATION_INTERVENANT = 'mise-en-paiement-visualisation-intervenant';
    const MODIF_SERVICE_DU_ASSOCIATION               = 'modif-service-du-association';
    const MODIF_SERVICE_DU_EDITION                   = 'modif-service-du-edition';
    const MODIF_SERVICE_DU_VISUALISATION             = 'modif-service-du-visualisation';
    const MODULATEUR_EDITION                         = 'modulateur-edition';
    const MODULATEUR_VISUALISATION                   = 'modulateur-visualisation';
    const MOTIF_NON_PAIEMENT_EDITION                 = 'motif-non-paiement-edition';
    const MOTIF_NON_PAIEMENT_VISUALISATION           = 'motif-non-paiement-visualisation';
    const ODF_CENTRES_COUT_EDITION                   = 'odf-centres-cout-edition';
    const ODF_ELEMENT_EDITION                        = 'odf-element-edition';
    const ODF_ELEMENT_VISUALISATION                  = 'odf-element-visualisation';
    const ODF_ETAPE_EDITION                          = 'odf-etape-edition';
    const ODF_ETAPE_VISUALISATION                    = 'odf-etape-visualisation';
    const ODF_EXPORT_CSV                             = 'odf-export-csv';
    const ODF_MODULATEURS_EDITION                    = 'odf-modulateurs-edition';
    const ODF_TAUX_MIXITE_EDITION                    = 'odf-taux-mixite-edition';
    const ODF_VISUALISATION                          = 'odf-visualisation';
    const PARAMETRES_ANNEES_EDITION                  = 'parametres-annees-edition';
    const PARAMETRES_ANNEES_VISUALISATION            = 'parametres-annees-visualisation';
    const PARAMETRES_CAMPAGNES_SAISIE_EDITION        = 'parametres-campagnes-saisie-edition';
    const PARAMETRES_CAMPAGNES_SAISIE_VISUALISATION  = 'parametres-campagnes-saisie-visualisation';
    const PARAMETRES_GENERAL_EDITION                 = 'parametres-general-edition';
    const PARAMETRES_GENERAL_VISUALISATION           = 'parametres-general-visualisation';
    const PIECE_JUSTIFICATIVE_DEVALIDATION           = 'piece-justificative-devalidation';
    const PIECE_JUSTIFICATIVE_EDITION                = 'piece-justificative-edition';
    const PIECE_JUSTIFICATIVE_GESTION_EDITION        = 'piece-justificative-gestion-edition';
    const PIECE_JUSTIFICATIVE_GESTION_VISUALISATION  = 'piece-justificative-gestion-visualisation';
    const PIECE_JUSTIFICATIVE_TELECHARGEMENT         = 'piece-justificative-telechargement';
    const PIECE_JUSTIFICATIVE_VALIDATION             = 'piece-justificative-validation';
    const PIECE_JUSTIFICATIVE_VISUALISATION          = 'piece-justificative-visualisation';
    const PILOTAGE_ECARTS_ETATS                      = 'pilotage-ecarts-etats';
    const PILOTAGE_VISUALISATION                     = 'pilotage-visualisation';
    const REFERENTIEL_ADMIN_EDITION                  = 'referentiel-admin-edition';
    const REFERENTIEL_ADMIN_VISUALISATION            = 'referentiel-admin-visualisation';
    const REFERENTIEL_DEVALIDATION                   = 'referentiel-devalidation';
    const REFERENTIEL_EDITION                        = 'referentiel-edition';
    const REFERENTIEL_VALIDATION                     = 'referentiel-validation';
    const REFERENTIEL_VISUALISATION                  = 'referentiel-visualisation';
    const TYPE_INTERVENTION_EDITION                  = 'type-intervention-edition';
    const TYPE_INTERVENTION_VISUALISATION            = 'type-intervention-visualisation';
    const WORKFLOW_DEPENDANCES_EDITION               = 'workflow-dependances-edition';
    const WORKFLOW_DEPENDANCES_VISUALISATION         = 'workflow-dependances-visualisation';

}
