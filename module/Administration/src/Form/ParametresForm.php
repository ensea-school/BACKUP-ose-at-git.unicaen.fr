<?php

namespace Administration\Form;

use Administration\Entity\Db\Parametre;
use Application\Form\AbstractForm;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Chargens\Service\ScenarioServiceAwareTrait;
use EtatSortie\Service\EtatSortieServiceAwareTrait;
use Formule\Entity\Db\Formule;
use Formule\Service\FormuleServiceAwareTrait;
use Laminas\Form\Element;
use Lieu\Form\Element\Structure;
use Lieu\Service\PaysServiceAwareTrait;
use Lieu\Service\StructureServiceAwareTrait;
use Paiement\Entity\Db\TauxRemu;
use Paiement\Service\DomaineFonctionnelServiceAwareTrait;
use Service\Entity\Db\TypeVolumeHoraire;
use UnicaenApp\Form\Element\SearchAndSelect;
use UnicaenApp\Util;
use UnicaenSignature\Service\SignatureConfigurationServiceAwareTrait;
use Utilisateur\Service\UtilisateurServiceAwareTrait;


/**
 * Description of ParametresForm
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ParametresForm extends AbstractForm
{
    use AnneeServiceAwareTrait;
    use DomaineFonctionnelServiceAwareTrait;
    use UtilisateurServiceAwareTrait;
    use ScenarioServiceAwareTrait;
    use PaysServiceAwareTrait;
    use StructureServiceAwareTrait;
    use EtatSortieServiceAwareTrait;
    use FormuleServiceAwareTrait;
    use SignatureConfigurationServiceAwareTrait;


    public function init()
    {


        $this->setAttribute('action', $this->getCurrentUrl());

        $this->add([
                       'type'       => 'Select',
                       'name'       => 'annee',
                       'options'    => [
                           'value_options' => Util::collectionAsOptions($this->getServiceAnnee()->getList()),
                           'label'         => 'Pour la saisie des services',
                       ],
                       'attributes' => [
                           'class'            => 'selectpicker',
                           'data-live-search' => 'true',
                       ],
                   ]);

        $this->add([
                       'type'       => 'Select',
                       'name'       => 'annee_import',
                       'options'    => [
                           'value_options' => Util::collectionAsOptions($this->getServiceAnnee()->getList()),
                           'label'         => 'Pour l\'import',
                       ],
                       'attributes' => [
                           'class'            => 'selectpicker',
                           'data-live-search' => 'true',
                       ],
                   ]);

        $this->add([
                       'type'       => 'Select',
                       'name'       => 'annee_minimale_import_odf',
                       'options'    => [
                           'value_options' => Util::collectionAsOptions($this->getServiceAnnee()->getList()),
                           'label'         => 'Année minimale d\'import pour l\'ODF',
                       ],
                       'attributes' => [
                           'class'            => 'selectpicker',
                           'data-live-search' => 'true',
                       ],
                   ]);

        $this->add([
                       'type'    => Structure::class,
                       'name'    => 'structure_univ',
                       'options' => [
                           'label' => 'Composante représentant l\'université',
                       ],
                   ]);

        $this->add([
                       'name'    => 'discipline_codes_corresp_1_libelle',
                       'type'    => 'Text',
                       'options' => [
                           'label' => 'Libellé 1 des correspondances',
                       ],
                   ]);

        $this->add([
                       'name'    => 'discipline_codes_corresp_2_libelle',
                       'type'    => 'Text',
                       'options' => [
                           'label' => 'Libellé 2 des correspondances',
                       ],
                   ]);

        $this->add([
                       'name'    => 'discipline_codes_corresp_3_libelle',
                       'type'    => 'Text',
                       'options' => [
                           'label' => 'Libellé 3 des correspondances',
                       ],
                   ]);

        $this->add([
                       'name'    => 'discipline_codes_corresp_4_libelle',
                       'type'    => 'Text',
                       'options' => [
                           'label' => 'Libellé 4 des correspondances',
                       ],
                   ]);

        $this->add([
                       'name'    => 'statut_intervenant_codes_corresp_1_libelle',
                       'type'    => 'Text',
                       'options' => [
                           'label' => 'Libellé 1 des correspondances',
                       ],
                   ]);

        $this->add([
                       'name'    => 'statut_intervenant_codes_corresp_2_libelle',
                       'type'    => 'Text',
                       'options' => [
                           'label' => 'Libellé 2 des correspondances',
                       ],
                   ]);

        $this->add([
                       'name'    => 'statut_intervenant_codes_corresp_3_libelle',
                       'type'    => 'Text',
                       'options' => [
                           'label' => 'Libellé 3 des correspondances',
                       ],
                   ]);

        $this->add([
                       'name'    => 'statut_intervenant_codes_corresp_4_libelle',
                       'type'    => 'Text',
                       'options' => [
                           'label' => 'Libellé 4 des correspondances',
                       ],
                   ]);

        $this->add([
                       'type'       => 'Select',
                       'name'       => 'domaine_fonctionnel_ens_ext',
                       'options'    => [
                           'value_options' => Util::collectionAsOptions($this->getServiceDomaineFonctionnel()->getList($this->getServiceDomaineFonctionnel()->finderByHistorique())),
                       ],
                       'attributes' => [
                           'class'            => 'selectpicker',
                           'data-live-search' => 'true',
                       ],
                   ]);

        $etablissement = new SearchAndSelect('etablissement');
        $etablissement->setRequired(true)
            ->setSelectionRequired(true)
            ->setAutocompleteSource(
                $this->getUrl('etablissement/recherche')
            )
            ->setLabel("Établissement :")
            ->setAttributes(['title' => "Saisissez le libellé (2 lettres au moins)"]);
        $this->add($etablissement);

        $this->add([
                       'type'       => 'Select',
                       'name'       => 'formule',
                       'attributes' => [
                           'class'            => 'selectpicker',
                           'data-live-search' => 'true',
                       ],
                   ]);
        $this->setValueOptions('formule', 'SELECT f FROM ' . Formule::class . ' f WHERE f.active = true ORDER BY f.libelle');

        $this->add([
                       'type'       => 'Select',
                       'name'       => 'regle_paiement_annee_civile',
                       'options'    => [
                           'label'         => 'Répartition année civile antérieure / en cours',
                           'value_options' => [
                               '4-6sur10'      => 'Répartition 4/10 des heures pour l\'année  antérieure, 6/10 pour l\'année en cours',
                               'semestre-date' => 'En fonction du semestre des heures ou de la date des cours',
                           ],
                       ],
                       'attributes' => [
                           'class'     => 'selectpicker',
                           'data-size' => 20,
                       ],
                   ]);

        $this->add([
                       'type'       => 'Select',
                       'name'       => 'regle_repartition_annee_civile',
                       'options'    => [
                           'label'         => 'Répartition des heures AA/AC dans les mises en paiement',
                           'value_options' => [
                               'prorata'      => 'Chaque mise en paiement est répartie selon le prorata AA/AC',
                               'ordre-saisie' => 'Les premières mises en paiement sont considérées en AA, puis ce qui dépasse est en AC',
                           ],
                       ],
                       'attributes' => [
                           'class'     => 'selectpicker',
                           'data-size' => 20,
                       ],
                   ]);

        $this->add([
                       'name'       => 'pourc_s1_pour_annee_civile',
                       'options'    => [
                           'label'  => 'Pour le 1er semestre, % d\'heures sur l\'année antérieure',
                           'suffix' => '%',
                       ],
                       'attributes' => [
                           'class' => 'input-sm',
                           'step'  => 'any',
                           'min'   => 0,
                           'max'   => 1,
                       ],
                       'type'       => 'Text',
                   ]);

        $this->add([
                       'name'       => 'pourc_aa_referentiel',
                       'options'    => [
                           'label'  => 'Pour les heures de référentiel, % d\'heures sur l\'année antérieure',
                           'suffix' => '%',
                       ],
                       'attributes' => [
                           'class' => 'input-sm',
                           'step'  => 'any',
                           'min'   => 0,
                           'max'   => 1,
                       ],
                       'type'       => 'Text',
                   ]);

        $this->add([
                       'type'       => 'Select',
                       'name'       => 'distinction_fi_fa_fc',
                       'options'    => [
                           'label'         => 'Distinction FI/FA/FC des heures à payer',
                           'value_options' => [
                               '1' => 'Oui, distinguer la FI, la FA et la FC pour les heures à payer',
                               '0' => 'Non, toutes les heures d\'enseignement seront traitées sous l label "Enseignement"',
                           ],
                       ],
                       'attributes' => [
                           'class' => 'selectpicker',
                       ],
                   ]);

        $this->add([
                       'name'    => 'doc-intervenant-vacataires',
                       'type'    => 'Text',
                       'options' => [
                           'label' => 'Vacataires',
                       ],
                   ]);

        $this->add([
                       'name'    => 'doc-intervenant-permanents',
                       'type'    => 'Text',
                       'options' => [
                           'label' => 'Permanents',
                       ],
                   ]);

        $this->add([
                       'name'    => 'doc-intervenant-etudiants',
                       'type'    => 'Text',
                       'options' => [
                           'label' => 'Étudiants',
                       ],
                   ]);

        $this->add([
                       'type'       => 'Select',
                       'name'       => 'oseuser',
                       'options'    => [
                           'value_options' => Util::collectionAsOptions($this->getServiceUtilisateur()->getList()),
                       ],
                       'attributes' => [
                           'class'            => 'selectpicker',
                           'data-live-search' => 'true',
                           'data-size'        => 20,
                       ],
                   ]);

        $this->add([
                       'type'       => 'Select',
                       'name'       => 'es_extraction_paie',
                       'options'    => [
                           'value_options' => Util::collectionAsOptions($this->getServiceEtatSortie()->getList()),
                           'label'         => 'État de sortie pour l\'extraction de la paie',
                       ],
                       'attributes' => [
                           'class'            => 'selectpicker',
                           'data-live-search' => 'true',
                       ],
                   ]);

        $this->add([
                       'type'       => 'Select',
                       'name'       => 'es_extraction_indemnites',
                       'options'    => [
                           'value_options' => Util::collectionAsOptions($this->getServiceEtatSortie()->getList()),
                           'label'         => 'État de sortie pour l\'extraction des indémnités de fin de mission',
                       ],
                       'attributes' => [
                           'class'            => 'selectpicker',
                           'data-live-search' => 'true',
                       ],
                   ]);

        $this->add([
                       'type'       => 'Select',
                       'name'       => 'es_services_pdf',
                       'options'    => [
                           'value_options' => Util::collectionAsOptions($this->getServiceEtatSortie()->getList()),
                           'label'         => 'État de sortie pour l\'édition PDF des services',
                       ],
                       'attributes' => [
                           'class'            => 'selectpicker',
                           'data-live-search' => 'true',
                       ],
                   ]);

        $this->add([
                       'type'       => 'Select',
                       'name'       => 'es_services_csv',
                       'options'    => [
                           'value_options' => Util::collectionAsOptions($this->getServiceEtatSortie()->getList()),
                           'label'         => 'État de sortie pour l\'export CSV des services',
                       ],
                       'attributes' => [
                           'class'            => 'selectpicker',
                           'data-live-search' => 'true',
                       ],
                   ]);

        $this->add([
                       'type'       => 'Select',
                       'name'       => 'es_etat_paiement',
                       'options'    => [
                           'value_options' => Util::collectionAsOptions($this->getServiceEtatSortie()->getList()),
                           'label'         => 'État de sortie pour les états de paiement',
                       ],
                       'attributes' => [
                           'class'            => 'selectpicker',
                           'data-live-search' => 'true',
                       ],
                   ]);

        $this->add([
                       'type'       => 'Select',
                       'name'       => 'scenario_charges_services',
                       'options'    => [
                           'value_options' => Util::collectionAsOptions($this->getServiceScenario()->getList($this->getServiceScenario()->finderByHistorique())),
                       ],
                       'attributes' => [
                           'class'            => 'selectpicker',
                           'data-live-search' => 'true',
                           'data-size'        => 20,
                       ],
                   ]);

        $this->add([
                       'type'       => 'Select',
                       'name'       => 'report_service',
                       'options'    => [
                           'value_options' => [
                               TypeVolumeHoraire::CODE_PREVU   => 'Se baser sur le prévisionnel validé de l\'année prédédente',
                               TypeVolumeHoraire::CODE_REALISE => 'Se baser sur le réalisé validé de l\'année prédédente',
                               'desactive'                     => 'Fonctionnalité désactivée',
                           ],
                       ],
                       'attributes' => [
                           'class'     => 'selectpicker',
                           'data-size' => 20,
                       ],
                   ]);

        $this->add([
                       'type'       => 'Select',
                       'name'       => 'constatation_realise',
                       'options'    => [
                           'value_options' => [
                               TypeVolumeHoraire::CODE_PREVU => 'Constater comme réalisées les heures prévisionelles validées',
                               'desactive'                   => 'Fonctionnalité désactivée',
                           ],
                       ],
                       'attributes' => [
                           'class'     => 'selectpicker',
                           'data-size' => 20,
                       ],
                   ]);

        $this->add([
                       'type'       => 'Select',
                       'name'       => 'centres_couts_paye',
                       'options'    => [
                           'value_options' => [
                               'enseignement' => 'Utiliser les centres de coûts de la composante d\'enseignement',
                               'affectation'  => 'Utiliser les centres de coûts de la composante d\'affectation de l\'intervenant',
                           ],
                       ],
                       'attributes' => [
                           'class'     => 'selectpicker',
                           'data-size' => 20,
                       ],
                   ]);


        $this->add([
                       'type'       => 'Time',
                       'name'       => 'horaire_nocturne',
                       'options'    => [
                           'label' => "Horaire nocturne",
                       ],
                       'attributes' => [
                           'title' => "Horaire à partir duquel les heures faites sont considérées comme nocturnes",
                       ],
                   ]);

        $this->add([
                       'type'       => 'Select',
                       'name'       => 'taux-remu',
                       'options'    => [
                           'label' => "Taux de rémunération par défaut",
                       ],
                       'attributes' => [
                           'class'            => 'selectpicker',
                           'data-live-search' => 'true',
                       ],
                   ]);
        $this->setValueOptions('taux-remu', 'SELECT t FROM ' . TauxRemu::class . ' t WHERE t.histoDestruction IS NULL ORDER BY t.libelle');

        $this->add([
                       'type'       => 'Text',
                       'name'       => 'taux_conges_payes',
                       'options'    => [
                           'label'  => "Taux pour prise en compte des congés payés",
                           'suffix' => '%',
                       ],
                       'attributes' => [
                           'class' => 'input-sm',
                           'step'  => 'any',
                           'min'   => 0,
                           'max'   => 1,
                       ],
                   ]);

        $this->add([
                       'name'    => 'indicateur_email_expediteur',
                       'type'    => 'Text',
                       'options' => [
                           'label' => 'Email expéditeur',
                       ],
                   ]);

        $this->add([
                       'type'       => 'Select',
                       'name'       => 'contrat_regle_franchissement',
                       'options'    => [
                           'value_options' => [
                               Parametre::CONTRAT_FRANCHI_VALIDATION  => 'Validation du contrat',
                               Parametre::CONTRAT_FRANCHI_DATE_RETOUR => 'Validation & saisie de la date de retour du contrat signé',
                           ],
                       ],
                       'attributes' => [
                           'class'            => 'selectpicker',
                           'data-live-search' => 'true',
                       ],
                   ]);

        $this->add([
                       'name'    => 'contrat_modele_mail_objet',
                       'type'    => 'Text',
                       'options' => [
                           'label' => 'Objet du mail',

                       ],

                   ]);

        $this->add([
                       'name'    => 'contrat_mail_expediteur',
                       'type'    => Element\Email::class,
                       'options' => [
                           'label' => 'Expéditeur du mail (si vide, l\'email de l\'utilisateur sera utilisé)',

                       ],

                   ]);

        $this->add([
                       'name'       => 'contrat_modele_mail',
                       'type'       => 'Textarea',
                       'options'    => [
                           'label' => 'Corps du mail envoyé aux intervenants lorsqu\'on leur transmet leur contrat',

                       ],
                       'attributes' => [
                           'rows' => 12,
                       ],
                   ]);

        $this->add([
                       'name'    => 'candidature_modele_acceptation_mail_objet',
                       'type'    => 'Text',
                       'options' => [
                           'label' => 'Objet du mail d\'acceptation',

                       ],

                   ]);

        $this->add([
                       'name'    => 'candidature_modele_refus_mail_objet',
                       'type'    => 'Text',
                       'options' => [
                           'label' => 'Objet du mail de refus',

                       ],

                   ]);

        $this->add([
                       'name'    => 'candidature_mail_expediteur',
                       'type'    => Element\Email::class,
                       'options' => [
                           'label' => 'Expéditeur du mail (si vide, l\'email de l\'utilisateur sera utilisé)',

                       ],

                   ]);

        $this->add([
                       'name'       => 'candidature_modele_acceptation_mail',
                       'type'       => 'Textarea',
                       'options'    => [
                           'label' => 'Corps du mail envoyé aux candidats dont la candidature est acceptée',

                       ],
                       'attributes' => [
                           'rows' => 12,
                       ],
                   ]);


        $this->add([
                       'name'       => 'candidature_modele_refus_mail',
                       'type'       => 'Textarea',
                       'options'    => [
                           'label' => 'Corps du mail envoyé aux candidats dont la candidature est refusée',

                       ],
                       'attributes' => [
                           'rows' => 12,
                       ],
                   ]);

        //Récupération des parapheurs disponibles dans l'application
        $letterFiles              = $this->getSignatureConfigurationService()->getLetterFiles();
        $listeLetterFiles['none'] = 'Signature électronique désactivée';
        foreach ($letterFiles as $key => $value) {
            $listeLetterFiles[$key] = $value['label'];
        }


        $this->add([
                       'type'       => 'Select',
                       'name'       => 'signature_electronique_parapheur',
                       'options'    => [
                           'value_options' => $listeLetterFiles,
                       ],
                       'attributes' => [
                           'class'            => 'selectpicker',
                           'data-live-search' => 'true',
                       ],
                   ]);


        $this->add([
                       'name'       => 'page_contact',
                       'type'       => 'Textarea',
                       'options'    => [
                           'label' => 'Contenu de la page "Contact"',

                       ],
                       'attributes' => [
                           'rows' => 6,
                       ],
                   ]);


        $this->add([
                       'name'       => 'page_accueil',
                       'type'       => 'Textarea',
                       'options'    => [
                           'label' => 'Message de la page d\'accueil',

                       ],
                       'attributes' => [
                           'rows' => 6,
                       ],
                   ]);

        $this->add([
                       'name'       => 'connexion_sans_role_ni_statut',
                       'type'       => 'Textarea',
                       'options'    => [
                           'label' => 'Message informatif si l\'utilisateur n\'est pas intervenant et n\'a aucune affectation',

                       ],
                       'attributes' => [
                           'rows' => 6,
                       ],
                   ]);

        $this->add([
                       'name'       => 'connexion_non_autorise',
                       'type'       => 'Textarea',
                       'options'    => [
                           'label' => 'Message informatif si l\'intervenant n\'est pas autorisé à se connecter',

                       ],
                       'attributes' => [
                           'rows' => 6,
                       ],
                   ]);


        $this->add([
                       'name'       => 'submit',
                       'type'       => 'Submit',
                       'attributes' => [
                           'value' => 'Enregistrer',
                           'class' => 'btn btn-primary',
                       ],
                   ]);

        $this->add([
                       'type'       => 'Select',
                       'name'       => 'avenant',
                       'options'    => [
                           'label' => 'En cas de changement (heures ou date de fin) sur des contrats ou avenants existants :',

                           'value_options' => [
                               PARAMETRE::AVENANT_AUTORISE  => 'demander à générer des avenants',
                               PARAMETRE::AVENANT_DESACTIVE => 'ne rien faire, laisser le contrat tel quel',
                           ],
                       ],
                       'attributes' => [
                           'class'     => 'selectpicker',
                           'data-size' => 20,
                       ],
                   ]);

        $this->add([
                       'type'       => 'Select',
                       'name'       => 'contrat_ens',
                       'options'    => [
                           'label' => 'Pour les enseignements et le référentiel',

                           'value_options' => [
                               PARAMETRE::CONTRAT_ENS_GLOBAL     => 'Un contrat global pour l\'établissement',
                               PARAMETRE::CONTRAT_ENS_COMPOSANTE => 'Un contrat ou un avenant par composante',
                           ],
                       ],
                       'attributes' => [
                           'class'     => 'selectpicker',
                           'data-size' => 20,
                       ],
                   ]);

        $this->add([
                       'type'       => 'Select',
                       'name'       => 'contrat_mis',
                       'options'    => [
                           'label' => 'Pour les missions',

                           'value_options' => [
                               PARAMETRE::CONTRAT_MIS_GLOBAL     => 'Un contrat global pour l\'établissement',
                               PARAMETRE::CONTRAT_MIS_COMPOSANTE => 'Un contrat ou un avenant par composante',
                               PARAMETRE::CONTRAT_MIS_MISSION    => 'Un contrat par mission',
                           ],
                       ],
                       'attributes' => [
                           'class'     => 'selectpicker',
                           'data-size' => 20,
                       ],
                   ]);

        $this->add([
                       'type'       => 'Select',
                       'name'       => Parametre::CONTRAT_DIRECT,
                       'options'    => [
                           'label'         => 'Étape facultative de projet de contrat',
                           'value_options' => [
                               PARAMETRE::CONTRAT_DIRECT => 'Le contrat est créé directement sans passer par l\'étape projet',
                               'desactive'               => 'Un projet de contrat doit être validé pour devenir un contrat',
                           ],
                       ],
                       'attributes' => [
                           'class'     => 'selectpicker',
                           'data-size' => 20,
                       ],
                   ]);

        $this->add([
                       'type'       => 'Select',
                       'name'       => 'contrat_date',
                       'options'    => [
                           'label'         => 'Possibilité de saisir la date de retour signé sans ajouter le contrat',
                           'value_options' => [
                               PARAMETRE::CONTRAT_DATE => 'La saisie est possible sans contrat',
                               'desactive'             => 'Le contrat est nécessaire pour pouvoir saisir',
                           ],
                       ],
                       'attributes' => [
                           'class'     => 'selectpicker',
                           'data-size' => 20,
                       ],
                   ]);
    }



    /**
     * Should return an array specification compatible with
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            /* Filtres et validateurs */
        ];
    }

}