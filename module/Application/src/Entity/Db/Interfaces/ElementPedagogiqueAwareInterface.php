<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\ElementPedagogique;

/**
 * Description of ElementPedagogiqueAwareInterface
 *
 * @author UnicaenCode
 */
interface ElementPedagogiqueAwareInterface
{
    /**
     * @param ElementPedagogique $elementPedagogique
     * @return self
     */
    public function setElementPedagogique( ElementPedagogique $elementPedagogique = null );



    /**
     * @return ElementPedagogique
     */
    public function getElementPedagogique();
}