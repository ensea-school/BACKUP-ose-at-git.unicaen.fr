<?php

namespace Application\Form\ServiceReferentiel;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Form\Element\Hidden;

/**
 * Description of Saisie
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Saisie extends Form implements \Zend\InputFilter\InputFilterProviderInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function __construct($name = null, $options = array())
    {
        parent::__construct('service', $options);
    }

    /**
     * Bind an object to the form
     *
     * Ensures the object is populated with validated values.
     *
     * @param  \Application\Entity\Db\ServiceReferentiel $object
     * @param  int $flags
     * @return mixed|void
     * @throws Exception\InvalidArgumentException
     */
    public function bind($object, $flags = \Zend\Form\FormInterface::VALUES_NORMALIZED)
    {
        if ($object instanceof \Application\Entity\Db\Service && $object->getTypeVolumeHoraire()) {
            $this->get('type-volume-horaire')->setValue($object->getTypeVolumeHoraire()->getId());
        }
        return parent::bind($object, $flags);
    }

    public function init()
    {
        $url = $this->getServiceLocator()->getServiceLocator()->get('viewhelpermanager')->get('url');
        /* @var $url Zend\View\Helper\Url */

        $this->setHydrator($this->getServiceLocator()->getServiceLocator()->get('FormServiceReferentielSaisieHydrator'));

        $saisie = $this->getServiceLocator()->get('ServiceReferentielSaisieFieldset'); /* @var $saisie SaisieFieldset */
        //$saisie->setUseAsBaseFieldset(true);
        $this->add($saisie);

        $this->add(new Hidden('type-volume-horaire'));

        $this->add(array(
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => array(
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ),
        ));

        $this->setAttribute('action', $url(null, [], [], true));
    }

    public function initFromContext()
    {
        $this->get('service')->initFromContext();
    }

    public function saveToContext()
    {
        $this->get('service')->saveToContext();
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array();
    }
}