<?php

namespace Application\Form\CentreCout;

use Application\Form\AbstractForm;
use Application\Service\Traits\CentreCoutServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use Zend\Form\Element\Csrf;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Application\Service\Traits\CentreCoutStructureServiceAwareTrait;

/**
 * Description of CentreCoutStructureStructureSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky@unicaen.fr>
 */
class CentreCoutStructureSaisieForm extends AbstractForm
{
    use StructureServiceAwareTrait;
    use CentreCoutStructureServiceAwareTrait;



    public function init()
    {
        $hydrator = new CentreCoutStructureHydrator();
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());
        $this->add([
            'name'    => 'centre-cout',
            'options' => [
            ],
            'type'    => 'Hidden',
        ]);

        $this->add([
            'name'       => 'structure',
            'options'    => [
                'label' => 'Structure',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);
        $this->get('structure')
            ->setValueOptions(\UnicaenApp\Util::collectionAsOptions(getStructures()));

        $this->add(new Csrf('security'));
        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => "Enregistrer",
                'class' => 'btn btn-primary',
            ],
        ]);

        return $this;
    }



    public function getStructures()
    {
        $serviceStructure = $this->getServiceStructure();
        $qb               = $serviceStructure->finderByEnseignement();
        $structures       = $serviceStructure->getList($qb);

        return $structures;
    }



    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'structure' => [
                'required' => true,
            ],

            'unite-budgetaire' => [
                'required' => false,
            ],

        ];
    }

}





class CentreCoutStructureHydrator implements HydratorInterface
{
    use StructureServiceAwareTrait;
    use SourceServiceAwareTrait;
    use CentreCoutServiceAwareTrait;



    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array                                      $data
     * @param  \Application\Entity\Db\CentreCoutStructure $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        if (array_key_exists('structure', $data)) {
            $object->setStructure($this->getServiceStructure()->get($data['structure']));
        }
        $object->setCentreCout($this->getServiceCentreCout()->get($data['centre-cout']));

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Db\CentreCoutStructure $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'id'          => $object->getId(),
            'structure'   => ($s = $object->getStructure()) ? $s->getId() : null,
            'centre-cout' => $object->getCentreCout()->getId(),
        ];

        return $data;
    }
}
