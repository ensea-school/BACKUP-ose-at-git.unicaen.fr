<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\CheminPedagogique;

/**
 * Description of CheminPedagogiqueAwareInterface
 *
 * @author UnicaenCode
 */
interface CheminPedagogiqueAwareInterface
{
    /**
     * @param CheminPedagogique $cheminPedagogique
     * @return self
     */
    public function setCheminPedagogique( CheminPedagogique $cheminPedagogique = null );



    /**
     * @return CheminPedagogique
     */
    public function getCheminPedagogique();
}