<?php

namespace Common\ORM\Filter;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;

/**
 * Description of ValiditeFilter
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ValiditeFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetaData $targetEntity, $targetTableAlias)
    {
        // Check if the entity implements the required interface
        if (!$targetEntity->reflClass->implementsInterface('Application\Entity\Db\ValiditeAwareInterface')) {
            return "";
        }

        $this->setParameter('now', new \DateTime());
        
        return sptrintf("%s BETWEEN %s AND NVL(%s, %s)",
                $this->getParameter('now'),
                "$targetTableAlias.VALIDITE_DEBUT",
                "$targetTableAlias.VALIDITE_FIN",
                $this->getParameter('now'));
    }
}