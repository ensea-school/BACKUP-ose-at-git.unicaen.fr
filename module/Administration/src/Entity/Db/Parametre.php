<?php

namespace Administration\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class Parametre implements HistoriqueAwareInterface
{
    /* Années */
    const ANNEE                     = 'annee';
    const ANNEE_IMPORT              = 'annee_import';
    const ANNEE_MINIMALE_IMPORT_ODF = 'annee_minimale_import_odf';


    /* IDS */
    const ETABLISSEMENT               = 'etablissement';
    const STRUCTURE_UNIV              = 'structure_univ';
    const OSEUSER                     = 'oseuser';
    const FORMULE                     = 'formule';
    const DOMAINE_FONCTIONNEL_ENS_EXT = 'domaine_fonctionnel_ens_ext';
    const SCENARIO_CHARGES_SERVICES   = 'scenario_charges_services';


    /* Etats de sortie */
    const ES_EXTRACTION_PAIE       = 'es_extraction_paie';
    const ES_EXTRACTION_INDEMNITES = 'es_extraction_indemnites';
    const ES_SERVICES_PDF          = 'es_services_pdf';
    const ES_SERVICES_PDF2         = 'es_services_pdf_secondaire';
    const ES_SERVICES_CSV          = 'es_services_csv';
    const ES_SERVICES_CSV2         = 'es_services_csv_secondaire';
    const ES_ETAT_PAIEMENT         = 'es_etat_paiement';
    const ES_EXPORT_FORMATION      = 'es_export_formation';


    /* Divers */
    const REPORT_SERVICE       = 'report_service';
    const CONSTATATION_REALISE = 'constatation_realise';


    /* Paiement */
    const CENTRES_COUTS_PAYE             = 'centres_couts_paye';
    const REGLE_PAIEMENT_ANNEE_CIVILE    = 'regle_paiement_annee_civile';
    const REGLE_REPARTITION_ANNEE_CIVILE = 'regle_repartition_annee_civile';
    const POURC_AA_REFERENTIEL           = 'pourc_aa_referentiel';
    const POURC_S1_POUR_ANNEE_CIVILE     = 'pourc_s1_pour_annee_civile';
    const HORAIRE_NOCTURNE               = 'horaire_nocturne';
    const TAUX_REMU                      = 'taux-remu';
    const TAUX_CONGES_PAYES              = 'taux_conges_payes';
    const DISTINCTION_FI_FA_FC           = 'distinction_fi_fa_fc';


    /* Documentations */
    const DOC_INTERVENANT_VACATAIRES = 'doc-intervenant-vacataires';
    const DOC_INTERVENANT_PERMANENTS = 'doc-intervenant-permanents';
    const DOC_INTERVENANT_ETUDIANTS  = 'doc-intervenant-etudiants';


    /* Disciplines */
    const DISCIPLINE_CODES_CORRESP_1_LIBELLE = 'discipline_codes_corresp_1_libelle';
    const DISCIPLINE_CODES_CORRESP_2_LIBELLE = 'discipline_codes_corresp_2_libelle';
    const DISCIPLINE_CODES_CORRESP_3_LIBELLE = 'discipline_codes_corresp_3_libelle';
    const DISCIPLINE_CODES_CORRESP_4_LIBELLE = 'discipline_codes_corresp_4_libelle';


    /* Statuts */
    const STATUT_INTERVENANT_CODES_CORRESP_1_LIBELLE = 'statut_intervenant_codes_corresp_1_libelle';
    const STATUT_INTERVENANT_CODES_CORRESP_2_LIBELLE = 'statut_intervenant_codes_corresp_2_libelle';
    const STATUT_INTERVENANT_CODES_CORRESP_3_LIBELLE = 'statut_intervenant_codes_corresp_3_libelle';
    const STATUT_INTERVENANT_CODES_CORRESP_4_LIBELLE = 'statut_intervenant_codes_corresp_4_libelle';


    /* Contrat */
    const CONTRAT_REGLE_FRANCHISSEMENT = 'contrat_regle_franchissement';
    // VALEURS CONTRAT_REGLE_FRANCHISSEMENT
    const CONTRAT_FRANCHI_DATE_RETOUR = 'date-retour';
    const CONTRAT_FRANCHI_VALIDATION  = 'validation';

    const CONTRAT_MODELE_MAIL       = 'contrat_modele_mail';
    const CONTRAT_MODELE_MAIL_OBJET = 'contrat_modele_mail_objet';
    const CONTRAT_MAIL_EXPEDITEUR   = 'contrat_mail_expediteur';
    const AVENANT                   = 'avenant';
    // VALEURS AVENANT
    const AVENANT_AUTORISE  = 'avenant_autorise';
    const AVENANT_DESACTIVE = 'avenant_desactive';

    const CONTRAT_DIRECT = 'contrat_direct';
    // VALEURS AVENANT
    // CONTRAT_DIRECT = 'contrat_direct';
    const CONTRAT_DIRECT_DESACTIVE = 'desactive';

    const CONTRAT_MIS = 'contrat_mis';
    // VALEURS CONTRAT_MIS
    const CONTRAT_MIS_COMPOSANTE = 'contrat_mis_composante';
    const CONTRAT_MIS_GLOBAL     = 'contrat_mis_globale';
    const CONTRAT_MIS_MISSION    = 'contrat_mis_mission';

    const CONTRAT_ENS = 'contrat_ens';
    // VALEURS CONTRAT_ENS
    const CONTRAT_ENS_COMPOSANTE = 'contrat_ens_composante';
    const CONTRAT_ENS_GLOBAL     = 'contrat_ens_global';

    const CONTRAT_DATE = 'contrat_date';


    /* Candidature mission */
    const CANDIDATURE_MODELE_ACCEPTATION_MAIL       = 'candidature_modele_acceptation_mail';
    const CANDIDATURE_MODELE_ACCEPTATION_MAIL_OBJET = 'candidature_modele_acceptation_mail_objet';
    const CANDIDATURE_MODELE_REFUS_MAIL             = 'candidature_modele_refus_mail';
    const CANDIDATURE_MODELE_REFUS_MAIL_OBJET       = 'candidature_modele_refus_mail_objet';
    const CANDIDATURE_MAIL_EXPEDITEUR               = 'candidature_mail_expediteur';


    /* Signature électronique */
    const SIGNATURE_ELECTRONIQUE_PARAPHEUR = 'signature_electronique_parapheur';


    /* Messages informatifs */
    const PAGE_CONTACT                  = 'page_contact';
    const PAGE_ACCUEIL                  = 'page_accueil';
    const CONNEXION_NON_AUTORISE        = 'connexion_non_autorise';
    const CONNEXION_SANS_ROLE_NI_STATUT = 'connexion_sans_role_ni_statut';


    /* Indicateur */
    const INDICATEUR_EMAIL_EXPEDITEUR = 'indicateur_email_expediteur';


    use HistoriqueAwareTrait;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $nom;

    /**
     * @var string
     */
    protected $valeur;

    /**
     * @var integer
     */
    protected $id;



    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }



    /**
     * Set description
     *
     * @param string $description
     *
     * @return Parametre
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }



    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }



    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Parametre
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }



    /**
     * Get valeur
     *
     * @return string
     */
    public function getValeur()
    {
        return $this->valeur;
    }



    /**
     * Set valeur
     *
     * @param string $valeur
     *
     * @return Parametre
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;

        return $this;
    }



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

}
