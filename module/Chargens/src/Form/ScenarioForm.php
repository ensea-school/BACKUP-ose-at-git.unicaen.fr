<?php

namespace Chargens\Form;

use Application\Form\AbstractForm;
use Application\Service\Traits\ContextServiceAwareTrait;
use Chargens\Entity\Db\Scenario;
use Laminas\Hydrator\HydratorInterface;
use Lieu\Form\Element\Structure;
use Lieu\Service\StructureServiceAwareTrait;


/**
 * Description of ScenarioForm
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ScenarioForm extends AbstractForm
{
    use ContextServiceAwareTrait;
    use StructureServiceAwareTrait;



    public function init()
    {
        $hydrator = new ScenarioFormHydrator();
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
            'type'       => Structure::class,
            'options'    => [
                'label'                     => "Composante :",
                'disable_inarray_validator' => true,
                'label_attributes'          => [
                    'title' => "Structure gestionnaire de l'enseignement",
                ],
                'enseignement' => true,
            ],
            'attributes' => [
                'title'            => "Composante ...",
                'data-width'       => "100%",
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