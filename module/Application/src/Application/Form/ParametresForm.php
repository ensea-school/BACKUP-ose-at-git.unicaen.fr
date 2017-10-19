<?php

namespace Application\Form;

use Application\Service\Traits\AnneeAwareTrait;
use Application\Service\Traits\DomaineFonctionnelAwareTrait;
use Application\Service\Traits\ScenarioServiceAwareTrait;
use Application\Service\Traits\UtilisateurAwareTrait;
use UnicaenApp\Form\Element\SearchAndSelect;
use UnicaenApp\Util;


/**
 * Description of ParametresForm
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ParametresForm extends AbstractForm
{
    use AnneeAwareTrait;
    use DomaineFonctionnelAwareTrait;
    use UtilisateurAwareTrait;
    use ScenarioServiceAwareTrait;

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
            'name'    => 'contrat_civilite_president',
            'type'    => 'Text',
            'options' => [
                'label' => 'Civilité du président (avec article)',
            ],
        ]);

        $this->add([
            'name'    => 'contrat_etablissement',
            'type'    => 'Text',
            'options' => [
                'label' => 'Établissement',
            ],
        ]);

        $this->add([
            'name'    => 'contrat_etablissement_represente',
            'type'    => 'Text',
            'options' => [
                'label' => 'Représenté par',
            ],
        ]);

        $this->add([
            'name'    => 'contrat_lieu_signature',
            'type'    => 'Text',
            'options' => [
                'label' => 'Lieu de signature',
            ],
        ]);

        $personnel = new SearchAndSelect('directeur_ressources_humaines_id');
        $personnel ->setRequired(true)
            ->setSelectionRequired(true)
            ->setAutocompleteSource(
                $this->getUrl('recherche', ['action' => 'personnelFind'])
            )
            ->setAttributes(['title' => "Saisissez le nom suivi éventuellement du prénom (2 lettres au moins)"]);
        $this->add($personnel);

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
            'name'    => 'formule_function_name',
            'type'    => 'Text',
            'options' => [
                'label' => 'Fonction',
            ],
        ]);

        $this->add([
            'name'    => 'formule_package_name',
            'type'    => 'Text',
            'options' => [
                'label' => 'Package Oracle',
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
            'name'    => 'winpaie_carte',
            'type'    => 'Text',
            'options' => [
                'label' => 'Carte',
            ],
        ]);

        $this->add([
            'name'    => 'winpaie_mc',
            'type'    => 'Text',
            'options' => [
                'label' => 'MC',
            ],
        ]);

        $this->add([
            'name'    => 'winpaie_retenue',
            'type'    => 'Text',
            'options' => [
                'label' => 'Retenue',
            ],
        ]);

        $this->add([
            'name'    => 'winpaie_sens',
            'type'    => 'Text',
            'options' => [
                'label' => 'Sens',
            ],
        ]);

        $this->add([
            'name'    => 'export_pdf_services_signature_1',
            'type'    => 'Text',
            'options' => [
                'label' => 'Signature 1',
            ],
        ]);

        $this->add([
            'name'    => 'export_pdf_services_signataire_1',
            'type'    => 'Textarea',
            'options' => [
                'label' => 'Signataire 1',
            ],
        ]);

        $this->add([
            'name'    => 'export_pdf_services_signature_2',
            'type'    => 'Text',
            'options' => [
                'label' => 'Signature 2',
            ],
        ]);

        $this->add([
            'name'    => 'export_pdf_services_signataire_2',
            'type'    => 'Textarea',
            'options' => [
                'label' => 'Signataire 2',
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