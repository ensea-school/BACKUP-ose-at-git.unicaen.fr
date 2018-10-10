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
            ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($this->getServiceStructure()->getList()));
        $this->add([
            'name'    => 'code',
            'options' => [
                'label' => "Code",
            ],
            'type'    => 'Text',
        ]);

        $this->add([
            'name'    => 'code',
            'options' => [
                'label' => "Code",
            ],
            'type'    => 'Text',
        ]);

        $this->add([
            'name'    => 'unite-budgetaire',
            'options' => [
                'label' => "Unité budgétaire",
            ],
            'type'    => 'Text',
        ]);

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

            'source-code' => [
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
        $object->setSourceCode($data['code']);
        $object->setCentreCout($this->getServiceCentreCout()->get($data['centre-cout']));
        $object->setSource($this->getServiceSource()->getOse());
        $object->setUniteBudgetaire($data['unite-budgetaire']);

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
            'id'                 => $object->getId()
            , 'structure'         => ($s = $object->getStructure()) ? $s->getId() : null
            , 'code'             => $object->getSourceCode()
            , 'centre-cout'          => $object->getCentreCout()->getId()
            , 'unite-budgetaire' => $object->getUniteBudgetaire()
        ];

        return $data;
    }
}   
