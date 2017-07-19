<?php

namespace Application\Form\ServiceReferentiel;

use Application\Entity\Db\Service;
use Application\Form\AbstractForm;
use Application\Form\ServiceReferentiel\Traits\SaisieFieldsetAwareTrait;
use Zend\Form\FormInterface;
use Zend\Form\Element\Hidden;
use Zend\Stdlib\Hydrator\HydratorInterface;


/**
 * Description of Saisie
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Saisie extends AbstractForm
{
    use SaisieFieldsetAwareTrait;

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
        $hydrator = new SaisieHydrator();
        $this->setHydrator($hydrator);

        $this->setAttribute('class', 'service-referentiel-form');

        $this->add($this->getFieldsetServiceReferentielSaisie());

        $this->add(new Hidden('type-volume-horaire'));

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ],
        ]);

        $this->setAttribute('action', $this->getCurrentUrl());
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




/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class SaisieHydrator implements HydratorInterface
{
    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  \Application\Entity\Db\ServiceReferentiel $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object = $data['service'];

        return $object;
    }

    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Db\ServiceReferentiel $object
     * @return array
     */
    public function extract($object)
    {
        $data = [];
        $data['service'] = $object;

        return $data;
    }
}