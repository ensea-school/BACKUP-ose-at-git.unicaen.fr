<?php

namespace Application\Form\Intervenant\Dossier;

use Application\Form\AbstractFieldset;
use Application\Form\CustomElements\PaysSelect;
use Application\Form\Intervenant\Dossier;
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
        $civilite = new EntitySelect('civilite', [
            'label'        => 'Civilité',
            'empty_option' => "(Sélectionnez une civilité...)",
        ]);
        $civilite->getProxy()
            ->setFindMethod(['name' => 'findBy', 'params' => ['criteria' => [], 'orderBy' => ['libelleLong' => 'ASC']]])
            ->setObjectManager($this->getServiceContext()->getEntityManager())
            ->setTargetClass(\Application\Entity\Db\Civilite::class);
        $this->add($civilite);


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
        $paysSelect = new PaysSelect('paysNaissance');
        $paysSelect->setLabel("Pays de naissance");
        $paysSelect->setFranceDefault();
        $this->add($paysSelect);

        /**
         * Département de naissance
         */
        $departementSelect = new EntitySelect('departementNaissance', [
            'label'        => 'Département de naissance',
            'empty_option' => "(Sélectionnez un département...)",
        ]);
        $departementSelect->getProxy()
            ->setFindMethod(['name' => 'findBy', 'params' => ['criteria' => [], 'orderBy' => ['code' => 'ASC']]])
            ->setObjectManager($this->getServiceContext()->getEntityManager())
            ->setTargetClass(\Application\Entity\Db\Departement::class);
        $this->add($departementSelect);

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
                'required' => true,
            ],
            'nomPatronymique'      => [
                'required' => false,
            ],
            'prenom'               => [
                'required' => true,
            ],
            'civilite'             => [
                'required' => true,
            ],
            'dateNaissance'        => [
                'required'   => true,
                'validators' => [
                    new DateValidator(['format' => Constants::DATE_FORMAT]),
                ],
            ],
            'paysNaissance'        => [
                'required'   => true,
                'validators' => [
                    new PaysNaissanceValidator(['service' => $this->getServicePays()]),
                ],
            ],
            'departementNaissance' => [
                'required'   => $departementRequired,
                'validators' => [
                    new DepartementNaissanceValidator(),
                ],
            ],
            'villeNaissance'       => [
                'required' => true,
            ],

        ];

        return $spec;
    }
}