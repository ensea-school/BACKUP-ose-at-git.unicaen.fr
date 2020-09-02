<?php

namespace Application\Form\Intervenant\Dossier;

use Application\Form\AbstractFieldset;
use Application\Form\CustomElements\PaysSelect;
use Application\Form\Intervenant\Dossier;
use Application\Form\Intervenant\IntervenantDossier;
use Application\Service\Traits\CiviliteServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\DepartementServiceAwareTrait;
use Application\Service\Traits\PaysServiceAwareTrait;
use Application\Service\Traits\StatutIntervenantServiceAwareTrait;
use Application\Validator\DepartementNaissanceValidator;
use Application\Validator\PaysNaissanceValidator;
use Application\Constants;
use DoctrineORMModule\Form\Element\EntitySelect;
use Zend\Validator\Date as DateValidator;

/**
 * Description of DossierIdentiteComplementaireFieldset
 *
 */
class DossierIdentiteComplementaireFieldset extends AbstractFieldset
{
    use ContextServiceAwareTrait;
    use StatutIntervenantServiceAwareTrait;
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
            ],
            'type'       => 'UnicaenApp\Form\Element\Date',
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
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);


        $this->get('paysNaissance')
            ->setValueOptions(['' => '- NON RENSEIGNÉ -'] + \UnicaenApp\Util::collectionAsOptions($this->getServicePays()->getList()));

        //Set France par défault si INSEE France
        /* $idFrance = $this->getServicePays()->getIdByLibelle('FRANCE');
         if ($idFrance) {
             $this->get('paysNaissance')->setValue($idFrance);
         }*/


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
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);


        $this->get('departementNaissance')
            ->setValueOptions(['' => '- NON RENSEIGNÉ -'] + \UnicaenApp\Util::collectionAsOptions($this->getServiceDepartement()->getList()));

        /**
         * Ville de naissance
         */
        $this->add([
            'name'    => 'villeNaissance',
            'options' => [
                'label'         => 'Ville de naissance <span class="text-danger">*</span>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],],
            'type'    => 'Text',
        ]);


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
        $paysNaissanceId = (int)$this->get('paysNaissance')->getValue();

        // la sélection du département n'est obligatoire que si le pays sélectionné est la France
        $departementRequired = (self::$franceId === $paysNaissanceId);
        $spec                = [];

        $spec = [
            'dateNaissance'        => [
                'required'    => false,
                'allow_empty' => true,
                'validators'  => [
                    new DateValidator(['format' => Constants::DATE_FORMAT]),
                ],
            ],
            'paysNaissance'        => [
                'required'    => false,
                'allow_empty' => true,
                'validators'  => [
                    new PaysNaissanceValidator(['service' => $this->getServicePays()]),
                ],
            ],
            'departementNaissance' => [
                'required'   => false,//$departementRequired,
                'validators' => [
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