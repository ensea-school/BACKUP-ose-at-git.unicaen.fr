<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Description of DisciplineForm
 *
 * @author Laurent LECLUSE <laurent.lecluse at unicaen.fr>
 */
class DisciplineForm extends Form implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;



    public function init()
    {
        $hydrator = new DisciplineFormHydrator;
        $this->setHydrator($hydrator);

        $this->add([
            'type'    => 'Text',
            'name'    => 'source-code',
            'options' => [
                'label' => 'Code',
            ],
        ]);

        $this->add([
            'type'    => 'Text',
            'name'    => 'libelle-long',
            'options' => [
                'label' => 'Libellé long',
            ],
        ]);

        $this->add([
            'type'    => 'Text',
            'name'    => 'libelle-court',
            'options' => [
                'label' => 'Libellé court',
            ],
        ]);

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ],
        ]);
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
            'source-code'   => [
                'required' => true,
            ],
            'libelle-long'  => [
                'required' => true,
            ],
            'libelle-court' => [
                'required' => true,
            ],
        ];
    }
}





class DisciplineFormHydrator implements HydratorInterface
{

    /**
     * @param  array                             $data
     * @param  \Application\Entity\Db\Discipline $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setSourceCode($data['source-code']);
        $object->setLibelleLong($data['libelle-long']);
        $object->setLibelleCourt($data['libelle-court']);

        return $object;
    }



    /**
     * @param  \Application\Entity\Db\Discipline $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'id'            => $object->getId(),
            'source-code'   => $object->getSourceCode(),
            'libelle-long'  => $object->getLibelleLong(),
            'libelle-court' => $object->getLibelleCourt(),
        ];

        return $data;
    }
}