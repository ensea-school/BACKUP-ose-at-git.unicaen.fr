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
    /**
     * @var CheminPedagogique
     */
    private $cheminPedagogique;





    /**
     * @param CheminPedagogique $cheminPedagogique
     * @return self
     */
    public function setCheminPedagogique( CheminPedagogique $cheminPedagogique = null )
    {
        $this->cheminPedagogique = $cheminPedagogique;
        return $this;
    }



    /**
     * @return CheminPedagogique
     */
    public function getCheminPedagogique()
    {
        return $this->cheminPedagogique;
    }
}