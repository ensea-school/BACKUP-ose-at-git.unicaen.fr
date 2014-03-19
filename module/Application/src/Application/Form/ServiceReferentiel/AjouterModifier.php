<?php

namespace Application\Form\ServiceReferentiel;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Form\Element\Csrf;
use Zend\Stdlib\Hydrator\ClassMethods;
use Application\Form\ServiceReferentiel\ServiceReferentielFieldset;
use Application\Entity\Db\IntervenantPermanent;

/**
 * Description of AjouterModifier
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 * @see ServiceReferentielFieldset
 * @see FonctionServiceReferentielFieldset
 */
class AjouterModifier extends Form
{
    /**
     * @var \Application\Entity\Db\Annee
     */
    protected $annee;
    
    /**
     * 
     */
    public function __construct()
    {
        parent::__construct();
        
        $this   ->setAttribute('method', 'post')
                ->setAttribute('class', 'service-referentiel')
//                ->setObject(new IntervenantPermanent())
                ->setHydrator(new ClassMethods(false))
                ->setInputFilter(new InputFilter())
//                ->setPreferFormInputFilter(false)
         ;
        
        $fsIntervenant = new ServiceReferentielFieldset('intervenant');
        $fsIntervenant->setUseAsBaseFieldset(true);
        $this->add($fsIntervenant);
        
        $this->add(array(
            'type' => 'Button',
            'name' => 'ajouter',
            'options' => array(
                'label' => 'Ajouter',
            ),
            'attributes' => array(
                'title' => "Ajouter une fonction",
                'class' => 'fonction-referentiel fonction-referentiel-ajouter btn btn-default btn-xs'
            ),
        ));
         
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
            ),
        ));
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
        if (!$object instanceof IntervenantPermanent) {
            throw new \Common\Exception\LogicException("Intervenant spécifié invalide.");
        }
        
        return parent::bind($object, $flags);
    }
    
    /**
     * 
     * @param \Application\Entity\Db\Annee $annee
     * @return \Application\Form\ServiceReferentiel\AjouterModifier
     */
    public function setAnnee(\Application\Entity\Db\Annee $annee)
    {
        $this->annee = $annee;
        
        return $this;
    }
}