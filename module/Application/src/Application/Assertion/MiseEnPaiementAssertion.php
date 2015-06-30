<?php

namespace Application\Assertion;

use Application\Entity\Db\ServiceAPayerInterface;
use Application\Entity\Db\MiseEnPaiement;
use Application\Entity\Db\Privilege;
use Zend\Permissions\Acl\Acl;
use Application\Acl\Role;
use Zend\Permissions\Acl\Role\RoleInterface;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Doctrine\ORM\Query\Expr\Join;

/**
 * Description of MiseEnPaiementAssertion
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class MiseEnPaiementAssertion extends AbstractAssertion
{
    use \Application\Service\Traits\TypeValidationAwareTrait;
    use \Application\Service\Traits\ValidationAwareTrait;
    use \Application\Service\Traits\ServiceAwareTrait;
    use \Application\Service\Traits\ServiceReferentielAwareTrait;
    use \Application\Service\Traits\TypeVolumeHoraireAwareTrait;
    
    use \UnicaenApp\Service\MessageCollectorAwareTrait;

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
        if (! $this->checkValidationRealise($miseEnPaiement)) {
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
        $intervenant = $miseEnPaiement->getServiceAPayer()->getIntervenant();
        
        // la clôture de la saisie du réalisé n'a pas de sens pour un vacataire
        if (! $intervenant->estPermanent()) {
            return true;
        }
        
        $cloture = $this->getServiceValidation()->findValidationClotureServices($intervenant, null);
        
        // la clôture de la saisie du réalisé doit être faite
        if (! $cloture) {
            $this->getServiceMessageCollector()->addMessage("La demande de mise en paiement est impossible tant que la saisie des enseignements et référentiel réalisés n'est pas clôturée.", 'danger');
            return false;
        }
        
        return true;
    }
    
    /**
     * Pour les permanents, pas de demande de MEP possible si le moindre
     * enseignement ou référentiel réalisé n'est pas validé.
     * 
     * @param MiseEnPaiement $miseEnPaiement
     * @return boolean
     */
    private function checkValidationRealise(MiseEnPaiement $miseEnPaiement)
    {
        $intervenant = $miseEnPaiement->getServiceAPayer()->getIntervenant();
        
        // on ne s'intéresse pas aux vacataires
        if (! $intervenant->estPermanent()) {
            return true;
}
        
        $tvhRealise = $this->getServiceTypeVolumeHoraire()->getRealise();
        
        /**
         * Recherche d'enseignement non validé.
         */
        $alias = $this->getServiceService()->getAlias();
        $qb = $this->getServiceService()->finderByIntervenant($intervenant);
        $this->getServiceService()->finderByTypeVolumeHoraire($tvhRealise, $qb);
        $qb
                ->select("COUNT($alias)")
                ->leftJoin("vh.validation", "val", Join::WITH, "1 = compriseEntre(val.histoCreation, val.histoDestruction)")
                ->andWhere("vh.validation IS EMPTY");
        $count = (int) $qb->getQuery()->getSingleScalarResult();
        if ($count) {
            $this->getServiceMessageCollector()->addMessage("La demande de mise en paiement est impossible tant qu'il existe des enseignements réalisés non validés.", 'danger');
            return false;
        }
        
        /**
         * Recherche de référentiel non validé.
         */
        $alias = $this->getServiceServiceReferentiel()->getAlias();
        $qb = $this->getServiceServiceReferentiel()->finderByIntervenant($intervenant);
        $this->getServiceServiceReferentiel()->finderByTypeVolumeHoraire($tvhRealise, $qb);
        $qb
                ->select("COUNT($alias)")
                ->leftJoin("vhr.validation", "val", Join::WITH, "1 = compriseEntre(val.histoCreation, val.histoDestruction)")
                ->andWhere("vhr.validation IS EMPTY");
        $count = (int) $qb->getQuery()->getSingleScalarResult();
        if ($count) {
            $this->getServiceMessageCollector()->addMessage("La demande de mise en paiement est impossible tant qu'il existe du référentiel réalisé non validé.", 'danger');
            return false;
        }
        
        return true;
    }
}