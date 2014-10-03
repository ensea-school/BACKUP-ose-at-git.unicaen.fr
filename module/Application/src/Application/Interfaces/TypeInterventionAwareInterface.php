<?php

namespace Application\Interfaces;

use Application\Entity\Db\TypeIntervention;

/**
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
interface TypeInterventionAwareInterface
{

    /**
     * Spécifie le type d'intervention concerné.
     *
     * @param TypeIntervention $typeIntervention Type de rôle concernée
     */
    public function setTypeIntervention(TypeIntervention $typeIntervention = null);

    /**
     * Retourne le type d'intervention concerné.
     *
     * @return TypeIntervention
     */
    public function getTypeIntervention();
}