<?php

namespace Utilisateur\Form;

use Application\Form\AbstractForm;
use Application\Service\Traits\PerimetreServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;
use UnicaenApp\Util;

/**
 * Description of RoleForm
 *
 * @author Laurent LECLUSE <laurent.lecluse at unicaen.fr>
 */
class RoleForm extends AbstractForm
{
    use PerimetreServiceAwareTrait;


    public function init()
    {
        $hydrator = new RoleFormHydrator;
        $hydrator->setServicePerimetre($this->getServicePerimetre());
        $this->setHydrator($hydrator);
        $this->setAttribute('action', $this->getCurrentUrl());
        $this->add([
            'type'    => 'Text',
            'name'    => 'code',
            'options' => [
                'label' => 'Code',
            ],
        ]);

        $this->add([
            'type'    => 'Text',
            'name'    => 'libelle',
            'options' => [
                'label' => 'Libellé',
            ],
        ]);

        $this->add([
            'type'    => 'Select',
            'name'    => 'perimetre',
            'options' => [
                'label'         => 'Périmètre',
                'value_options' => Util::collectionAsOptions($this->getServicePerimetre()->getList()),
            ],

        ]);

        $this->add([
            'name'       => 'peut-changer-structure',
            'options'    => [
                'label' => 'Peut changer de structure',
            ],
            'attributes' => [
                'title' => "Détermine si l'utilisateur peut changer de structure",
            ],
            'type'       => 'Checkbox',
        ]);

        $this->add([
            'name'    => 'accessible-exterieur',
            'options' => [
                'label' => 'Utilisable hors du réseau informatique de l\'établissement',
            ],
            'type'    => 'Checkbox',
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
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'code'                   => [
                'required' => true,
            ],
            'libelle'                => [
                'required' => true,
            ],
            'perimetre'              => [
                'required' => true,
            ],
            'peut-changer-structure' => [
                'required' => true,
            ],
            'accessible-exterieur'   => [
                'required' => true,
            ],
        ];
    }
}





class RoleFormHydrator implements HydratorInterface
{
    use PerimetreServiceAwareTrait;


    /**
     * @param array                       $data
     * @param \Utilisateur\Entity\Db\Role $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setCode($data['code']);
        $object->setLibelle($data['libelle']);
        $object->setPerimetre($this->getServicePerimetre()->get($data['perimetre']));
        $object->setPeutChangerStructure($data['peut-changer-structure']);
        $object->setAccessibleExterieur($data['accessible-exterieur']);

        return $object;
    }



    /**
     * @param \Utilisateur\Entity\Db\Role $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'id'                     => $object->getId(),
            'code'                   => $object->getCode(),
            'libelle'                => $object->getLibelle(),
            'perimetre'              => $object->getPerimetre() ? $object->getPerimetre()->getId() : null,
            'peut-changer-structure' => $object->getPeutChangerStructure(),
            'accessible-exterieur'   => $object->isAccessibleExterieur(),
        ];

        return $data;
    }
}