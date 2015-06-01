<?php

namespace Application\Assertion;

use Application\Entity\Db\ServiceAPayerInterface;
use Application\Entity\Db\MiseEnPaiement;
use Application\Entity\Db\Privilege;
use Application\Entity\Db\TypeValidation;
use Zend\Permissions\Acl\Acl;
use Application\Acl\Role;
use Zend\Permissions\Acl\Role\RoleInterface;
use Zend\Permissions\Acl\Resource\ResourceInterface;


/**
 * Description of MiseEnPaiementAssertion
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class MiseEnPaiementAssertion extends AbstractAssertion
{
    use \Application\Service\Traits\TypeValidationAwareTrait;
    use \Application\Service\Traits\ValidationAwareTrait;
    
    const PRIVILEGE_VISUALISATION      = 'visualisation';
    const PRIVILEGE_DEMANDE            = 'demande';
    const PRIVILEGE_VALIDATION         = 'validation';
    const PRIVILEGE_MISE_EN_PAIEMENT   = 'mise-en-paiement';


    protected function assertEntity(Acl $acl, RoleInterface $role = null, ResourceInterface $entity = null, $privilege = null)
    {
        if (! $role instanceof Role) return false;

        if ($entity instanceof MiseEnPaiement){
            switch ($privilege){
                case Privilege::MISE_EN_PAIEMENT_DEMANDE:
                    return $this->assertMiseEnPaiementDemande($role, $entity);
            }
        }else if ($entity instanceof ServiceAPayerInterface){
            switch ($privilege){
                case Privilege::MISE_EN_PAIEMENT_DEMANDE:
                    return $this->assertServiceAPayerDemande( $role, $entity );
            }
        }
        return true;
    }

    protected function assertMiseEnPaiementDemande( Role $role, MiseEnPaiement $miseEnPaiement )
    {
        if (! $this->checkClotureRealise($miseEnPaiement)) {
            return false;
        }
        
        if ($miseEnPaiement->getValidation()){
            return false; // pas de nouvelle demande si la mise en paiement est déjà validée
        }

        if ($serviceAPayer = $miseEnPaiement->getServiceAPayer()){
            return $this->assertServiceAPayerDemande( $role, $serviceAPayer );
        }else{
            return true; // pas assez d'éléments pour statuer
        }
    }

    protected function assertServiceAPayerDemande( Role $role, ServiceAPayerInterface $serviceAPayer )
    {
        $oriStructure  = $role->getStructure();
        $destStructure = $serviceAPayer->getStructure();
        
        if (empty($oriStructure) || empty($destStructure)){
            return true; // pas essez d'éléments pour statuer
        }else{
            return $oriStructure === $destStructure;
        }
    }
    
    /**
     * Pour les permanents, pas de demande de MEP possible sans clôture du service réalisé.
     * 
     * @param MiseEnPaiement $miseEnPaiement
     * @return boolean
     */
    private function checkClotureRealise(MiseEnPaiement $miseEnPaiement)
    {
        $intervenant = $miseEnPaiement->getFormuleResultatService()->getFormuleResultat()->getIntervenant();
        
        // la clôture de la saisie du réalisé n'a pas de sens pour un vacataire
        if (! $intervenant->estPermanent()) {
            return true;
        }
        
        $cloture = $this->getServiceValidation()->findValidationClotureServices(
                $intervenant, 
                $this->getServiceTypeValidation()->getByCode(TypeValidation::CODE_CLOTURE_REALISE));
        
        // la clôture de la saisie du réalisé doit être faite
        if (! $cloture) {
            return false;
        }
        
        return true;
    }
}