<?php

namespace Paiement\Assertion;

use Application\Service\Traits\ContextServiceAwareTrait;
use Unicaen\Framework\Authorize\AbstractAssertion;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use Paiement\Entity\Db\Dotation;
use Paiement\Entity\Db\TypeRessource;


/**
 * Description of BudgetAssertion
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class BudgetAssertion extends AbstractAssertion
{
    use ContextServiceAwareTrait;

    /**
     * Exemple
     */
    protected function assertEntity(?ResourceInterface $entity, ?string $privilege): bool
    {
        switch (true) {
            case $entity instanceof Dotation:
                return $this->assertStructure($entity->getStructure());

            case $entity instanceof TypeRessource:
                return true; // déjà filtré par ce qu'il y a dessus!!

            case $entity instanceof Structure:
                return $this->assertStructure($entity);
        }

        return true;
    }



    protected function assertStructure(Structure $structure): bool
    {
        $rs = $this->getServiceContext()->getStructure();

        return (!$rs || $structure->inStructure($rs));
    }

}