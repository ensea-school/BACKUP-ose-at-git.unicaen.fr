<?php

namespace Application\Form\Intervenant;

use Zend\Form\Element\Csrf;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Validator\NotEmpty;
use UnicaenApp\Hydrator\Strategy\DateStrategy;
use Application\Rule\Intervenant\NecessitePassageCommissionRechercheRule;
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
        
        $dateCommissionRechercheRequired = $this->getRuleDateCommissionRecherche()->execute();
        if ($dateCommissionRechercheRequired) {
            $this->add(array(
                'name' => 'dateCommissionRecherche',
                'type'  => 'UnicaenApp\Form\Element\Date',
                'options' => array(
                    'label' => "Date de passage en Commission de la Recherche",
                ),
                'attributes' => array(
                    'id' => uniqid('dateCommissionRecherche'),
                    'disabled' => !$dateCommissionRechercheRequired,
                ),
            ));
            $this->getHydrator()->addStrategy('dateCommissionRecherche', new DateStrategy($this->get('dateCommissionRecherche')));
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
    
    public function bind($object, $flags = \Zend\Form\FormInterface::VALUES_NORMALIZED) 
    {
        parent::bind($object, $flags);
        
        if ($object->getId()) {
            if ($this->has($name = 'dateConseilRestreint')) {
                $this->remove($name);
            }
            if ($this->has($name = 'dateCommissionRecherche')) {
                $this->remove($name);
            }
            $this->get('valide')->setLabel("Décochez pour dévalider les enseignements")->setValue(true);
        }
        
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
        $passageCommissionRechercheRule  = $this->getRuleDateCommissionRecherche();
        $passageConseilRestreintRule     = $this->getRuleDateConseilRestreint();
        
        $dateCommissionRechercheRequired = $passageCommissionRechercheRule->isRelevant() && $passageCommissionRechercheRule->execute();
        $dateConseilRestreintRequired    = $passageConseilRestreintRule->isRelevant()    && $passageConseilRestreintRule->execute();

        // la saisie d'une date ne peut être obligatoire que si la case validation est cochée
        if (!$this->get('valide')->getValue()) {
            $dateCommissionRechercheRequired = false;
            $dateConseilRestreintRequired    = false;
        }
        
        $validatorsDateCommissionRecherche = array();
        if ($dateCommissionRechercheRequired) {
            $validatorsDateCommissionRecherche[] = array(
                'name' => 'NotEmpty',
                'options' => array(
                    'messages' => array(
                        NotEmpty::IS_EMPTY => $passageCommissionRechercheRule->getMessage(),
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
                'required' => false,
            ),
            'dateCommissionRecherche' => array(
                'required'   => $dateCommissionRechercheRequired,
                'validators' => $validatorsDateCommissionRecherche,
            ),
            'dateConseilRestreint' => array(
                'required'   => $dateConseilRestreintRequired,
                'validators' => $validatorsDateConseilRestreint,
            ),
        );
    }
         
    /**
     * @var NecessitePassageCommissionRechercheRule 
     */
    private $ruleDateCommissionRecherche;
    
    /**
     * @return NecessitePassageCommissionRechercheRule
     */
    public function getRuleDateCommissionRecherche()
    {
        if (null === $this->ruleDateCommissionRecherche) {
            $this->ruleDateCommissionRecherche = new NecessitePassageCommissionRechercheRule($this->getIntervenant());
        }
        return $this->ruleDateCommissionRecherche;
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