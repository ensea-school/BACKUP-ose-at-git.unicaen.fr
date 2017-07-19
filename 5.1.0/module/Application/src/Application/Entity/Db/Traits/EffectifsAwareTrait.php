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
    /**
     * @var Effectifs
     */
    private $effectifs;





    /**
     * @param Effectifs $effectifs
     * @return self
     */
    public function setEffectifs( Effectifs $effectifs = null )
    {
        $this->effectifs = $effectifs;
        return $this;
    }



    /**
     * @return Effectifs
     */
    public function getEffectifs()
    {
        return $this->effectifs;
    }
}