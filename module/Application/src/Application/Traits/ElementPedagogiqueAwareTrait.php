<?php

namespace Application\Traits;

use Application\Entity\Db\ElementPedagogique;

/**
 * Description of ElementPedagogiqueAwareTrait
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
trait ElementPedagogiqueAwareTrait
{
    /**
     * @var ElementPedagogique
     */
    protected $elementPedagogique;

    /**
     * Spécifie l'élément pédagogique concerné.
     *
     * @param ElementPedagogique $elementPedagogique ElementPedagogique concerné
     */
    public function setElementPedagogique(ElementPedagogique $elementPedagogique = null)
    {
        $this->elementPedagogique = $elementPedagogique;

        return $this;
    }
    
    /**
     * Retourne l'élément pédagogique concerné.
     *
     * @return ElementPedagogique
     */
    public function getElementPedagogique()
    {
        return $this->elementPedagogique;
    }
}