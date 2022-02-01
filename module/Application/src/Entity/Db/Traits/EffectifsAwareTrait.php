<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Effectifs;

/**
 * Description of EffectifsAwareTrait
 *
 * @author UnicaenCode
 */
trait EffectifsAwareTrait
{
    protected ?Effectifs $effectifs = null;



    /**
     * @param Effectifs $effectifs
     *
     * @return self
     */
    public function setEffectifs( Effectifs $effectifs )
    {
        $this->effectifs = $effectifs;

        return $this;
    }



    public function getEffectifs(): ?Effectifs
    {
        return $this->effectifs;
    }
}