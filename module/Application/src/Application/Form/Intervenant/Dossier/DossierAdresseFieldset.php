<?php

namespace Application\Form\Intervenant\Dossier;

use Application\Entity\Db\Dossier as Dossier;
use Application\Form\AbstractFieldset;
use Application\Form\Elements\PaysSelect;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\StatutIntervenantServiceAwareTrait;

/**
 * Description of DossierFieldset
 *
 */
class DossierAdresseFieldset extends AbstractFieldset
{
    use ContextServiceAwareTrait;
    use StatutIntervenantServiceAwareTrait;

    static private $franceId;



    /**
     * This function is automatically called when creating element with factory. It
     * allows to perform various operations (add elements...)
     */
    public function init()
    {
        //$hydrator = new DossierFieldsetDoctrineHydrator($this->getServiceContext()->getEntityManager());

        $this->setObject(new Dossier())
            ->addElements();

        //->setHydrator($hydrator)
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
                'label'         => 'Précisions',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'rows' => 2,
            ],
            'type'       => 'Textarea',
        ]);

        /**
         * Lieu dit
         */
        $this->add([
            'name'       => 'lieuDit',
            'options'    => [
                'label'         => 'Lieu dit',
            ],
            'type'       => 'Text',
        ]);

        /**
         * Numéro
         */
        $this->add([
            'name'       => 'numero',
            'options'    => [
                'label'         => '',
            ],
            'attributes' => [
                'placeholder' => 'N°'
            ],
            'type'       => 'Text',
        ]);

        /**
         * complement
         */
        $this->add([
            'name'       => 'numeroComplement',
            'options'    => [
                'label'         => '',
                'empty_option'              => "Compl.",
                'value_options'             => ['Bis','Ter'],
            ],
            'type'       => 'Select',
        ]);

        /**
         * voirie
         */
        $this->add([
            'name'       => 'voirie',
            'options'    => [
                'label'         => '',
                'empty_option'              => "type de voirie",
                'value_options'             => ['rue','boulevard'],
            ],
            'type'       => 'Select',
        ]);
        /**
         * voie
         */
        $this->add([
            'name'       => 'voie',
            'options'    => [
                'label'         => '',
            ],
            'attributes' => [
              'placeholder' => 'nom de la voie'
            ],
            'type'       => 'Text',
        ]);
        /**
         * Code postal
         */
        $this->add([
            'name'       => 'codePostal',
            'options'    => [
                'label'         => '',
            ],
            'attributes' => [
                'placeholder' => 'Code postal'
            ],
            'type'       => 'Text',
        ]);

        /**
         * Ville
         */
        $this->add([
            'name'       => 'codePostal',
            'options'    => [
                'label'         => '',
            ],
            'attributes' => [
                'placeholder' => 'Ville'
            ],
            'type'       => 'Text',
        ]);

        /**
         * Pays
         */

        $paysSelect = new \Application\Form\CustomElements\PaysSelect('pays', []);
        $paysSelect->setLabel("Pays");

        $this->add($paysSelect);


        return $this;
    }



    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {

        return [];
    }




}