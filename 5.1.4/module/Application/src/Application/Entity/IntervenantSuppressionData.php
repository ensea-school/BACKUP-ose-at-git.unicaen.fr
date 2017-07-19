<?php

namespace Application\Entity;

use Application\Model\TreeNode;

class IntervenantSuppressionData extends TreeNode {

    private $entity;

    /**
     * @var bool
     */
    private $unbreakable = false;


    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }



    /**
     * @param mixed $entity
     *
     * @return IntervenantSuppressionData
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }



    /**
     * @return boolean
     */
    public function isUnbreakable()
    {
        return $this->unbreakable;
    }



    /**
     * @param boolean $unbreakable
     *
     * @return IntervenantSuppressionData
     */
    public function setUnbreakable($unbreakable)
    {
        $this->unbreakable = $unbreakable;

        return $this;
    }

}