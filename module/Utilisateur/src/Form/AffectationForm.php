<?php

namespace Utilisateur\Form;

use Application\Form\AbstractForm;
use Application\Service\Traits\ContextServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;
use Lieu\Form\Element\Structure;
use Lieu\Service\StructureServiceAwareTrait;
use UnicaenApp\Form\Element\SearchAndSelect;
use UnicaenApp\Util;
use Utilisateur\Entity\Db\Role;
use Utilisateur\Service\RoleServiceAwareTrait;
use Utilisateur\Service\UtilisateurServiceAwareTrait;

/**
 * Description of AffectationForm
 *
 * @author Laurent LECLUSE <laurent.lecluse at unicaen.fr>
 */
class AffectationForm extends AbstractForm
{
    use StructureServiceAwareTrait;
    use UtilisateurServiceAwareTrait;
    use RoleServiceAwareTrait;
    use ContextServiceAwareTrait;


    public function init()
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $structure = $role ? $role->getStructure() : null;

        $this->setAttribute('action', $this->getCurrentUrl());
        $hydrator = new AffectationFormHydrator();
        $this->setHydrator($hydrator);
        $hydrator->setServiceUtilisateur($this->getServiceUtilisateur());
        $hydrator->setServiceRole($this->getServiceRole());
        $hydrator->setServiceStructure($this->getServiceStructure());

        $roles = $this->getServiceRole()->getList();

        $rolesMustHaveStructure = [];
        foreach ($roles as $role) {
            /* @var $role Role */
            if ($role->getPerimetre()->isComposante()) {
                $rolesMustHaveStructure[] = $role->getId();
            }
            if ($structure && $role->getPerimetre()->isEtablissement()) {
                unset($roles[$role->getId()]);
            }
        }

        $this->setAttribute('data-roles-must-have-structure', json_encode($rolesMustHaveStructure));
        $this->setAttribute('class', 'affectation-form');

        $utilisateur = new SearchAndSelect('utilisateur');
        $utilisateur->setRequired(true)
            ->setSelectionRequired(true)
            ->setAutocompleteSource(
                $this->getUrl('utilisateur-recherche')
            )
            ->setLabel("Utilisateur")
            ->setAttributes(['title' => "Saisissez le nom suivi éventuellement du prénom (2 lettres au moins)"]);
        $this->add($utilisateur);

        $this->add([
            'type'    => 'Select',
            'name'    => 'role',
            'options' => [
                'label'         => 'Rôle',
                'value_options' => Util::collectionAsOptions($roles),
            ],
        ]);

        $this->add([
            'type'       => Structure::class,
            'name'       => 'structure',
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
            'utilisateur' => [
                'required' => true,
            ],
            'role'        => [
                'required' => true,
            ],
            'structure'   => [
                'required' => false,
            ],
        ];
    }
}


class AffectationFormHydrator implements HydratorInterface
{
    use StructureServiceAwareTrait;
    use RoleServiceAwareTrait;
    use UtilisateurServiceAwareTrait;


    /**
     * @param array                              $data
     * @param \Utilisateur\Entity\Db\Affectation $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $utilisateur = isset($data['utilisateur']['id']) ? $data['utilisateur']['id'] : null;
        $structure = isset($data['structure']) ? (int)$data['structure'] : null;

        $object->setUtilisateur($this->getServiceUtilisateur()->getByUsername($utilisateur));
        $object->setRole($this->getServiceRole()->get($data['role']));
        $object->setStructure($this->getServiceStructure()->get($structure));

        return $object;
    }



    /**
     * @param \Utilisateur\Entity\Db\Affectation $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'id' => $object->getId(),
        ];

        if ($utilisateur = $object->getUtilisateur()) {
            $data['utilisateur'] = [
                'id'    => $utilisateur->getUsername(),
                'label' => (string)$utilisateur,
            ];
        }

        if ($role = $object->getRole()) {
            $data['role'] = $role->getId();
        }

        if ($structure = $object->getStructure()) {
            $data['structure'] = $structure->getId();
        }

        return $data;
    }
}