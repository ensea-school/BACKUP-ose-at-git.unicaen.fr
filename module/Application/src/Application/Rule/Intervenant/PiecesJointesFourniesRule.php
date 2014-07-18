<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\IntervenantExterieur;
use Application\Service\TypePieceJointeStatut;

/**
 * Description of PiecesJointesFournies
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PiecesJointesFourniesRule extends IntervenantRule
{
    /**
     * @var TypePieceJointeStatut
     */
    private $serviceTypePieceJointeStatut;
    
    public function __construct(Intervenant $intervenant, TypePieceJointeStatut $serviceTypePieceJointeStatut)
    {
        parent::__construct($intervenant);
        $this->serviceTypePieceJointeStatut = $serviceTypePieceJointeStatut;
    }
    
    public function execute()
    {
        // liste des (types de) pièces justificatives déjà fournies
        $pjFournies = $this->getIntervenant()->getDossier()->getPieceJointe(); /* @var $pjFournies \Doctrine\Common\Collections\Collection */
        $typesFournis = array();
        foreach ($pjFournies as $pj) {
            $typesFournis[$pj->getType()->getId()] = $pj->getType();
        }
        
        // liste des (types de) pièces justificatives à fournir selon le statut d'intervenant
        $qb = $this->serviceTypePieceJointeStatut->finderByStatutIntervenant($this->getIntervenant()->getStatut());
        $qb = $this->serviceTypePieceJointeStatut->finderByPremierRecrutement($this->getIntervenant()->getDossier()->getPremierRecrutement(), $qb);
        $typesPieceJointeStatut = $this->serviceTypePieceJointeStatut->getList($qb);
        
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
}
