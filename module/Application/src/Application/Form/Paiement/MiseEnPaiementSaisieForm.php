<?php

namespace Application\Form\Paiement;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\InputFilter\InputFilterProviderInterface;
use \Application\Interfaces\ServiceAPayerAwareInterface;
use \Application\Traits\ServiceAPayerAwareTrait;
use UnicaenApp\Form\Element\SearchAndSelect;
use Zend\Form\Element\Hidden;

/**
 * Description of MiseEnPaiementSaisieForm
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class
    MiseEnPaiementSaisieForm
extends
    Form
implements
    InputFilterProviderInterface,
    ServiceLocatorAwareInterface,
    ServiceAPayerAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ServiceAPayerAwareTrait;


    public function __construct($name = null, $options = [])
    {
        parent::__construct('mise-en-paiement', $options);
    }

    public function init()
    {
        $url = $this->getServiceLocator()->getServiceLocator()->get('viewhelpermanager')->get('url');
        /* @var $url Zend\View\Helper\Url */

        $this->setHydrator($this->getServiceLocator()->getServiceLocator()->get('FormMiseEnPaiementSaisieHydrator'))
              ->setAllowedObjectBindingClass('Application\Entity\Db\MiseEnPaiement');

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $this->add(array(
            'name'    => 'centre-cout',
            'options' => array(
                'label'         => 'Centre de coût',
            ),
            'type'    => 'Select',
        ));

        $this->add([
            'name'       => 'heures',
            'options'    => [
                'label' => 'Heures',
            ],
            'attributes' => [
                'title' => 'Heures',
                'class' => 'volume-horaire volume-horaire-heures input-sm',
                'step'  => 'any',
                'min'   => 0,
            ],
            'type'       => 'Text',
        ]);

        $this->add( new Hidden('formule-resultat-service') );
        $this->add( new Hidden('formule-resultat-service-referentiel') );

        $this->add(array(
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => array(
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ),
        ));
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.

     * @return array
     */
    public function getInputFilterSpecification(){
        $filters = [
            'centre-cout'                          => [ 'required' => true ],
            'formule-resultat-service'             => [ 'required' => true ],
            'formule-resultat-service-referentiel' => [ 'required' => true ],
        ];
        foreach( self::$heures as $hid => $hdata ){
            $filters[$hid] = [
                'required' => false,
            ];
        }
        return $filters;
    }
}