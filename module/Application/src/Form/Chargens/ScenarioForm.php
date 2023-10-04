<?php

namespace Application\Form\Chargens;

use Application\Entity\Db\Scenario;
use Application\Form\AbstractForm;
use Application\Service\Traits\ContextServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;
use Lieu\Service\StructureServiceAwareTrait;
use UnicaenApp\Util;


/**
 * Description of ScenarioForm
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ScenarioForm extends AbstractForm
{
    use ContextServiceAwareTrait;
    use StructureServiceAwareTrait;

    /**
     * @var Structure[]
     */
    private $structures;



    public function init()
    {
        $this->loadData();

        $hydrator = new ScenarioFormHydrator;
        $hydrator->setServiceStructure($this->getServiceStructure());
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $this->add([
            'name'    => 'libelle',
            'type'    => 'Text',
            'options' => [
                'label' => 'Libellé :',
            ],
        ]);

        $this->add([
            'name'       => 'structure',
            'type'       => 'Select',
            'options'    => [
                'label'                     => "Composante :",
                'empty_option'              => "- Aucune -",
                'disable_inarray_validator' => true,
                'label_attributes'          => [
                    'title' => "Structure gestionnaire de l'enseignement",
                ],
                'value_options'             => Util::collectionAsOptions($this->structures),
            ],
            'attributes' => [
                'title'            => "Composante ...",
                'class'            => 'selectpicker',
                'data-width'       => "100%",
                'data-live-search' => "true",
            ],
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



    private function loadData()
    {
        $cStructure = $this->getServiceContext()->getStructure();

        if ($cStructure) {
            $this->structures = [$cStructure];
        } else {
            $qb = $this->getServiceStructure()->finderByHistorique();
            $this->getServiceStructure()->finderByEnseignement($qb);
            $this->structures = $this->getServiceStructure()->getList($qb);
        }

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
            'structure' => [
                'required' => false,
            ],
        ];
    }
}





class ScenarioFormHydrator implements HydratorInterface
{
    use StructureServiceAwareTrait;


    /**
     * @param array    $data
     * @param Scenario $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);

        if (isset($data['structure'])) {
            $structureId = (int)$data['structure'];
            $object->setStructure($this->getServiceStructure()->get($structureId));
        }

        return $object;
    }



    /**
     * @param Scenario $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'id'        => $object->getId(),
            'libelle'   => $object->getLibelle(),
            'structure' => $object->getStructure() ? $object->getStructure()->getId() : null,
        ];

        return $data;
    }
}