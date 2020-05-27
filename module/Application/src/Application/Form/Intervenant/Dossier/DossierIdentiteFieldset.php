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
 * Description of DossierFieldset
 *
 */
class DossierIdentiteFieldset extends AbstractFieldset
{
    use ContextServiceAwareTrait;
    use StatutIntervenantServiceAwareTrait;
    use PaysServiceAwareTrait;
    use DepartementServiceAwareTrait;
    use CiviliteServiceAwareTrait;

    static private $franceId;



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
            'name'    => 'nomUsuel',
            'options' => [
                'label' => 'Nom usuel',
            ],
            'type'    => 'Text',
        ]);

        /**
         * Nom patro
         */
        $this->add([
            'name'    => 'nomPatronymique',
            'options' => [
                'label' => 'Nom de naissance',
            ],
            'type'    => 'Text',
        ]);

        /**
         * Prénom
         */
        $this->add([
            'name'    => 'prenom',
            'options' => [
                'label' => 'Prénom',
            ],
            'type'    => 'Text',
        ]);

        /**
         * Civilité
         */
        $this->add([
            'name'       => 'civilite',
            'options'    => [
                'label' => 'Civilité',
            ],
            'attributes' => [
            ],
            'type'       => 'Select',
        ]);

        $this->get('civilite')
            ->setValueOptions(['' => '(Sélectionnez une civilité...)'] + \UnicaenApp\Util::collectionAsOptions($this->getServiceCivilite()->getList()));

        /**
         * Date de naissance
         */
        $this->add([
            'name'       => 'dateNaissance',
            'options'    => [
                'label'         => 'Date de naissance',
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
                'label' => 'Pays de naissance',
            ],
            'attributes' => [
            ],
            'type'       => 'Select',
        ]);

        $this->get('paysNaissance')
            ->setValueOptions(['' => 'Sélectionnez un pays...'] + \UnicaenApp\Util::collectionAsOptions($this->getServicePays()->getList()));

        /**
         * Département de naissance
         */
        /**
         * Pays de naissance
         */
        $this->add([
            'name'       => 'departementNaissance',
            'options'    => [
                'label' => 'Département de naissance',
            ],
            'attributes' => [
            ],
            'type'       => 'Select',
        ]);

        $this->get('departementNaissance')
            ->setValueOptions(['' => 'Sélectionnez un département...'] + \UnicaenApp\Util::collectionAsOptions($this->getServiceDepartement()->getList()));

        /*$departementSelect = new EntitySelect('departementNaissance', [
            'label'        => 'Département de naissance',
            'empty_option' => "(Sélectionnez un département...)",
        ]);*/

        /*$departementSelect->getProxy()
            ->setFindMethod(['name' => 'findBy', 'params' => ['criteria' => [], 'orderBy' => ['code' => 'ASC']]])
            ->setObjectManager($this->getServiceContext()->getEntityManager())
            ->setTargetClass(\Application\Entity\Db\Departement::class);
        $this->add($departementSelect);*/

        /**
         * Ville de naissance
         */
        $this->add([
            'name'    => 'villeNaissance',
            'options' => [
                'label' => 'Ville de naissance',
            ],
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
        $paysNaissanceId       = (int)$this->get('paysNaissance')->getValue();

        // la sélection du département n'est obligatoire que si le pays sélectionné est la France
        $departementRequired = (self::$franceId === $paysNaissanceId);

        $spec = [
            'nomUsuel'             => [
                'required' => false,
                'readonly' => true
            ],
            'nomPatronymique'      => [
                'required' => false,
            ],
            'prenom'               => [
                'required' => false,
            ],
            'civilite'             => [
                'required' => false,
            ],
            'dateNaissance'        => [
                'required'   => false,
                'allow_empty' => true,
                'validators' => [
                    new DateValidator(['format' => Constants::DATE_FORMAT]),
                ],
            ],
            'paysNaissance'        => [
                'required'   => false,
                'allow_empty' => true,
                'validators' => [
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