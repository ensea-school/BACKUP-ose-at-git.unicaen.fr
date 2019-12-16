<?php

namespace Application\Form\MotifNonPaiement;

use Application\Form\AbstractForm;
use Zend\Form\Element\Csrf;
use Zend\Hydrator\HydratorInterface;

/**
 * Description of MotifNonPaiementSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky@unicaen.fr>
 */
class MotifNonPaiementSaisieForm extends AbstractForm
{

    public function init()
    {
        $hydrator = new MotifNonPaiementHydrator();
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
            'name'    => 'libelle-court',
            'options' => [
                'label' => "Libelle Court",
            ],
            'type'    => 'Text',
        ]);
        $this->add([
            'name'    => 'libelle-long',
            'options' => [
                'label' => "Libelle Long",
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
            'code' => [
                'required' => true,
            ],

            'libelle-court' => [
                'required' => true,
            ],

            'libelle-long' => [
                'required' => true,
            ],

        ];
    }

}





class MotifNonPaiementHydrator implements HydratorInterface
{

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array                                   $data
     * @param  \Application\Entity\Db\MotifNonPaiement $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setCode($data['code']);
        $object->setLibelleCourt($data['libelle-court']);
        $object->setLibelleLong($data['libelle-long']);

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Db\MotifNonPaiement $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'id'              => $object->getId()
            , 'code'          => $object->getCode()
            , 'libelle-court' => $object->getLibelleCourt()
            , 'libelle-long'  => $object->getLibelleLong()
            ,
        ];

        return $data;
    }
}   
    