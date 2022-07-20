<?php

namespace Application\ORM\Filter;

use Application\Service\Traits\ContextServiceAwareTrait;
use Doctrine\ORM\Mapping\ClassMetaData;

/**
 * Description of HistoriqueFilter
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class HistoriqueFilter extends AbstractFilter
{

    public function addFilterConstraint(ClassMetaData $targetEntity, $targetTableAlias): string
    {
        // Check if the entity implements the required interface
        if (!$targetEntity->reflClass->implementsInterface('UnicaenApp\Entity\HistoriqueAwareInterface')) {
            return "";
        }

        if ($this->isEnabled($targetEntity)) {
            return $targetTableAlias . '.HISTO_DESTRUCTION IS NULL';
        } else {
            return '';
        }
    }

}