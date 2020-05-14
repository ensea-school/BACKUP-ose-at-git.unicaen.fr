<?php

namespace Application\Form\Intervenant\Dossier;

use Application\Entity\Db\Dossier as Dossier;
use Application\Entity\Db\StatutIntervenant;
use Application\Form\AbstractFieldset;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\PaysServiceAwareTrait;
use Application\Service\Traits\StatutIntervenantServiceAwareTrait;
use Application\Validator\DepartementNaissanceValidator;
use Application\Validator\NumeroINSEEValidator;
use Application\Validator\PaysNaissanceValidator;
use Application\Constants;
use Application\Validator\RIBValidator;
use DoctrineModule\Form\Element\Proxy;
use DoctrineORMModule\Form\Element\EntitySelect;
use Zend\Validator\Date as DateValidator;

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

        $this
            ->setObject(new Dossier())
            ->addElements();
        //->setHydrator($hydrator)
    }



    /**
     * @return self
     */
    private function addElements()
    {


        /**
         * Adresse postale
         */
        $this->add([
            'name'       => 'adresse',
            'options'    => [
                'label'         => 'Adresse postale <em>en France</em>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'rows' => 5,
            ],
            'type'       => 'Textarea',
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
        $numeroInseeProvisoire = (bool)$this->get('numeroInseeEstProvisoire')->getValue();

        // la sélection du département n'est obligatoire que si le pays sélectionné est la France
        $departementRequired = (self::$franceId === $paysNaissanceId);

        $spec = [

            'adresse'              => [
                'required' => true,
            ],

        ];

        return $spec;
    }




}