<?php

namespace Application\Form\Droits;

use Application\Entity\Db\Role;
use Application\Service\Traits\PersonnelAwareTrait;
use Application\Service\Traits\RoleAwareTrait;
use Application\Service\Traits\StructureAwareTrait;
use UnicaenApp\Form\Element\SearchAndSelect;
use Zend\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use UnicaenApp\Util;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Description of AffectationForm
 *
 * @author Laurent LECLUSE <laurent.lecluse at unicaen.fr>
 */
class AffectationForm extends Form\Form implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;
    use StructureAwareTrait;
    use PersonnelAwareTrait;
    use RoleAwareTrait;


    public function init()
    {
        $url = $this->getServiceLocator()->getServiceLocator()->get('viewhelpermanager')->get('url');
        /* @var $url \Zend\View\Helper\Url */

        $hydrator = new AffectationFormHydrator;
        $this->setHydrator($hydrator);
        $hydrator->setServicePersonnel  ($this->getServicePersonnel());
        $hydrator->setServiceRole       ($this->getServiceRole()     );
        $hydrator->setServiceStructure  ($this->getServiceStructure());

        $roles = $this->getServiceRole()->getList();

        $rolesMustHaveStructure = [];
        foreach ($roles as $role) { /* @var $role Role */
            if ($role->getPerimetre()->isComposante()){
                $rolesMustHaveStructure[] = $role->getId();
            }
        }

        $this->setAttribute('data-roles-must-have-structure', json_encode($rolesMustHaveStructure));
        $this->setAttribute('class','affectation-form');

        $qb = $this->getServiceStructure()->finderByEnseignement();
        $this->getServiceStructure()->finderByNiveau(2, $qb);
        $structures = $this->getServiceStructure()->getList( $qb );


        $personnel = new SearchAndSelect('personnel');
        $personnel ->setRequired(true)
            ->setSelectionRequired(true)
            ->setAutocompleteSource(
                $url('recherche', ['action' => 'personnelFind'])
            )
            ->setLabel("Personnel")
            ->setAttributes(['title' => "Saisissez le nom suivi éventuellement du prénom (2 lettres au moins)"]);
        $this->add($personnel);

        $this->add( [
            'type' => 'Select',
            'name' => 'role',
            'options' => [
                'label' => 'Rôle',
                'value_options' => Util::collectionAsOptions($roles)
            ],
        ] );

        $this->add( [
            'type' => 'Select',
            'name' => 'structure',
            'options' => [
                'label' => 'Structure',
                'value_options' => Util::collectionAsOptions($structures)
            ],
        ] );

        $this->add( [
            'name' => 'id',
            'type' => 'Hidden'
        ] );

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value'  => 'Enregistrer',
                'class'  => 'btn btn-primary',
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
            'personnel' => [
                'required' => true,
            ],
            'role' => [
                'required' => true,
            ],
            'structure' => [
                'required' => false,
            ],
        ];
    }
}


class AffectationFormHydrator implements HydratorInterface
{
    use StructureAwareTrait;
    use RoleAwareTrait;
    use PersonnelAwareTrait;

    /**
     * @param  array $data
     * @param  \Application\Entity\Db\Affectation $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $personnel = isset($data['personnel']['id']) ? (int)$data['personnel']['id'] : null;

        $object->setPersonnel( $this->getServicePersonnel() ->get($personnel) );
        $object->setRole     ( $this->getServiceRole()      ->get($data['role']     ) );
        $object->setStructure( $this->getServiceStructure() ->get($data['structure']) );
        return $object;
    }

    /**
     * @param  \Application\Entity\Db\Affectation $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'id' => $object->getId(),
        ];

        if ($personnel = $object->getPersonnel()){
            $data['personnel'] = [
                'id'    => $personnel->getId(),
                'label' => (string)$personnel
            ];
        }

        if ($role = $object->getRole()){
            $data['role'] = $role->getId();
        }

        if ($structure = $object->getStructure()){
            $data['structure'] = $structure->getId();
        }

        return $data;
    }
}