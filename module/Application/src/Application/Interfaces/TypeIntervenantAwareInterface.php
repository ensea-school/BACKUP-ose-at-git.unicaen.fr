<?php

namespace Application\Interfaces;

use Application\Entity\Db\TypeIntervenant;

/**
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
interface TypeIntervenantAwareInterface
{

    /**
     * Spécifie le type d'intervenant concerné.
     *
     * @param TypeIntervenant $typeIntervenant le type d'intervenant concerné
     * @return self
     */
    public function setTypeIntervenant(TypeIntervenant $typeIntervenant);

    /**
     * Retourne le type d'intervenant concerné.
     *
     * @return TypeIntervenant
     */
    public function getTypeIntervenant();
}