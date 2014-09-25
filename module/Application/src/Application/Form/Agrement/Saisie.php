<?php

namespace Application\Form\Agrement;

use Zend\Form\Element\Csrf;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use UnicaenApp\Hydrator\Strategy\DateStrategy;

/**
 * Formulaire de saisie d'un agrément.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Saisie extends Form implements InputFilterProviderInterface
{
    use \Application\Traits\IntervenantAwareTrait;
    use \Application\Traits\TypeAgrementAwareTrait;
    
    public function init()
    {
        $this->setHydrator(new ClassMethods(false));
        
        $this->setAttribute('method', 'POST');
        
        $this->add(array(
            'name' => 'dateDecision',
            'type'  => 'UnicaenApp\Form\Element\Date',
            'options' => array(
                'label' => "Date de la décision",
            ),
            'attributes' => array(
                'id' => uniqid('dateDecision'),
            ),
        ));
        $this->getHydrator()->addStrategy('dateDecision', new DateStrategy($this->get('dateDecision')));
        
        $this->add(new Csrf('security'));
        
        $this->add(array(
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => array(
                'value' => "Enregistrer",
            ),
        ));

        return $this;
    }
    
    /**
     * 
     * @param \Application\Entity\Db\Intervenant[] $intervenants
     * @return self
     */
    public function setIntervenants(array $intervenants)
    {
        if (!$this->has($name = "intervenants")) {
            $mcb = new \Zend\Form\Element\MultiCheckbox($name);
            $this->add($mcb);
        }
        
        $options = [];
        foreach ($intervenants as $intervenant) {
            $options[$intervenant->getId()] = (string)$intervenant;
        }
        $this->get($name)
                ->setValueOptions($options)
                ->setValue(array_keys($options));
        
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
            'dateDecision' => [
                'required'   => true,
            ],
            'intervenants' => [
                'required'   => $this->has('intervenants'),
            ],
        );
    }
}