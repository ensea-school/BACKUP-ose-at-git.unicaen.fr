<?php

namespace Application\Assertion;

use Application\Interfaces\StructureAwareInterface;
use Application\Entity\Db\ServiceAPayerInterface;
use Application\Entity\Db\MiseEnPaiement;
use Zend\Permissions\Acl;

/**
 * Description of MiseEnPaiementAssertion
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class MiseEnPaiementAssertion extends AbstractAssertion
{
    const PRIVILEGE_VISUALISATION      = 'visualisation';
    const PRIVILEGE_DEMANDE            = 'demande';
    const PRIVILEGE_VALIDATION         = 'validation';
    const PRIVILEGE_MISE_EN_PAIEMENT   = 'mise-en-paiement';


    protected function assertEntity(Acl\Acl $acl, Acl\Role\RoleInterface $role = null, Acl\Resource\ResourceInterface $entity = null, $privilege = null)
    {
        if ($entity instanceof MiseEnPaiement)
            return $this->assertEntityMiseEnPaiement($entity);

        return true;
    }

    protected function assertEntityMiseEnPaiement( MiseEnPaiement $miseEnPaiement )
    {
        if ($miseEnPaiement->getValidation() && $this->privilege == self::PRIVILEGE_DEMANDE){
            return false; // pas de nouvelle demande si la mise en paiement est déjà validée
        }

        if ($serviceAPayer = $miseEnPaiement->getServiceAPayer()){
            return $this->assertResourceServiceAPayer($serviceAPayer);
        }else{
            return true; // pas assez d'éléments pour statuer
        }
    }

    protected function assertResourceServiceAPayer( ServiceAPayerInterface $serviceAPayer )
    {
        $oriStructure = ($this->role instanceof StructureAwareInterface) ? $this->role->getStructure() : null;
        $destStructure = $serviceAPayer->getStructure();
        if (empty($oriStructure) || empty($destStructure)){
            return true; // pas essez d'éléments pour statuer
        }else{
            return $oriStructure === $destStructure;
        }
    }
}