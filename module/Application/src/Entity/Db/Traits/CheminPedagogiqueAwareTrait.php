<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\CheminPedagogique;

/**
 * Description of CheminPedagogiqueAwareTrait
 *
 * @author UnicaenCode
 */
trait CheminPedagogiqueAwareTrait
{
    protected ?CheminPedagogique $cheminPedagogique = null;



    /**
     * @param CheminPedagogique $cheminPedagogique
     *
     * @return self
     */
    public function setCheminPedagogique( ?CheminPedagogique $cheminPedagogique )
    {
        $this->cheminPedagogique = $cheminPedagogique;

        return $this;
    }



    public function getCheminPedagogique(): ?CheminPedagogique
    {
        return $this->cheminPedagogique;
    }
}