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

    private bool $contextFilter = true;



    public function init()
    {
        parent::init();
        $this->setLabel('Structure');
        $this->setAttribute('class', 'selectpicker');
        $this->setAttribute('data-live-search', 'true');
        $this->setEmptyOption('- Aucune -');
    }



    public function isContextFilter(): bool
    {
        return $this->contextFilter && false !== $this->getOption('context_filter');
    }



    public function setContextFilter(bool $contextFilter): Structure
    {
        $this->contextFilter = $contextFilter;

        return $this;
    }



    public function isEnseignement(): bool
    {
        return $this->enseignement || $this->getOption('enseignement');
    }



    public function setEnseignement(bool $enseignement): Structure
    {
        $this->enseignement = $enseignement;

        return $this;
    }



    protected function populateOptions()
    {
        $this->valueOptions = [];

        $tree = $this->getServiceStructure()->getTree(null, $this->isEnseignement(), $this->isContextFilter());
        $this->subPopulate($tree, 1);
    }



    /**
     * @return array
     */
    public function getValueOptions(): array
    {
        if (empty($this->valueOptions)){
            $this->populateOptions();
        }

        return $this->valueOptions;
    }



    /**
     * @param array|StructureEntity[] $structures
     * @param int $level
     * @return void
     */
    protected function subPopulate(array|Collection $structures, int $level)
    {
        foreach($structures as $structure){
            $this->valueOptions[$structure->getId()] = [
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