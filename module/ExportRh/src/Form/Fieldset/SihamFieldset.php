<?php

namespace ExportRh\Form\Fieldset;

use Application\Form\AbstractFieldset;
use ExportRh\Connecteur\Siham\SihamConnecteurAwareTrait;

class SihamFieldset extends AbstractFieldset
{
    use SihamConnecteurAwareTrait;

    public function init()
    {
        /**
         * Année universitaire
         */
        $this->add([
            'name'       => 'anneeUniversitaire',
            'options'    => [
                'label'         => 'Année universitaire de prise en charge',
                'label_options' => [
                    'disable_html_escape' => true,
                ],],
            'attributes' => [
                'class' => 'form-control anneeUniversitaire',
                //'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);


        $this->get('anneeUniversitaire')
            ->setValueOptions(['2020-09-01' => '2020/2021',
                               '2021-09-01' => '2021/2022',]);


        /**
         * Modalite de service
         */
        $this->add([
            'name'       => 'modaliteService',
            'options'    => [
                'label'         => 'Modalités',
                'label_options' => [
                    'disable_html_escape' => true,
                ],],
            'attributes' => [
                'class' => 'form-control modaliteService',
                //'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

        $valuesModaliteService = \UnicaenApp\Util::collectionAsOptions($this->getSihamConnecteur()->recupererListeModalites());

        if (count($valuesModaliteService) == 1) {
            $this->get('modaliteService')
                ->setValueOptions($valuesModaliteService)
                ->setAttribute('readonly', 'readonly');
        } else {
            $this->get('modaliteService')
                ->setValueOptions(['' => '(Sélectionnez une modalité de service)'] + $valuesModaliteService);
        }


        //Statut
        $this->add([
            'name'       => 'statut',
            'options'    => [
                'label'         => 'Statut',
                'label_options' => [
                    'disable_html_escape' => true,
                ],],
            'attributes' => [
                'class' => 'form-control statut',
                //'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

        $valuesStatut = \UnicaenApp\Util::collectionAsOptions($this->getSihamConnecteur()->recupererListeStatuts());

        if (count($valuesStatut) == 1) {
            $this->get('statut')
                ->setValueOptions($valuesStatut)
                ->setAttribute('readonly', 'readonly');
        } else {
            $this->get('statut')
                ->setValueOptions(['' => '(Sélectionnez un statut)'] + $valuesStatut);
        }


        //Position administrative
        $this->add([
            'name'       => 'position',
            'options'    => [
                'label'         => 'Position administrative',
                'label_options' => [
                    'disable_html_escape' => true,
                ],],
            'attributes' => [
                'class' => 'form-control position',
                //'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

        $valuesPosition = \UnicaenApp\Util::collectionAsOptions($this->getSihamConnecteur()->recupererListePositions());

        if (count($valuesPosition) == 1) {
            $this->get('position')
                ->setValueOptions($valuesPosition)
                ->setAttribute('readonly', 'readonly');
        } else {
            $this->get('statut')
                ->setValueOptions(['' => '(Sélectionnez une position)'] + $valuesPosition);
        }


        //Affectation
        $this->add([
            'name'       => 'affectation',
            'options'    => [
                'label'         => 'Affectation',
                'label_options' => [
                    'disable_html_escape' => true,
                ],],
            'attributes' => [
                'class' => 'form-control affectation',
                //'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

        $valuesAffectation = \UnicaenApp\Util::collectionAsOptions($this->getSihamConnecteur()->recupererListeUO());

        if (count($valuesAffectation) == 1) {
            $this->get('affectation')
                ->setValueOptions($valuesAffectation)
                ->setAttribute('readonly', 'readonly');
        } else {
            $this->get('affectation')
                ->setValueOptions(['' => '(Sélectionnez une affectation)'] + $valuesAffectation);
        }


        //Type d'emploi
        $this->add([
            'name'       => 'emploi',
            'options'    => [
                'label'         => 'Type d\'emploi',
                'label_options' => [
                    'disable_html_escape' => true,
                ],],
            'attributes' => [
                'class' => 'form-control emploi',
                //'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

        $valuesEmploi = \UnicaenApp\Util::collectionAsOptions($this->getSihamConnecteur()->recupererListeEmplois());

        if (count($valuesEmploi) == 1) {
            $this->get('emploi')
                ->setValueOptions($valuesEmploi)
                ->setAttribute('readonly', 'readonly');
        } else {
            $this->get('emploi')
                ->setValueOptions(['' => '(Sélectionnez un emploi)'] + $valuesEmploi);
        }


        return $this;
    }



    public function getInputFilterSpecification()
    {

        $spec = [
            'nomUsuel' => [
                'required' => true,
            ],

        ];

        return $spec;
    }
}