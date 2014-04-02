<?php

namespace Application\Form\Service;

use Zend\Form\Form;
use UnicaenApp\Form\Element\SearchAndSelect;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\InputFilter\InputFilter;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Hidden;
use Zend\Mvc\Controller\Plugin\Url;

/**
 * Description of Saisie
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Saisie extends Form
{

    public function __construct( Url $url, array $context=array() )
    {
        parent::__construct('service');

        $this   ->setAttribute('method', 'post')
                ->setAttribute('class', 'service')
                ->setHydrator(new ClassMethods(false))
                ->setInputFilter(new InputFilter())
         ;

        $id = new Hidden('id');
        $this->add($id);

        if (! isset($context['intervenant'])){
            $intervenant = new SearchAndSelect('intervenant');
            $intervenant ->setRequired(true)
                         ->setSelectionRequired(true)
                         ->setAutocompleteSource(
                            $url->fromRoute('recherche', array('action' => 'intervenantFind'))
                         )
                         ->setLabel("Intervenant :")
                         ->setAttributes(array('title' => "Saisissez le nom suivi éventuellement du prénom (2 lettres au moins)"));
            $this->add($intervenant);
        }

        $elementPedagogique = new SearchAndSelect('elementPedagogique');
        $elementPedagogique->setLabel("Elément pédagogique :")
                ->setAttributes(array('title' => "Saisissez 2 lettres au moins"))
                ->setAutocompleteSource(
                    $url->fromRoute('of/default', array('action' => 'search-of') )
                );
        $this->add($elementPedagogique);

        $etablissement = new SearchAndSelect('etablissement');
        $etablissement ->setRequired(true)
                       ->setSelectionRequired(true)
                       ->setAutocompleteSource(
                           $url->fromRoute('etablissement/recherche')
                       )
                       ->setLabel("Etablissement :")
                       ->setAttributes(array('title' => "Saisissez le libellé (2 lettres au moins)"));
        $this->add($etablissement);

        /**
         * Csrf
         */
        $this->add(new Csrf('security'));

        /**
         * Submit
         */
        $this->add(array(
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => array(
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ),
        ));

        $this->setAttribute('action', $url->fromRoute(null, array(), array(), true));
    }

    /**
     * Bind an object to the form
     *
     * Ensures the object is populated with validated values.
     *
     * @param  object $object
     * @param  int $flags
     * @return mixed|void
     * @throws Exception\InvalidArgumentException
     */
    public function bind($object, $flags = \Zend\Form\FormInterface::VALUES_NORMALIZED)
    {
        return parent::bind($object, $flags);
    }
}