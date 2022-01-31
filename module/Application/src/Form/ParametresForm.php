<?php

namespace Application\Form;

use Application\Entity\Db\Parametre;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Application\Service\Traits\DomaineFonctionnelServiceAwareTrait;
use Application\Service\Traits\EtatSortieServiceAwareTrait;
use Application\Service\Traits\FormuleServiceAwareTrait;
use Application\Service\Traits\PaysServiceAwareTrait;
use Application\Service\Traits\ScenarioServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;
use Application\Service\Traits\UtilisateurServiceAwareTrait;
use Application\Service\Traits\WfEtapeServiceAwareTrait;
use Laminas\Form\Element;
use UnicaenApp\Form\Element\SearchAndSelect;
use UnicaenApp\Util;


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
    use WfEtapeServiceAwareTrait;


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
            'type'       => 'Select',
            'name'       => 'structure_univ',
            'options'    => [
                'value_options' => Util::collectionAsOptions($this->getServiceStructure()->getList(
                    $this->getServiceStructure()->finderByHistorique()
                )),
                'label'         => 'Composante représentant l\'université',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
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
            'options'    => [
                'value_options' => Util::collectionAsOptions($this->getServiceFormule()->getList()),
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
        ]);

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
            'name'       => 'es_winpaie',
            'options'    => [
                'value_options' => Util::collectionAsOptions($this->getServiceEtatSortie()->getList()),
                'label'         => 'État de sortie pour l\'extraction Winpaie',
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
            'name'       => 'modalite_services_prev_ens',
            'options'    => [
                'value_options' => [
                    Parametre::SERVICES_MODALITE_SEMESTRIEL => 'Par semestre (mode semestriel)',
                    Parametre::SERVICES_MODALITE_CALENDAIRE => 'Par date et heure de cours (mode calendaire)',
                ],
            ],
            'attributes' => [
                'class'     => 'selectpicker',
                'data-size' => 20,
            ],
        ]);

        $this->add([
            'type'       => 'Select',
            'name'       => 'modalite_services_prev_ref',
            'options'    => [
                'value_options' => [
                    Parametre::SERVICES_MODALITE_SEMESTRIEL => 'Par semestre (mode semestriel)',
                    Parametre::SERVICES_MODALITE_CALENDAIRE => 'Par date et heure de cours (mode calendaire)',
                ],
            ],
            'attributes' => [
                'class'     => 'selectpicker',
                'data-size' => 20,
            ],
        ]);

        $this->add([
            'type'       => 'Select',
            'name'       => 'modalite_services_real_ens',
            'options'    => [
                'value_options' => [
                    Parametre::SERVICES_MODALITE_SEMESTRIEL => 'Par semestre (mode semestriel)',
                    Parametre::SERVICES_MODALITE_CALENDAIRE => 'Par date et heure de cours (mode calendaire)',
                ],
            ],
            'attributes' => [
                'class'     => 'selectpicker',
                'data-size' => 20,
            ],
        ]);

        $this->add([
            'type'       => 'Select',
            'name'       => 'modalite_services_real_ref',
            'options'    => [
                'value_options' => [
                    Parametre::SERVICES_MODALITE_SEMESTRIEL => 'Par semestre (mode semestriel)',
                    Parametre::SERVICES_MODALITE_CALENDAIRE => 'Par date et heure de cours (mode calendaire)',
                ],
            ],
            'attributes' => [
                'class'     => 'selectpicker',
                'data-size' => 20,
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
                'rows' => 6,
            ],
        ]);

        $this->add([
            'type'       => 'Select',
            'name'       => 'export_rh_franchissement',
            'options'    => [
                'value_options' => Util::collectionAsOptions($this->getServiceWfEtape()->getList()),
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