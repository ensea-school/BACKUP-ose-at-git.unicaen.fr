<?php

namespace Application\Form\Intervenant;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Entity\Db\IntervenantPermanent;

/**
 * Formulaire de modification de service dû d'un intervenant.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ModificationServiceDuForm extends Form implements ServiceLocatorAwareInterface, ContextProviderAwareInterface, \UnicaenApp\Service\EntityManagerAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;
    use \UnicaenApp\Service\EntityManagerAwareTrait;

    /**
     * @param  null|int|string  $name    Optional name for the element
     * @param  array            $options Optional options for the element
     */
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        
        $this   ->setAttribute('method', 'post')
                ->setAttribute('class', 'modification-service-du')
                ->setHydrator(new \Zend\Stdlib\Hydrator\ClassMethods(false))
                ->setInputFilter(new \Zend\InputFilter\InputFilter())
//                ->setPreferFormInputFilter(false)
         ;
    }
    
    /**
     * This function is automatically called when creating element with factory. It
     * allows to perform various operations (add elements...)
     */
    public function init()
    {
        $fs = $this->getServiceLocator()->get("IntervenantModificationServiceDuFieldset"); /* @var $fs ModificationServiceDuFieldset */
        $fs->setUseAsBaseFieldset(true);
        $this->add($fs, array('name' => 'fs'));
        
        $this->add(array(
            'type' => 'Button',
            'name' => 'ajouter',
            'options' => array(
                'label' => '<span class="glyphicon glyphicon-plus"></span> Ajouter',
                'label_options' => array(
                    'disable_html_escape' => true,
                ),
            ),
            'attributes' => array(
                'title' => "Ajouter une modification de service dû",
                'class' => 'modification-service-du modification-service-du-ajouter btn btn-default btn-xs'
            ),
        ));
         
        /**
         * Csrf
         */
        $this->add(new \Zend\Form\Element\Csrf('security'));
        
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
     * @param  \Application\Entity\Db\IntervenantPermanent $object
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
