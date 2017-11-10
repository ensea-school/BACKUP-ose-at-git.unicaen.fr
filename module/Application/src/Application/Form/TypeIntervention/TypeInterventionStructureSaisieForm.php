<?php
namespace Application\Form\TypeIntervention;

use Application\Form\AbstractForm;
use Application\Service\Traits\TypeInterventionAwareTrait;
use Application\Service\Traits\TypeInterventionStructureServiceAwareTrait;
use Application\Service\Traits\StructureAwareTrait;
use Zend\Form\Element\Csrf;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\AnneeAwareTrait;
use UnicaenApp\Util;
use UnicaenApp\Service\EntityManagerAwareTrait;

/**
 * Description of TypeInterventionStructureSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky at unicaen.fr>
 */
class TypeInterventionStructureSaisieForm extends AbstractForm
{
    use \Application\Entity\Db\Traits\TypeInterventionStructureAwareTrait;
    use StructureAwareTrait;
    use ContextServiceAwareTrait;
    use AnneeAwareTrait;



    public function init()
    {
        $hydrator = new TypeInterventionStructureHydrator();
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());
        $this->add([
                'name' => 'type-intervention',
                'type' => 'Hidden',
            ]
        );
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
        $this->add([
            'name'    => 'visible',
            'options' => [
                'label' => "Visible",
            ],
            'type'    => 'Checkbox',
        ]);
        $this->add([
            'type'       => 'Select',
            'name'       => 'annee-debut',
            'options'    => [
                'empty_option'  => 'Aucune',
                'value_options' => Util::collectionAsOptions($this->getServiceAnnee()->getList()),
                'label'         => 'année de début',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
        ]);
        $this->add([
            'type'       => 'Select',
            'name'       => 'annee-fin',
            'options'    => [
                'empty_option'  => 'Aucune',
                'value_options' => Util::collectionAsOptions($this->getServiceAnnee()->getList()),
                'label'         => 'année de fin',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
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
        $role             = $this->getServiceContext()->getSelectedIdentityRole();
        $serviceStructure = $this->getServiceStructure();
        $qb               = $serviceStructure->finderByEnseignement($serviceStructure->finderByNiveau(2));
        if ($role->getStructure()) {
            $serviceStructure->finderById($role->getStructure()->getId(), $qb); // Filtre
        }
        $this->get('structure')
            ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($serviceStructure->getList($qb)));

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
            'structure'   => [
                'required' => true,
            ],
            'visible'     => [
                'required' => true,
            ],
            'annee-debut' => [
                'required' => false,
            ],
            'annee-fin'   => [
                'required' => false,
            ],
        ];
    }

}





class TypeInterventionStructureHydrator implements HydratorInterface
{
    use TypeInterventionStructureServiceAwareTrait;
    use TypeInterventionAwareTrait;
    use StructureAwareTrait;
    use EntityManagerAwareTrait;
    use AnneeAwareTrait;



    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array                                            $data
     * @param  \Application\Entity\Db\TypeInterventionStructure $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setTypeIntervention($this->getServiceTypeIntervention()->getByCode($data['type-intervention']));
        if (array_key_exists('structure', $data)) {
            $object->setStructure($this->getServiceStructure()->get($data['structure']));
        }
        $object->setVisible($data['visible']);
        if (array_key_exists('annee-debut', $data)) {
            $object->setAnneeDebut($this->getServiceAnnee()->get($data['annee-debut']));
        }
        if (array_key_exists('annee-fin', $data)) {
            $object->setAnneeFin($this->getServiceAnnee()->get($data['annee-fin']));
        }

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Db\TypeInterventionStructure $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'id'                => $object->getId(),
            'type-intervention' => $object->getTypeIntervention(),
            'structure'         => ($s = $object->getStructure()) ? $s->getId() : null,
            'visible'           => $object->isVisible(),
            'annee-debut'       => $object->getAnneeDebut() ? $object->getAnneeDebut()->getId() : null,
            'annee-fin'         => $object->getAnneeFin() ? $object->getAnneeFin()->getId() : null,
        ];

        return $data;
    }
}

