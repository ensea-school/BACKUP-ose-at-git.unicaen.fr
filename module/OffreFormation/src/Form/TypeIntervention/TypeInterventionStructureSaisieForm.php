<?php

namespace OffreFormation\Form\TypeIntervention;

use Application\Form\AbstractForm;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Laminas\Form\Element\Csrf;
use Laminas\Hydrator\HydratorInterface;
use Lieu\Form\Element\Structure;
use Lieu\Service\StructureServiceAwareTrait;
use OffreFormation\Entity\Db\Traits\TypeInterventionStructureAwareTrait;
use OffreFormation\Service\Traits\TypeInterventionServiceAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenApp\Util;

/**
 * Description of TypeInterventionStructureSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky at unicaen.fr>
 */
class TypeInterventionStructureSaisieForm extends AbstractForm
{
    use TypeInterventionStructureAwareTrait;
    use StructureServiceAwareTrait;
    use ContextServiceAwareTrait;
    use AnneeServiceAwareTrait;


    public function init()
    {
        $hydrator = new TypeInterventionStructureHydrator();
        $this->setHydrator($hydrator);
        $hydrator->setEntityManager($this->getEntityManager());

        $this->setAttribute('action', $this->getCurrentUrl());
        $this->add([
                'name' => 'type-intervention',
                'type' => 'Hidden',
            ]
        );
        $this->add([
            'name'       => 'structure',
            'type'       => Structure::class,
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
                'value_options' => Util::collectionAsOptions($this->getServiceAnnee()->getChoixAnnees()),
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
                'value_options' => Util::collectionAsOptions($this->getServiceAnnee()->getChoixAnnees()),
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
    use TypeInterventionServiceAwareTrait;
    use StructureServiceAwareTrait;
    use EntityManagerAwareTrait;
    use AnneeServiceAwareTrait;


    /**
     * Hydrate $object with the provided $data.
     *
     * @param array                                                   $data
     * @param \OffreFormation\Entity\Db\TypeInterventionStructure $object
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
     * @param \OffreFormation\Entity\Db\TypeInterventionStructure $object
     *
     * @return array
     */
    public function extract($object): array
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

