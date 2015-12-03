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

    const ODF_CENTRES_COUT_EDITION          = 'odf-centres-cout-edition';
    const ODF_ELEMENT_EDITION               = 'odf-element-edition';
    const ODF_ETAPE_EDITION                 = 'odf-etape-edition';
    const ODF_EXPORT_CSV                    = 'odf-export-csv';
    const ODF_MODULATEURS_EDITION           = 'odf-modulateurs-edition';
    const ODF_TAUX_MIXITE_EDITION           = 'odf-taux-mixite-edition';
    const ODF_VISUALISATION                 = 'odf-visualisation';
    const ODF_ELEMENT_VISUALISATION         = 'odf-element-visualisation';
    const ODF_ETAPE_VISUALISATION           = 'odf-etape-visualisation';
    const DISCIPLINE_EDITION                = 'discipline-edition';
    const DISCIPLINE_GESTION                = 'discipline-gestion';
    const DISCIPLINE_VISUALISATION          = 'discipline-visualisation';
    const INTERVENANT_RECHERCHE             = 'intervenant-recherche';
    const INTERVENANT_FICHE                 = 'intervenant-fiche';
    const INTERVENANT_CALCUL_HETD           = 'intervenant-calcul-hetd';
    const INTERVENANT_EDITION               = 'intervenant-edition';
    const MODIF_SERVICE_DU_VISUALISATION    = 'modif-service-du-visualisation';
    const MODIF_SERVICE_DU_EDITION          = 'modif-service-du-edition';
    const MODIF_SERVICE_DU_ASSOCIATION      = 'modif-service-du-association';
    const ENSEIGNEMENT_VISUALISATION        = 'enseignement-visualisation';
    const ENSEIGNEMENT_EXPORT_CSV           = 'enseignement-export-csv';
    const MISE_EN_PAIEMENT_VISUALISATION    = 'mise-en-paiement-visualisation';
    const MISE_EN_PAIEMENT_DEMANDE          = 'mise-en-paiement-demande';
    const MISE_EN_PAIEMENT_EXPORT_CSV       = 'mise-en-paiement-export-csv';
    const MISE_EN_PAIEMENT_EXPORT_PDF       = 'mise-en-paiement-export-pdf';
    const MISE_EN_PAIEMENT_MISE_EN_PAIEMENT = 'mise-en-paiement-mise-en-paiement';
    const MISE_EN_PAIEMENT_EXPORT_PAIE      = 'mise-en-paiement-export-paie';
    const DROIT_AFFECTATION_VISUALISATION   = 'droit-affectation-visualisation';
    const DROIT_AFFECTATION_EDITION         = 'droit-affectation-edition';
    const IMPORT_ECARTS                     = 'import-ecarts';
    const IMPORT_MAJ                        = 'import-maj';
    const IMPORT_TBL                        = 'import-tbl';
    const IMPORT_VUES_PROCEDURES            = 'import-vues-procedures';

}