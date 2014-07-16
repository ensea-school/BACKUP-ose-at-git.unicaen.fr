<?php

namespace Application\Form\Intervenant;

use Zend\Form\Element\Csrf;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Validator\NotEmpty;
use UnicaenApp\Hydrator\Strategy\DateStrategy;
use Application\Rule\Intervenant\NecessitePassageConseilAcademiqueRule;
use Application\Rule\Intervenant\NecessitePassageConseilRestreintRule;

/**
 * Formulaire de validation des enseignements d'un intervenant.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ServiceValidation extends Form implements InputFilterProviderInterface
{
    use \Application\Traits\IntervenantAwareTrait;
    
    public function init()
    {
        $this->setHydrator(new ClassMethods(false));
        
        $this->setAttribute('method', 'POST');
        $this->add(array(
            'name' => 'valide',
            'type'  => 'Checkbox',
            'options' => array(
                'label' => "Cochez pour valider les enseignements",
                'use_hidden_element' => false,
                'checked_value' => 1,
                'unchecked_value' => 0
            ),
            'attributes' => array(
            ),
        ));
        
        $dateConseilRestreintRequired = $this->getRuleDateConseilRestreint()->execute();
        if ($dateConseilRestreintRequired) {
            $this->add(array(
                'name' => 'dateConseilRestreint',
                'type'  => 'UnicaenApp\Form\Element\Date',
                'options' => array(
                    'label' => "Date de passage en Conseil Restreint de la composante",
                ),
                'attributes' => array(
                    'id' => uniqid('dateConseilRestreint'),
                    'disabled' => !$dateConseilRestreintRequired,
                ),
            ));
            $this->getHydrator()->addStrategy('dateConseilRestreint', new DateStrategy($this->get('dateConseilRestreint')));
        }
        
        $dateConseilAcademiqueRequired = $this->getRuleDateConseilAcademique()->execute();
        if ($dateConseilAcademiqueRequired) {
            $this->add(array(
                'name' => 'dateConseilAcademique',
                'type'  => 'UnicaenApp\Form\Element\Date',
                'options' => array(
                    'label' => "Date de passage en Conseil Académique",
                ),
                'attributes' => array(
                    'id' => uniqid('dateConseilAcademique'),
                    'disabled' => !$dateConseilAcademiqueRequired,
                ),
            ));
            $this->getHydrator()->addStrategy('dateConseilAcademique', new DateStrategy($this->get('dateConseilAcademique')));
        }
        
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
    
//    public function bind($object, $flags = \Zend\Form\FormInterface::VALUES_NORMALIZED) 
//    {
//        parent::bind($object, $flags);
//        
//        if ($object->getId()) {
//            if ($this->has($name = 'dateConseilRestreint')) {
//                $this->remove($name);
//            }
//            if ($this->has($name = 'dateConseilAcademique')) {
//                $this->remove($name);
//            }
//            $this->get('valide')->setLabel("Décochez pour dévalider les enseignements")->setValue(true);
//        }
//        
//        return $this;
//    }
    
    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        $passageConseilAcademiqueRule  = $this->getRuleDateConseilAcademique();
        $passageConseilRestreintRule     = $this->getRuleDateConseilRestreint();
        
        $dateConseilAcademiqueRequired = $passageConseilAcademiqueRule->isRelevant() && $passageConseilAcademiqueRule->execute();
        $dateConseilRestreintRequired    = $passageConseilRestreintRule->isRelevant()    && $passageConseilRestreintRule->execute();

        // la saisie d'une date ne peut être obligatoire que si la case validation est cochée
        if (!$this->get('valide')->getValue()) {
            $dateConseilAcademiqueRequired = false;
            $dateConseilRestreintRequired    = false;
        }
        
        $validatorsDateConseilAcademique = array();
        if ($dateConseilAcademiqueRequired) {
            $validatorsDateConseilAcademique[] = array(
                'name' => 'NotEmpty',
                'options' => array(
                    'messages' => array(
                        NotEmpty::IS_EMPTY => $passageConseilAcademiqueRule->getMessage(),
                    ),
                ),
            );
        }
        
        $validatorsDateConseilRestreint = array();
        if ($dateConseilRestreintRequired) {
            $validatorsDateConseilRestreint[] = array(
                'name' => 'NotEmpty',
                'options' => array(
                    'messages' => array(
                        NotEmpty::IS_EMPTY => $passageConseilRestreintRule->getMessage(),
                    ),
                ),
            );
        }

        return array(
            'valide' => array(
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                NotEmpty::IS_EMPTY => "Vous devez cocher la case pour valider",
                            ),
                        ),
                    ),
                ),
            ),
            'dateConseilAcademique' => array(
                'required'   => $dateConseilAcademiqueRequired,
                'validators' => $validatorsDateConseilAcademique,
            ),
            'dateConseilRestreint' => array(
                'required'   => $dateConseilRestreintRequired,
                'validators' => $validatorsDateConseilRestreint,
            ),
        );
    }
         
    /**
     * @var NecessitePassageConseilAcademiqueRule 
     */
    private $ruleDateConseilAcademique;
    
    /**
     * @return NecessitePassageConseilAcademiqueRule
     */
    public function getRuleDateConseilAcademique()
    {
        if (null === $this->ruleDateConseilAcademique) {
            $this->ruleDateConseilAcademique = new NecessitePassageConseilAcademiqueRule($this->getIntervenant());
        }
        return $this->ruleDateConseilAcademique;
    }
        
    /**
     * @var NecessitePassageConseilRestreintRule 
     */
    private $ruleDateConseilRestreint;
    
    /**
     * @return NecessitePassageConseilRestreintRule
     */
    public function getRuleDateConseilRestreint()
    {
        if (null === $this->ruleDateConseilRestreint) {
            $this->ruleDateConseilRestreint = new NecessitePassageConseilRestreintRule($this->getIntervenant());
        }
        return $this->ruleDateConseilRestreint;
    }
}