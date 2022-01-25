<?php

namespace Application\Form\Adresse;

use Application\Form\AbstractFieldset;
use Application\Form\Elements\PaysSelect;
use Application\Service\Traits\AdresseNumeroComplServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\PaysServiceAwareTrait;
use Intervenant\Service\StatutServiceAwareTrait;
use Application\Service\Traits\VoirieServiceAwareTrait;

/**
 * Description of AdresseFieldset
 *
 */
class AdresseFieldset extends AbstractFieldset
{
    use ContextServiceAwareTrait;
    use StatutServiceAwareTrait;
    use PaysServiceAwareTrait;
    use VoirieServiceAwareTrait;
    use AdresseNumeroComplServiceAwareTrait;

    static private $franceId;



    /**
     * This function is automatically called when creating element with factory. It
     * allows to perform various operations (add elements...)
     */
    public function init()
    {
        $this->addElements();
    }



    /**
     * @return self
     */
    private function addElements()
    {


        /**
         * Précision
         */
        $this->add([
            'name'       => 'precisions',
            'options'    => [
                'label'         => 'Complément d\'adresse',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'class' => 'dossierElement',
                'rows'  => 2,
            ],
            'type'       => 'Textarea',
        ]);

        /**
         * Lieu dit
         */
        $this->add([
            'name'       => 'lieuDit',
            'options'    => [
                'label' => 'Lieu dit',
            ],
            'attributes' => [
                'class' => 'dossierElement',
            ],
            'type'       => 'Text',
        ]);

        /**
         * Numéro
         */
        $this->add([
            'name'       => 'numero',
            'options'    => [
                'label' => 'N°',
            ],
            'attributes' => [
                'class'       => 'dossierElement',
                'placeholder' => 'N°',
                'pattern'     => '[0-9]*',
                'title'       => 'Le champs doit contenir uniquement des numéros',
                'maxlength'   => 4,
            ],
            'type'       => 'Text',
        ]);

        /**
         * complement
         */
        $this->add([
            'name'       => 'numeroComplement',
            'options'    => [
                'label'         => 'Compl.',
                'empty_option'  => ' ',
                'value_options' => \UnicaenApp\Util::collectionAsOptions($this->getServiceAdresseNumeroCompl()->getList()),
            ],
            'attributes' => [
                'class'            => 'selectpicker dossierElement',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);


        /**
         * voirie
         */
        $qb      = $this->getServiceVoirie()->finderByHistorique();
        $voiries = \UnicaenApp\Util::collectionAsOptions($this->getServiceVoirie()->getList($qb));
        $this->add([
            'name'       => 'voirie',
            'options'    => [
                'label'         => 'Voirie',
                'empty_option'  => ' ',
                'value_options' => $voiries,
            ],
            'attributes' => [
                'class'            => 'selectpicker dossierElement',
                'data-live-search' => 'true',
                'data-size'        => 10,
            ],
            'type'       => 'Select',
        ]);

        /**
         * voie
         */
        $this->add([
            'name'       => 'voie',
            'options'    => [
                'label' => 'Voie',
            ],
            'attributes' => [
                'class'       => 'dossierElement',
                'placeholder' => 'nom de la voie',
            ],
            'type'       => 'Text',
        ]);
        /**
         * Code postal
         */
        $this->add([
            'name'       => 'codePostal',
            'options'    => [
                'label'         => 'Code postal <span class="text-danger">*</span>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],],
            'attributes' => [
                'class'       => 'dossierElement',
                'placeholder' => 'Code postal',
            ],
            'type'       => 'Text',
        ]);

        /**
         * Ville
         */
        $this->add([
            'name'       => 'ville',
            'options'    => [
                'label'         => 'Ville <span class="text-danger">*</span>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],],
            'attributes' => [
                'class'       => 'dossierElement',
                'placeholder' => 'Ville',
            ],
            'type'       => 'Text',
        ]);

        /**
         * Pays
         */


        $this->add([
            'name'       => 'pays',
            'options'    => [
                'label'         => 'Pays <span class="text-danger">*</span>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],],
            'attributes' => [
                'class'            => 'selectpicker dossierElement',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

        $this->get('pays')
            ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($this->getServicePays()->getList()));


        return $this;
    }



    /**
     * Should return an array specification compatible with
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {

        $spec = [
            'precisions'       => [
                'required' => false,
            ],
            'lieuDit'          => [
                'required' => false,
            ],
            'numero'           => [
                'required' => false,
            ],
            'numeroComplement' => [
                'required' => false,
            ],
            'voirie'           => [
                'required' => false,
            ],
            'voie'             => [
                'required' => false,
            ],
            'codePostal'       => [
                'required' => false,
            ],
            'ville'            => [
                'required' => false,
            ],
            'pays'             => [
                'required' => false,
            ],

        ];

        return $spec;
    }

}