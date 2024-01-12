<?php

namespace Dossier\Form;

use Application\Form\AbstractFieldset;
use Application\Service\Traits\ContextServiceAwareTrait;
use Intervenant\Service\CiviliteServiceAwareTrait;
use Intervenant\Service\StatutServiceAwareTrait;
use Lieu\Service\DepartementServiceAwareTrait;
use Lieu\Service\PaysServiceAwareTrait;

/**
 * Description of DossierFieldset
 *
 */
class DossierIdentiteFieldset extends AbstractFieldset
{
    use ContextServiceAwareTrait;
    use StatutServiceAwareTrait;
    use PaysServiceAwareTrait;
    use DepartementServiceAwareTrait;
    use CiviliteServiceAwareTrait;


    /**
     * This function is automatically called when creating element with factory. It
     * allows to perform various operations (add elements...)
     */
    public function init()
    {
        /**
         * Id
         */
        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        /**
         * Nom usuel
         */
        $this->add([
            'name'       => 'nomUsuel',
            'options'    => [
                'label'         => 'Nom usuel <span class="text-danger">*</span>',
                'label_options' => ['disable_html_escape' => true],
            ],
            'attributes' => [
                'class' => 'dossierElement',
            ],
            'type'       => 'Text',
        ]);

        /**
         * Nom patro
         */
        $this->add([
            'name'       => 'nomPatronymique',
            'options'    => [
                'label'         => 'Nom de naissance',
                'label_options' => ['disable_html_escape' => true],
            ],
            'attributes' => [
                'class' => 'dossierElement',
            ],
            'type'       => 'Text',
        ]);

        /**
         * Prénom
         */
        $this->add([
            'name'       => 'prenom',
            'options'    => [
                'label'         => 'Prénom <span class="text-danger">*</span>',
                'label_options' => ['disable_html_escape' => true],],
            'attributes' => [
                'class' => 'dossierElement',
            ],
            'type'       => 'Text',
        ]);

        /**
         * Civilité
         */
        $this->add([
            'name'       => 'civilite',
            'options'    => [
                'label'         => 'Civilité <span class="text-danger">*</span>',
                'label_options' => ['disable_html_escape' => true],
            ],
            'attributes' => [
                'class' => 'dossierElement',
            ],
            'type'       => 'Select',
        ]);

        $this->get('civilite')
            ->setValueOptions(['' => '- NON RENSEIGNÉ -'] + \UnicaenApp\Util::collectionAsOptions($this->getServiceCivilite()->getList()));


        return $this;
    }



    public function getInputFilterSpecification()
    {

        $spec = [
            'nomUsuel'        => [
                'required' => false,
                'readonly' => true,
            ],
            'nomPatronymique' => [
                'required' => false,
            ],
            'prenom'          => [
                'required' => false,
            ],
            'civilite'        => [
                'required' => false,
            ],

        ];

        return $spec;
    }
}