<?php

namespace Application\Interfaces;

use Application\Entity\Db\TypeHeures;

/**
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
interface TypeHeuresAwareInterface
{

    /**
     * Spécifie le type d'heures.
     *
     * @param TypeHeures $typeHeures le type d'heures
     * @return self
     */
    public function setTypeHeures(TypeHeures $typeHeures = null);

    /**
     * Retourne le type d'heures.
     *
     * @return TypeHeures
     */
    public function getTypeHeures();
}