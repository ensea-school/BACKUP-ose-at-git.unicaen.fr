<?php

namespace Dossier\Form;

use Application\Constants;
use Application\Form\AbstractFieldset;
use Application\Service\Traits\ContextServiceAwareTrait;
use Dossier\Validator\DepartementNaissanceValidator;
use Dossier\Validator\PaysNaissanceValidator;
use Intervenant\Service\CiviliteServiceAwareTrait;
use Intervenant\Service\StatutServiceAwareTrait;
use Laminas\Validator\Date as DateValidator;
use Lieu\Service\DepartementServiceAwareTrait;
use Lieu\Service\PaysServiceAwareTrait;

/**
 * Description of DossierIdentiteComplementaireFieldset
 *
 */
class DossierIdentiteComplementaireFieldset extends AbstractFieldset
{
    use ContextServiceAwareTrait;
    use StatutServiceAwareTrait;
    use PaysServiceAwareTrait;
    use DepartementServiceAwareTrait;
    use CiviliteServiceAwareTrait;

    static private $franceId;



    public function init()
    {
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

        /**
         * Pays de naissance
         */
        $this->add([
            'name'       => 'paysNaissance',
            'options'    => [
                'label'         => 'Pays de naissance <span class="text-danger">*</span>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],],
            'attributes' => [
                'class'            => 'selectpicker dossierElement',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

        $this->get('paysNaissance')
            ->setValueOptions(['' => '(Sélectionnez un pays)'] + \UnicaenApp\Util::collectionAsOptions($this->getServicePays()->getList()));

        /**
         * Pays nationalité
         */
        $this->add([
            'name'       => 'paysNationalite',
            'options'    => [
                'label'         => 'Pays de Nationalité <span class="text-danger">*</span>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],],
            'attributes' => [
                'class'            => 'selectpicker dossierElement',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);


        $this->get('paysNationalite')
            ->setValueOptions(['' => '(Sélectionnez une nationalité)'] + \UnicaenApp\Util::collectionAsOptions($this->getServicePays()->getListValide()));


        /**
         * Département de naissance
         */
        $this->add([
            'name'       => 'departementNaissance',
            'options'    => [
                'label'         => 'Département de naissance <span class="text-danger">*</span>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],],
            'attributes' => [
                'info_icon'        => "Uniquement si votre pays de naissance est la France.",
                'class'            => 'selectpicker dossierElement',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);


        $this->get('departementNaissance')
            ->setValueOptions(['' => '(Sélectionnez un département)'] + \UnicaenApp\Util::collectionAsOptions($this->getServiceDepartement()->getList()));

        /**
         * Ville de naissance
         */
        $this->add([
            'name'       => 'villeNaissance',
            'options'    => [
                'label'         => 'Ville de naissance <span class="text-danger">*</span>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],],
            'attributes' => [
                'class' => 'dossierElement',
            ],
            'type'       => 'Text',
        ]);


        return $this;
    }



    public function getInputFilterSpecification()
    {
        $paysNaissanceId = (int)$this->get('paysNaissance')->getValue();

        // la sélection du département n'est obligatoire que si le pays sélectionné est la France
        $departementRequired = (self::$franceId === $paysNaissanceId);
        $spec                = [];

        $spec = [
            'dateNaissance'        => [
                'required'    => false,
                'allow_empty' => true,
                'validators'  => [
                    new DateValidator(),
                ],
            ],
            'paysNaissance'        => [
                'required'    => false,
                'allow_empty' => true,
                'validators'  => [
                    new PaysNaissanceValidator(['service' => $this->getServicePays()]),
                ],
            ],
            'paysNationalite'      => [
                'required'    => false,
                'allow_empty' => true,
            ],
            'departementNaissance' => [
                'required'    => false,
                'allow_empty' => true,
                'validators'  => [
                    new DepartementNaissanceValidator(),
                ],
            ],
            'villeNaissance'       => [
                'required' => false,
            ],

        ];

        return $spec;
    }
}