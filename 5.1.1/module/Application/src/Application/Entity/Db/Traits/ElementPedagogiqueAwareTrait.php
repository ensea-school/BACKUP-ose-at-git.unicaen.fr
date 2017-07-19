<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\ElementPedagogique;

/**
 * Description of ElementPedagogiqueAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementPedagogiqueAwareTrait
{
    /**
     * @var ElementPedagogique
     */
    private $elementPedagogique;





    /**
     * @param ElementPedagogique $elementPedagogique
     * @return self
     */
    public function setElementPedagogique( ElementPedagogique $elementPedagogique = null )
    {
        $this->elementPedagogique = $elementPedagogique;
        return $this;
    }



    /**
     * @return ElementPedagogique
     */
    public function getElementPedagogique()
    {
        return $this->elementPedagogique;
    }
}