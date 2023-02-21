<?php

namespace OffreFormation\Entity\Db\Traits;

use OffreFormation\Entity\Db\ElementPedagogique;

/**
 * Description of ElementPedagogiqueAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementPedagogiqueAwareTrait
{
    protected ?ElementPedagogique $elementPedagogique = null;



    /**
     * @param ElementPedagogique $elementPedagogique
     *
     * @return self
     */
    public function setElementPedagogique( ?ElementPedagogique $elementPedagogique )
    {
        $this->elementPedagogique = $elementPedagogique;

        return $this;
    }



    public function getElementPedagogique(): ?ElementPedagogique
    {
        return $this->elementPedagogique;
    }
}