<?php

namespace Application\Form\Intervenant;

use Zend\Form\Element\Csrf;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Validator\NotEmpty;
use UnicaenApp\Hydrator\Strategy\DateStrategy;
use Application\Rule\Intervenant\NecessitePassageCommissionRechercheRule;

/**
 * Formulaire de validation des données personnelles d'un intervenant vacataire non-BIATSS.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class DossierValidation extends Form implements InputFilterProviderInterface
{
    private $intervenant;
    
    public function setIntervenant($intervenant)
    {
        $this->intervenant = $intervenant;
        return $this;
    }
        
    private $ruleDateCommission;
    
    public function getRuleDateCommission()
    {
        if (null === $this->ruleDateCommission) {
            $this->ruleDateCommission = new NecessitePassageCommissionRechercheRule($this->intervenant);
        }
        return $this->ruleDateCommission;
    }
    
    public function init()
    {
        $this->setAttribute('method', 'POST');
        $this->add(array(
            'name' => 'valide',
            'type'  => 'Checkbox',
            'options' => array(
                'label' => "Cochez pour valider les données personnelles",
            ),
            'attributes' => array(
            ),
        ));
        
        $dateRequired = $this->getRuleDateCommission()->execute();
        $this->add(array(
            'name' => 'dateCommission',
            'type'  => 'UnicaenApp\Form\Element\Date',
            'options' => array(
                'label' => "Date de passage en commission",
            ),
            'attributes' => array(
                'disabled' => !$dateRequired,
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

        $h = new ClassMethods(false);
        $h->addStrategy('dateCommission', new DateStrategy($this->get('dateCommission')));
        $this->setHydrator($h);
        
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
        $passageCR    = $this->getRuleDateCommission();
        $dateRequired = $passageCR->isRelevant() && $passageCR->execute();

        // la date ne peut être obligatoire que si le dossier est complet
        if (!$this->get('valide')->getValue()) {
            $dateRequired = false;
        }
        
        $validatorsDate = array();
        if ($dateRequired) {
            $validatorsDate[] = array(
                'name' => 'NotEmpty',
                'options' => array(
                    'messages' => array(
                        NotEmpty::IS_EMPTY => $passageCR->getMessage(),
                    ),
                ),
            );
        }

        return array(
            'valide' => array(
                'required' => false,
            ),
            'dateCommission' => array(
                'required' => $dateRequired,
                'validators' => $validatorsDate,
            ),
        );
    }
}