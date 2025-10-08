<?php

namespace Application\ORM\Filter;

use Doctrine\ORM\Mapping\ClassMetaData;

/**
 * Description of AnneeFilter
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class AnneeFilter extends AbstractFilter
{

    public function addFilterConstraint(ClassMetaData $targetEntity, $targetTableAlias): string
    {
        if (!$targetEntity->hasAssociation('annee')) {
            return '';
        }

        if ($this->isEnabled($targetEntity)) {
            return $targetTableAlias . '.ANNEE_ID = ' . $this->getServiceContext()->getAnnee()->getId();
        }

        return '';
    }
}