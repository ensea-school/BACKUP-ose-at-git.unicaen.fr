<?php

namespace Application\Form\ServiceReferentiel;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Form\Element\Csrf;
use Zend\Stdlib\Hydrator\ClassMethods;
use Application\Form\ServiceReferentiel\ServiceReferentielFieldset;
use Application\Entity\Db\IntervenantPermanent;
use Common\Exception\LogicException;

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
     * @param  null|int|string  $name    Optional name for the element
     * @param  array            $options Optional options for the element
     */
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        
        $this   ->setAttribute('method', 'post')
                ->setAttribute('class', 'service-referentiel')
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
     * Set options for a fieldset. Accepted options are:
     * - use_as_base_fieldset: is this fieldset use as the base fieldset?
     *
     * @param  array|Traversable $options
     * @return Element|ElementInterface
     * @throws Exception\InvalidArgumentException
     */
    public function setOptions($options)
    {
        parent::setOptions($options);

        if (isset($options['annee'])) {
            $this->setAnnee($options['annee']);
        }

        return $this;
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
            throw new LogicException("Intervenant spécifié invalide.");
        }
        
        return parent::bind($object, $flags);
    }
}