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
            ->setValueOptions([''           => '(Sélectionnez une année de prise en charge)',
                               '2020-09-01' => '2020/2021',
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


        $this->get('modaliteService')
            ->setValueOptions(['' => '(Sélectionnez une modalité de service)'] + \UnicaenApp\Util::collectionAsOptions($this->getSihamConnecteur()->recupererListeModalites()));

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


        $this->get('statut')
            ->setValueOptions(['' => '(Sélectionnez un statut)'] + \UnicaenApp\Util::collectionAsOptions($this->getSihamConnecteur()->recupererListeStatuts()));

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


        $this->get('position')
            ->setValueOptions(['' => '(Sélectionnez une position administrative)'] + \UnicaenApp\Util::collectionAsOptions($this->getSihamConnecteur()->recupererListePositions()));

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


        $this->get('affectation')
            ->setValueOptions(['' => '(Sélectionnez une affectation)'] + \UnicaenApp\Util::collectionAsOptions($this->getSihamConnecteur()->recupererListeUO()));

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


        $this->get('emploi')
            ->setValueOptions(['' => '(Sélectionnez un type d\'emploi)'] + \UnicaenApp\Util::collectionAsOptions($this->getSihamConnecteur()->recupererListeEmplois()));

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