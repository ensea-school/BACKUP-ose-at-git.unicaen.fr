<?php

namespace Application\Form\MotifModificationService;

use Application\Form\AbstractForm;
use Zend\Form\Element\Csrf;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Description of MotifModificationServiceSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky@unicaen.fr>
 */
class MotifModificationServiceSaisieForm extends AbstractForm
{

    public function init()
    {
        $hydrator = new MotifModificationServiceHydrator();
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());
        $this->add([
            'name'    => 'code',
            'options' => [
                'label' => "Code",
            ],
            'type'    => 'Text',
        ]);
        $this->add([
            'name'    => 'libelle',
            'options' => [
                'label' => "Libelle",
            ],
            'type'    => 'Text',
        ]);
        $this->add([
            'name'    => 'multiplicateur',
            'options' => [
                'label' => "Multiplicateur",
            ],
            'type'    => 'Text',
        ]);
        $this->add([
            'name'    => 'decharge',
            'options' => [
                'label' => "Decharge",
            ],
            'type'    => 'Checkbox',
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
            'code' => [
                'required' => true,
            ],

            'libelle' => [
                'required' => true,
            ],

            'multiplicateur' => [
                'required' => true,
            ],

            'decharge' => [
                'required' => true,
            ],

        ];
    }

}





class MotifModificationServiceHydrator implements HydratorInterface
{

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array                                             $data
     * @param  \Application\Entity\Db\MotifModificationServiceDu $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setCode($data['code']);
        $object->setLibelle($data['libelle']);
        $object->setMultiplicateur($data['multiplicateur']);
        $object->setDecharge($data['decharge']);

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Db\MotifModificationServiceDu $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'id'               => $object->getId()
            , 'code'           => $object->getCode()
            , 'libelle'        => $object->getLibelle()
            , 'multiplicateur' => $object->getMultiplicateur()
            , 'decharge'       => $object->getDecharge()
            ,
        ];

        return $data;
    }
}   
    