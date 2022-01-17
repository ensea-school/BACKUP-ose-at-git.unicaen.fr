<?php

namespace Application\Form\modulateur;

use Application\Form\AbstractForm;
use Laminas\Form\Element\Csrf;
use Laminas\Hydrator\HydratorInterface;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\TypeModulateurServiceAwareTrait;

/**
 * Description of typeModulateurSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky at unicaen.fr>
 */
class typeModulateurSaisieForm extends AbstractForm
{
    use ContextServiceAwareTrait;
    use TypeModulateurServiceAwareTrait;


    public function init()
    {
        $hydrator = new typeModulateurHydrator();
        /**
         * @var $typesModu \Application\Entity\Db\typeModulateur[]
         */
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());
        $this->add([
            'name'       => 'code',
            'options'    => [
                'label' => "Code",
            ],
            'attributes' => [
                'id' => uniqid('code'),
            ],
            'type'       => 'Text',
        ]);
        $this->add([
            'name'    => 'libelle',
            'options' => [
                'label' => "Libelle",
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
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'code'    => [
                'required' => false,
            ],
            'libelle' => [
                'required' => true,
            ],
        ];
    }

}





class typeModulateurHydrator implements HydratorInterface
{
    use TypeModulateurServiceAwareTrait;


    /**
     * Hydrate $object with the provided $data.
     *
     * @param array                                 $data
     * @param \Application\Entity\Db\typeModulateur $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setCode($data['code']);
        $object->setLibelle($data['libelle']);

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param \Application\Entity\Db\typeModulateur $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'id'      => $object->getId(),
            'code'    => $object->getCode(),
            'libelle' => $object->getLibelle(),
        ];

        return $data;
    }
}