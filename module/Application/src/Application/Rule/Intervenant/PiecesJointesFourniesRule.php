<?php

namespace Application\Rule\Intervenant;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Rule\AbstractRule;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Traits\IntervenantAwareTrait;
use Application\Entity\Db\IntervenantExterieur;
use Application\Service\TypePieceJointeStatut as TypePieceJointeStatutService;

/**
 * Description of PiecesJointesFourniesRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PiecesJointesFourniesRule extends AbstractRule implements ServiceLocatorAwareInterface, ContextProviderAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;
    use IntervenantAwareTrait;
    
    public function execute()
    {
        // liste des (types de) pièces justificatives déjà fournies
        $pjFournies = $this->getIntervenant()->getDossier()->getPieceJointe(); /* @var $pjFournies \Doctrine\Common\Collections\Collection */
        $typesFournis = array();
        foreach ($pjFournies as $pj) {
            $typesFournis[$pj->getType()->getId()] = $pj->getType();
        }
        
        $service = $this->getServiceTypePieceJointeStatut();
        
        // liste des (types de) pièces justificatives à fournir selon le statut d'intervenant
        $qb = $service->finderByStatutIntervenant($this->getIntervenant()->getStatut());
        $qb = $service->finderByPremierRecrutement($this->getIntervenant()->getDossier()->getPremierRecrutement(), $qb);
        $typesPieceJointeStatut = $service->getList($qb);
        
        // recherche des (types de) pièces justificatives obligatoires non fournies
        $typesNonFournis = array();
        foreach ($typesPieceJointeStatut as $tpjs) { /* @var $tpjs TypePieceJointeStatut */
            if (array_key_exists($tpjs->getType()->getId(), $typesFournis)) {
                continue;
            }
            if (!$tpjs->getObligatoire()) {
                continue;
            }
            $typesNonFournis[$tpjs->getType()->getId()] = $tpjs->getType();
        }
        
        if ($typesNonFournis) {
            $this->setMessage(sprintf("Les pièces justificatives suivantes n'ont pas été fournies par %s : %s", 
                    $this->getIntervenant(),
                    implode(", ", array_map('lcfirst', $typesNonFournis))));
            return false;
        }
        
        return true;
    }
    
    public function isRelevant()
    {
        return $this->getIntervenant() instanceof IntervenantExterieur && null !== $this->getIntervenant()->getDossier();
    }
    
    /**
     * @return TypePieceJointeStatutService
     */
    private function getServiceTypePieceJointeStatut()
    {
        return $this->getServiceLocator()->get('applicationTypePieceJointeStatut');
    }
}
