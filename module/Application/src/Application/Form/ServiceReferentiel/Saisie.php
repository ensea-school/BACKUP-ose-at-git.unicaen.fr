<?php

namespace Application\Form\ServiceReferentiel;

use Application\Entity\Db\Service;
use Zend\Form\Form;
use Zend\Form\FormInterface;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Form\Element\Hidden;

/**
 * Description of Saisie
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Saisie extends Form implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function __construct($name = null, $options = [])
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
     */
    public function bind($object, $flags = FormInterface::VALUES_NORMALIZED)
    {
        if ($object instanceof Service && $object->getTypeVolumeHoraire()) {
            $this->get('type-volume-horaire')->setValue($object->getTypeVolumeHoraire()->getId());
        }
        return parent::bind($object, $flags);
    }

    public function init()
    {
        $url = $this->getServiceLocator()->getServiceLocator()->get('viewhelpermanager')->get('url');
        /* @var $url \Zend\View\Helper\Url */

        $this->setHydrator($this->getServiceLocator()->getServiceLocator()->get('FormServiceReferentielSaisieHydrator'));

        $saisie = $this->getServiceLocator()->get('ServiceReferentielSaisieFieldset'); /* @var $saisie SaisieFieldset */
        //$saisie->setUseAsBaseFieldset(true);
        $this->add($saisie);

        $this->add(new Hidden('type-volume-horaire'));

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ],
        ]);

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
        return [];
    }
}