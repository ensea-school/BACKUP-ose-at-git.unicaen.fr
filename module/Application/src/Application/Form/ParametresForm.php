<?php

namespace Application\Form;

use Application\Entity\Db\Parametre;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Application\Service\Traits\DomaineFonctionnelServiceAwareTrait;
use Application\Service\Traits\EtatSortieServiceAwareTrait;
use Application\Service\Traits\FormuleServiceAwareTrait;
use Application\Service\Traits\PaysServiceAwareTrait;
use Application\Service\Traits\ScenarioServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;
use Application\Service\Traits\UtilisateurServiceAwareTrait;
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

    public function init()
    {
        $this->setAttribute('action',$this->getCurrentUrl());

        $this->add([
            'type' => 'Select',
            'name' => 'annee',
            'options'    => [
                'value_options' => Util::collectionAsOptions($this->getServiceAnnee()->getList()),
                'label' => 'Pour la saisie des services',
            ],
            'attributes' => [
                'class' => 'selectpicker',
                'data-live-search' => 'true'
            ],
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'annee_import',
            'options'    => [
                'value_options' => Util::collectionAsOptions($this->getServiceAnnee()->getList()),
                'label' => 'Pour l\'import',
            ],
            'attributes' => [
                'class' => 'selectpicker',
                'data-live-search' => 'true'
            ],
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'structure_univ',
            'options'    => [
                'value_options' => Util::collectionAsOptions($this->getServiceStructure()->getList(
                    $this->getServiceStructure()->finderByHistorique()
                )),
                'label' => 'Composante représentant l\'université',
            ],
            'attributes' => [
                'class' => 'selectpicker',
                'data-live-search' => 'true'
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
            'type' => 'Select',
            'name' => 'domaine_fonctionnel_ens_ext',
            'options'    => [
                'value_options' => Util::collectionAsOptions($this->getServiceDomaineFonctionnel()->getList($this->getServiceDomaineFonctionnel()->finderByHistorique())),
            ],
            'attributes' => [
                'class' => 'selectpicker',
                'data-live-search' => 'true'
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
            'type' => 'Select',
            'name' => 'formule',
            'options'    => [
                'value_options' => Util::collectionAsOptions($this->getServiceFormule()->getList()),
            ],
            'attributes' => [
                'class' => 'selectpicker',
                'data-live-search' => 'true'
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
            'type' => 'Select',
            'name' => 'oseuser',
            'options'    => [
                'value_options' => Util::collectionAsOptions($this->getServiceUtilisateur()->getList()),
            ],
            'attributes' => [
                'class' => 'selectpicker',
                'data-live-search' => 'true',
                'data-size' => 20,
            ],
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'es_winpaie',
            'options'    => [
                'value_options' => Util::collectionAsOptions($this->getServiceEtatSortie()->getList()),
                'label' => 'État de sortie pour l\'extraction Winpaie',
            ],
            'attributes' => [
                'class' => 'selectpicker',
                'data-live-search' => 'true'
            ],
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'es_services_pdf',
            'options'    => [
                'value_options' => Util::collectionAsOptions($this->getServiceEtatSortie()->getList()),
                'label' => 'État de sortie pour l\'édition PDF des services',
            ],
            'attributes' => [
                'class' => 'selectpicker',
                'data-live-search' => 'true'
            ],
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'es_etat_paiement',
            'options'    => [
                'value_options' => Util::collectionAsOptions($this->getServiceEtatSortie()->getList()),
                'label' => 'État de sortie pour les états de paiement',
            ],
            'attributes' => [
                'class' => 'selectpicker',
                'data-live-search' => 'true'
            ],
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'scenario_charges_services',
            'options'    => [
                'value_options' => Util::collectionAsOptions($this->getServiceScenario()->getList($this->getServiceScenario()->finderByHistorique())),
            ],
            'attributes' => [
                'class' => 'selectpicker',
                'data-live-search' => 'true',
                'data-size' => 20,
            ],
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'pays_france',
            'options'    => [
                'value_options' => Util::collectionAsOptions($this->getServicePays()->getList($this->getServicePays()->finderByHistorique())),
            ],
            'attributes' => [
                'class' => 'selectpicker',
                'data-live-search' => 'true',
                'data-size' => 20,
            ],
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'modalite_services_prev_ens',
            'options'    => [
                'value_options' => [
                    Parametre::SERVICES_MODALITE_SEMESTRIEL => 'Par semestre (mode semestriel)',
                    Parametre::SERVICES_MODALITE_CALENDAIRE => 'Par date et heure de cours (mode calendaire)'
                ],
            ],
            'attributes' => [
                'class' => 'selectpicker',
                'data-size' => 20,
            ],
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'modalite_services_prev_ref',
            'options'    => [
                'value_options' => [
                    Parametre::SERVICES_MODALITE_SEMESTRIEL => 'Par semestre (mode semestriel)',
                    Parametre::SERVICES_MODALITE_CALENDAIRE => 'Par date et heure de cours (mode calendaire)'
                ],
            ],
            'attributes' => [
                'class' => 'selectpicker',
                'data-size' => 20,
            ],
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'modalite_services_real_ens',
            'options'    => [
                'value_options' => [
                    Parametre::SERVICES_MODALITE_SEMESTRIEL => 'Par semestre (mode semestriel)',
                    Parametre::SERVICES_MODALITE_CALENDAIRE => 'Par date et heure de cours (mode calendaire)'
                ],
            ],
            'attributes' => [
                'class' => 'selectpicker',
                'data-size' => 20,
            ],
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'modalite_services_real_ref',
            'options'    => [
                'value_options' => [
                    Parametre::SERVICES_MODALITE_SEMESTRIEL => 'Par semestre (mode semestriel)',
                    Parametre::SERVICES_MODALITE_CALENDAIRE => 'Par date et heure de cours (mode calendaire)'
                ],
            ],
            'attributes' => [
                'class' => 'selectpicker',
                'data-size' => 20,
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
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
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