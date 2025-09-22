<?php

namespace Dossier\Form;

use Application\Form\AbstractFieldset;
use Application\Service\Traits\ContextServiceAwareTrait;
use Intervenant\Service\CiviliteServiceAwareTrait;
use Intervenant\Service\SituationMatrimonialeServiceAwareTrait;
use Intervenant\Service\StatutServiceAwareTrait;
use Laminas\Validator\Date as DateValidator;
use Lieu\Service\DepartementServiceAwareTrait;
use Lieu\Service\PaysServiceAwareTrait;
use UnicaenApp\Util;

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
    use SituationMatrimonialeServiceAwareTrait;


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

        $valuesCivilite =   Util::collectionAsOptions($this->getServiceCivilite()->getList());
        $this->get('civilite')->setValueOptions($valuesCivilite);
        $this->get('civilite')->setEmptyOption('- NON RENSEIGNÉE -');


        /**
         * Date de naissance
         */
        $this->add([
                       'name'       => 'dateNaissance',
                       'options'    => [
                           'label'         => 'Date de naissance <span class="text-danger">*</span>',
                           'label_options' => [
                               'disable_html_escape' => true,
                           ],
                       ],
                       'attributes' => [
                           'placeholder' => "jj/mm/aaaa",
                           'class'       => 'dossierElement',

                       ],
                       'type'       => 'Date',
                   ]);



        return $this;
    }



    public function getInputFilterSpecification(): array
    {

        return [
            'nomUsuel'                  => [
                'required' => false,
                'readonly' => true,
            ],
            'nomPatronymique'           => [
                'required' => false,
            ],
            'prenom'                    => [
                'required' => false,
            ],
            'civilite'                  => [
                'required' => false,
            ],
            'dateNaissance'        => [
                'required'    => false,
                'allow_empty' => true,
                'validators'  => [
                    new DateValidator(),
                ],
            ]
        ];


    }
}
