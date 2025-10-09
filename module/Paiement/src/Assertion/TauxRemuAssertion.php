<?php

namespace Paiement\Assertion;

use Application\Entity\Db\Agrement;
use Application\Provider\Privileges;
use Unicaen\Framework\Authorize\AbstractAssertion;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Paiement\Entity\Db\TauxRemu;


/**
 * Description of TauxRemuAssertion
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TauxRemuAssertion extends AbstractAssertion
{

    /**
     * Exemple
     */
    protected function assertEntity(?ResourceInterface $entity = null, $privilege = null): bool
    {
        switch (true) {
            case
                $entity instanceof TauxRemu:
                switch ($privilege) {
                    case Privileges::TAUX_SUPPRESSION:
                        return $this->assertTauxRemuSuppression($entity);
                    case Privileges::TAUX_EDITION:
                        return $this->assertTauxRemuEdition($entity);
                }
        }

        return true;
    }



    private function assertTauxRemuEdition(TauxRemu $entity): bool
    {
        return !$entity->isDefaut();
    }



    private function assertTauxRemuSuppression(TauxRemu $entity): bool
    {
        return !$entity->isDefaut() & !$entity->hasChildren();
    }

}

