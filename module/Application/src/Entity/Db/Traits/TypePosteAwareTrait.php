<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TypePoste;

/**
 * Description of TypePosteAwareTrait
 *
 * @author UnicaenCode
 */
trait TypePosteAwareTrait
{
    protected ?TypePoste $typePoste;



    /**
     * @param TypePoste|null $typePoste
     *
     * @return self
     */
    public function setTypePoste( ?TypePoste $typePoste )
    {
        $this->typePoste = $typePoste;

        return $this;
    }



    public function getTypePoste(): ?TypePoste
    {
        return $this->typePoste;
    }
}