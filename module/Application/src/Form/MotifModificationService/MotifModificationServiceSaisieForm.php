<?php

namespace Application\Form\MotifModificationService;

use Application\Form\AbstractForm;
use Laminas\Form\Element\Csrf;
use Laminas\Hydrator\HydratorInterface;

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
            'type'    => 'Select',
        ]);
        $this->get('multiplicateur')->setValueOptions([
            '-1' => '-1 : retire du service dû',
            '1'  => '1 : ajoute du service dû',
        ]);

        $this->add([
            'name'    => 'decharge',
            'options' => [
                'label' => "Le dépassement du service dû ne donnera pas lieu à des heures complémentaires (comme par exemple pour une décharge)",
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
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
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
     * @param array                                             $data
     * @param \Service\Entity\Db\MotifModificationServiceDu $object
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
     * @param \Service\Entity\Db\MotifModificationServiceDu $object
     *
     * @return array
     */
    public function extract($object): array
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
    