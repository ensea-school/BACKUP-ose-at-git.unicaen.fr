<?php

namespace Application\Interfaces;

use Application\Entity\Db\ElementPedagogique;

/**
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
interface ElementPedagogiqueAwareInterface
{

    /**
     * Spécifie l'élément pédagogique concerné.
     *
     * @param ElementPedagogique $elementPedagogique l'élément pédagogique concerné
     * @return self
     */
    public function setElementPedagogique(ElementPedagogique $elementPedagogique);

    /**
     * Retourne l'élément pédagogique concerné.
     *
     * @return ElementPedagogique
     */
    public function getElementPedagogique();
}