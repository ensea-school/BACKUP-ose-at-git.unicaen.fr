<?php

namespace Application\Form\Intervenant;

use Zend\Form\Element\Csrf;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Formulaire de saisie de la date de retour du contrat/avenant signé.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ContratRetour extends Form implements InputFilterProviderInterface
{
    use \Application\Traits\ContratAwareTrait;
    
    public function init()
    {
        $this->setHydrator(new ClassMethods(false));
        $this->setAttribute('method', 'POST');

        $contratToString = lcfirst($this->getContrat()->toString(true));
        
        $this->add(array(
            'name' => 'dateRetourSigne',
            'type'  => 'UnicaenApp\Form\Element\Date',
            'options' => array(
                'label' => "Date de retour $contratToString signé",
            ),
            'attributes' => array(
            ),
        ));
        
        $this->add(new Csrf('security'));
        
        $this->add(array(
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => array(
                'value' => "Enregistrer",
            ),
        ));

        $this->getHydrator()->addStrategy('dateRetourSigne', new \UnicaenApp\Hydrator\Strategy\DateStrategy($this->get('dateRetourSigne')));
        
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
        return array(
            'valide' => array(
                'required' => false,
            ),
        );
    }
}