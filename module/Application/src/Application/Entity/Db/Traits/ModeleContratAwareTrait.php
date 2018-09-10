<?php

namespace Application\Entity\Db\Traits;

/**
 * Description of ModeleContratAwareTrait
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
trait ModeleContratAwareTrait
{
    /**
     * @var ModeleContrat
     */
    private $modeleContrat;



    /**
     * @return ModeleContrat
     */
    public function getModeleContrat(): ModeleContrat
    {
        return $this->modeleContrat;
    }



    /**
     * @param ModeleContrat $modeleContrat
     *
     */
    public function setModeleContrat(ModeleContrat $modeleContrat)
    {
        $this->modeleContrat = $modeleContrat;

        return $this;
    }

}