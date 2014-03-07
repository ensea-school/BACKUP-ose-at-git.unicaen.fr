<?php

namespace Common\ORM\Filter;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;

/**
 * Description of HistoriqueFilter
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class HistoriqueFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetaData $targetEntity, $targetTableAlias)
    {
        // Check if the entity implements the required interface
        if (!$targetEntity->reflClass->implementsInterface('Application\Entity\Db\HistoriqueAwareInterface')) {
            return "";
        }

        return "$targetTableAlias.HISTO_DESTRUCTION IS NULL AND $targetTableAlias.HISTO_DESTRUCTEUR_ID IS NULL";
    }
}