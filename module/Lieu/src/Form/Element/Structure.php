<?php

namespace Lieu\Form\Element;

use Doctrine\Common\Collections\Collection;
use Laminas\Form\Element\Select;
use Lieu\Service\StructureServiceAwareTrait;
use Lieu\Entity\Db\Structure as StructureEntity;

class Structure extends Select
{
    use StructureServiceAwareTrait;

    private bool $enseignement = false;

    private array $optionsBuilding = [];

    public function init()
    {
        parent::init();

        $this->setLabel('Structure');
        $this->setAttribute('class', 'selectpicker');
        $this->setAttribute('data-live-search', 'true');
        $this->setEmptyOption('- Aucune -');
        $this->populateOptions();
    }



    public function isEnseignement(): bool
    {
        return $this->enseignement;
    }



    public function setEnseignement(bool $enseignement): Structure
    {
        $this->enseignement = $enseignement;
        return $this;
    }



    protected function populateOptions()
    {
        $this->optionsBuilding = [];

        $tree = $this->getServiceStructure()->getTree(null, $this->isEnseignement());
        $this->subPopulate($tree, 1);

        $this->setValueOptions($this->optionsBuilding);
        $this->optionsBuilding = [];
    }



    /**
     * @param array|StructureEntity[] $structures
     * @param int $level
     * @return void
     */
    protected function subPopulate(array|Collection $structures, int $level)
    {
        foreach($structures as $structure){
            $this->optionsBuilding[$structure->getId()] = [
                'value' => $structure->getId(),
                'label' => $structure->getLibelleCourt(),
                'attributes' => [
                    'style' => 'padding-left:'.(($level-1)*2+1).'em',
                ],
            ];
            $this->subPopulate($structure->getStructures(), $level + 1);
        }
    }
}