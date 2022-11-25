<?php

namespace Referentiel\Form;

use Enseignement\Entity\Db\Service;
use Application\Form\AbstractForm;
use Referentiel\Form\SaisieFieldsetAwareTrait;
use Referentiel\Service\FonctionReferentielServiceAwareTrait;
use Laminas\Form\FormInterface;
use Laminas\Form\Element\Hidden;
use Laminas\Hydrator\HydratorInterface;


/**
 * Description of Saisie
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Saisie extends AbstractForm
{
    use SaisieFieldsetAwareTrait;
    use FonctionReferentielServiceAwareTrait;


    public function __construct($name = null, $options = [])
    {
        parent::__construct('service', $options);
    }



    /**
     * Bind an object to the form
     *
     * Ensures the object is populated with validated values.
     *
     * @param \Referentiel\Entity\Db\ServiceReferentiel $object
     * @param int                                       $flags
     *
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

        $fieldset = $this->getFieldsetServiceReferentielSaisie();
        $this->setAttribute('data-fonctions', json_encode($this->makeDataFromFonctions()));

        $this->setAttribute('class', 'service-referentiel-form');

        $this->add($fieldset);

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
     * @return array
     */
    protected function makeDataFromFonctions()
    {
        $fonctions = $this->getServiceFonctionReferentiel()->getList();

        $data = [
            'etape-requise' => [],
            'structures'    => [],
        ];
        foreach ($fonctions as $fonction) {
            if ($fonction->isEtapeRequise()) {
                $data['etape-requise'][] = $fonction->getId();
            }
            if ($fonction->getStructure()) {
                $data['structures'][$fonction->getId()] = $fonction->getStructure()->getId();
            }
        }

        return $data;
    }



    /**
     * Should return an array specification compatible with
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
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
     * @param array                                     $data
     * @param \Referentiel\Entity\Db\ServiceReferentiel $object
     *
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
     * @param \Referentiel\Entity\Db\ServiceReferentiel $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data            = [];
        $data['service'] = $object;

        return $data;
    }
}