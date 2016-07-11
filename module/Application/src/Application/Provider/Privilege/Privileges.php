<?php

namespace Application\Provider\Privilege;

/**
 * Description of Privileges
 *
 * Liste des privilèges utilisables dans votre application
 *
 * @author UnicaenCode
 */
class Privileges extends \UnicaenAuth\Provider\Privilege\Privileges
{

    const ODF_VISUALISATION                         = 'odf-visualisation';
    const ODF_EXPORT_CSV                            = 'odf-export-csv';
    const ODF_ELEMENT_VISUALISATION                 = 'odf-element-visualisation';
    const ODF_ELEMENT_EDITION                       = 'odf-element-edition';
    const ODF_ETAPE_VISUALISATION                   = 'odf-etape-visualisation';
    const ODF_ETAPE_EDITION                         = 'odf-etape-edition';
    const ODF_CENTRES_COUT_EDITION                  = 'odf-centres-cout-edition';
    const ODF_MODULATEURS_EDITION                   = 'odf-modulateurs-edition';
    const ODF_TAUX_MIXITE_EDITION                   = 'odf-taux-mixite-edition';
    const DISCIPLINE_VISUALISATION                  = 'discipline-visualisation';
    const DISCIPLINE_EDITION                        = 'discipline-edition';
    const DISCIPLINE_GESTION                        = 'discipline-gestion';
    const INTERVENANT_RECHERCHE                     = 'intervenant-recherche';
    const INTERVENANT_FICHE                         = 'intervenant-fiche';
    const INTERVENANT_CALCUL_HETD                   = 'intervenant-calcul-hetd';
    const INTERVENANT_EDITION                       = 'intervenant-edition';
    const MODIF_SERVICE_DU_ASSOCIATION              = 'modif-service-du-association';
    const MODIF_SERVICE_DU_VISUALISATION            = 'modif-service-du-visualisation';
    const MODIF_SERVICE_DU_EDITION                  = 'modif-service-du-edition';
    const DOSSIER_VISUALISATION                     = 'dossier-visualisation';
    const DOSSIER_EDITION                           = 'dossier-edition';
    const DOSSIER_VALIDATION                        = 'dossier-validation';
    const DOSSIER_SUPPRESSION                       = 'dossier-suppression';
    const DOSSIER_DEVALIDATION                      = 'dossier-devalidation';
    const DOSSIER_DIFFERENCES                       = 'dossier-differences';
    const DOSSIER_PURGER_DIFFERENCES                = 'dossier-purger-differences';
    const PIECE_JUSTIFICATIVE_VISUALISATION         = 'piece-justificative-visualisation';
    const PIECE_JUSTIFICATIVE_EDITION               = 'piece-justificative-edition';
    const PIECE_JUSTIFICATIVE_VALIDATION            = 'piece-justificative-validation';
    const PIECE_JUSTIFICATIVE_DEVALIDATION          = 'piece-justificative-devalidation';
    const PIECE_JUSTIFICATIVE_GESTION_EDITION       = 'piece-justificative-gestion-edition';
    const PIECE_JUSTIFICATIVE_GESTION_VISUALISATION = 'piece-justificative-gestion-visualisation';
    const PIECE_JUSTIFICATIVE_TELECHARGEMENT        = 'piece-justificative-telechargement';
    const ENSEIGNEMENT_VISUALISATION                = 'enseignement-visualisation';
    const ENSEIGNEMENT_EDITION                      = 'enseignement-edition';
    const ENSEIGNEMENT_EXTERIEUR                    = 'enseignement-exterieur';
    const ENSEIGNEMENT_VALIDATION                   = 'enseignement-validation';
    const ENSEIGNEMENT_DEVALIDATION                 = 'enseignement-devalidation';
    const ENSEIGNEMENT_EXPORT_CSV                   = 'enseignement-export-csv';
    const MOTIF_NON_PAIEMENT_VISUALISATION          = 'motif-non-paiement-visualisation';
    const MOTIF_NON_PAIEMENT_EDITION                = 'motif-non-paiement-edition';
    const REFERENTIEL_VISUALISATION                 = 'referentiel-visualisation';
    const REFERENTIEL_EDITION                       = 'referentiel-edition';
    const REFERENTIEL_VALIDATION                    = 'referentiel-validation';
    const REFERENTIEL_DEVALIDATION                  = 'referentiel-devalidation';
    const AGREMENT_CONSEIL_ACADEMIQUE_VISUALISATION = 'agrement-conseil-academique-visualisation';
    const AGREMENT_CONSEIL_ACADEMIQUE_EDITION       = 'agrement-conseil-academique-edition';
    const AGREMENT_CONSEIL_RESTREINT_VISUALISATION  = 'agrement-conseil-restreint-visualisation';
    const AGREMENT_CONSEIL_RESTREINT_EDITION        = 'agrement-conseil-restreint-edition';
    const AGREMENT_CONSEIL_ACADEMIQUE_SUPPRESSION   = 'agrement-conseil-academique-suppression';
    const AGREMENT_CONSEIL_RESTREINT_SUPPRESSION    = 'agrement-conseil-restreint-suppression';
    const CONTRAT_VISUALISATION                     = 'contrat-visualisation';
    const CONTRAT_CREATION                          = 'contrat-creation';
    const CONTRAT_SUPPRESSION                       = 'contrat-suppression';
    const CONTRAT_VALIDATION                        = 'contrat-validation';
    const CONTRAT_DEVALIDATION                      = 'contrat-devalidation';
    const CONTRAT_DEPOT_RETOUR_SIGNE                = 'contrat-depot-retour-signe';
    const CONTRAT_SAISIE_DATE_RETOUR_SIGNE          = 'contrat-saisie-date-retour-signe';
    const MISE_EN_PAIEMENT_VISUALISATION            = 'mise-en-paiement-visualisation';
    const MISE_EN_PAIEMENT_DEMANDE                  = 'mise-en-paiement-demande';
    const MISE_EN_PAIEMENT_EXPORT_CSV               = 'mise-en-paiement-export-csv';
    const MISE_EN_PAIEMENT_EXPORT_PDF               = 'mise-en-paiement-export-pdf';
    const MISE_EN_PAIEMENT_MISE_EN_PAIEMENT         = 'mise-en-paiement-mise-en-paiement';
    const MISE_EN_PAIEMENT_EXPORT_PAIE              = 'mise-en-paiement-export-paie';
    const INDICATEUR_VISUALISATION                  = 'indicateur-visualisation';
    const INDICATEUR_ABONNEMENT                     = 'indicateur-abonnement';
    const INDICATEUR_ABONNEMENTS_EDITION            = 'indicateur-abonnements-edition';
    const INDICATEUR_ABONNEMENTS_VISUALISATION      = 'indicateur-abonnements-visualisation';
    const INDICATEUR_ENVOI_MAIL_INTERVENANTS        = 'indicateur-envoi-mail-intervenants';
    const DROIT_AFFECTATION_VISUALISATION           = 'droit-affectation-visualisation';
    const DROIT_AFFECTATION_EDITION                 = 'droit-affectation-edition';
    const IMPORT_ECARTS                             = 'import-ecarts';
    const IMPORT_MAJ                                = 'import-maj';
    const IMPORT_TBL                                = 'import-tbl';
    const IMPORT_VUES_PROCEDURES                    = 'import-vues-procedures';
    const PILOTAGE_ECARTS_ETATS                     = 'pilotage-ecarts-etats';
    const PARAMETRES_GENERAL_EDITION                = 'parametres-general-edition';
    const WORKFLOW_DEPENDANCES_EDITION              = 'workflow-dependances-edition';
    const CLOTURE_CLOTURE                           = 'cloture-cloture';
    const PARAMETRES_GENERAL_VISUALISATION          = 'parametres-general-visualisation';
    const CLOTURE_REOUVERTURE                       = 'cloture-reouverture';
    const WORKFLOW_DEPENDANCES_VISUALISATION        = 'workflow-dependances-visualisation';
    const BUDGET_VISUALISATION                      = 'budget-visualisation';
    const PILOTAGE_VISUALISATION                    = 'pilotage-visualisation';
    const CLOTURE_EDITION_SERVICES                  = 'cloture-edition-services';
    const BUDGET_EDITION_ENGAGEMENT_COMPOSANTE      = 'budget-edition-engagement-composante';
    const PARAMETRES_CAMPAGNES_SAISIE_EDITION       = 'parametres-campagnes-saisie-edition';
    const BUDGET_EDITION_ENGAGEMENT_ETABLISSEMENT   = 'budget-edition-engagement-etablissement';
    const BUDGET_EXPORT                             = 'budget-export';
    const PARAMETRES_CAMPAGNES_SAISIE_VISUALISATION = 'parametres-campagnes-saisie-visualisation';

}