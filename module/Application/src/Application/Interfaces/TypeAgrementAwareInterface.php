<?php

namespace Application\Interfaces;

use Application\Entity\Db\TypeAgrement;

/**
 *
 * @author Bertrand
 */
interface TypeAgrementAwareInterface
{

    /**
     * Spécifie le type d'agrément concerné.
     *
     * @param TypeAgrement $typeAgrement Type d'agrément concerné
     */
    public function setTypeAgrement(TypeAgrement $typeAgrement = null);

    /**
     * Retourne le type d'agrément concerné.
     *
     * @return TypeAgrement
     */
    public function getTypeAgrement();
}