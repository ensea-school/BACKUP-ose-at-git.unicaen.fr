<?php

namespace Application\Traits;

use Application\Entity\Db\TypeHeures;

/**
 * Description of TypeHeuresAwareTrait
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
trait TypeHeuresAwareTrait
{
    /**
     * @var TypeHeures
     */
    protected $typeHeures;

    /**
     * Spécifie le type d'heures.
     *
     * @param TypeHeures $typeHeures le type d'heures
     */
    public function setTypeHeures(TypeHeures $typeHeures = null)
    {
        $this->typeHeures = $typeHeures;

        return $this;
    }

    /**
     * Retourne le type d'heures.
     *
     * @return TypeHeures
     */
    public function getTypeHeures()
    {
        return $this->typeHeures;
    }
}