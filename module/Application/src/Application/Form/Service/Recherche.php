<?php

namespace Application\Form\Service;

use Zend\Form\Form;
use UnicaenApp\Form\Element\SearchAndSelect;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Form\OffreFormation\ElementPedagogiqueRechercheFieldset;


/**
 * Description of Recherche
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Recherche extends Form implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;


    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $this   ->setAttribute('method', 'post')
                ->setAttribute('class', 'service-recherche')
         ;

        $intervenant = new SearchAndSelect('intervenant');
        $intervenant ->setRequired(false)
                     ->setSelectionRequired(true)
                     ->setLabel("Intervenant :")
                     ->setAttributes(array('title' => "Saisissez le nom suivi éventuellement du prénom (2 lettres au moins)"));
        $this->add($intervenant);

        $element = new ElementPedagogiqueRechercheFieldset('elementPedagogique');
        $this->add($element);

        /**
         * Submit
         */
        $this->add(array(
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => array(
                'value' => 'Filtrer',
                'class' => 'btn btn-primary',
            ),
        ));
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
            'intervenant' => array(
                'required' => false
            ),
            'elementPedagogique' => array(
                'required' => false,
            ),
        );
    }
}