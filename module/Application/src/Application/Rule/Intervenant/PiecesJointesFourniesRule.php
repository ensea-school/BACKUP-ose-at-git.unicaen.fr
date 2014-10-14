<?php

namespace Application\Rule\Intervenant;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Rule\AbstractRule;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Traits\IntervenantAwareTrait;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\PieceJointe;
use Application\Service\PieceJointe as PieceJointeService;
use Application\Service\TypePieceJointeStatut as TypePieceJointeStatutService;

/**
 * Règle métier déterminant si des pièces justificatives existent pour un intervenant.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PiecesJointesFourniesRule extends AbstractRule implements ServiceLocatorAwareInterface, ContextProviderAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;
    use IntervenantAwareTrait;
    
    /**
     * 
     * @return boolean
     */
    public function execute()
    {
        // liste des PJ déjà fournies
        $pjFournies = $this->getPiecesJointesFournies();
        
        // liste des (types de) pièces justificatives déjà fournies
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
            if (!$tpjs->isObligatoire($this->totalHeuresReellesIntervenant)) {
                continue;
            }
            $typesNonFournis[$tpjs->getType()->getId()] = $tpjs->getType();
        }
        
        if ($typesNonFournis) {
            $this->setMessage(sprintf("Les pièces justificatives suivantes n'ont pas été fournies par %s : %s", 
                    $this->getIntervenant(),
                    implode(", ", $typesNonFournis)));
            return false;
        }
        
        return true;
    }
    
    /**
     * 
     * @return boolean
     */
    public function isRelevant()
    {
        return $this->getIntervenant() instanceof IntervenantExterieur && null !== $this->getIntervenant()->getDossier();
    }
    
    protected $piecesJointesFournies;
    
    /**
     * 
     * @return PieceJointe[] type_id => PieceJointe
     */
    public function getPiecesJointesFournies()
    {
        if (null === $this->piecesJointesFournies) {
            $qb = $this->getServicePieceJointe()->finderByDossier($this->getIntervenant()->getDossier());
            if (is_bool($this->getAvecFichier())) {
                $this->getServicePieceJointe()->finderByExistsFichier(true, $qb);
            }
            if (is_bool($this->getAvecValidation())) {
                $this->getServicePieceJointe()->finderByExistsValidation(true, $qb);
            }
            $piecesJointes = $qb->getQuery()->getResult();
            
            $this->piecesJointesFournies = [];
            foreach ($piecesJointes as $pj) { /* @var $pj PieceJointe */
                // NB: il ne peut y avoir qu'une seule pièce par type de pièce jointe
                $this->piecesJointesFournies[$pj->getType()->getId()] = $pj;
            }
        }
        
        return $this->piecesJointesFournies;
    }
    
    /**
     * Spécifie l'intervenant concerné.
     * 
     * @param Intervenant $intervenant Intervenant concerné
     * @return self
     */
    public function setIntervenant(Intervenant $intervenant)
    {
        $this->intervenant = $intervenant;
        
        $this->reset();
        
        return $this;
    }
    
    /**
     * @var float
     */
    protected $totalHeuresReellesIntervenant;
    
    /**
     * Spécifie le total d'heures réelles de l'intervenant.
     * Ce total est pris en compte pour déterminer le caractère obligatoire de certain type de PJ.
     * 
     * @param float $totalHeuresReellesIntervenant
     * @return self
     */
    public function setTotalHeuresReellesIntervenant($totalHeuresReellesIntervenant)
    {
        $this->totalHeuresReellesIntervenant = $totalHeuresReellesIntervenant;
        
        return $this;
    }

    /**
     * Réinitialise les variables de travail.
     * 
     * @return self
     */
    private function reset()
    {
        $this->piecesJointesFournies = null;
        
        return $this;
    }
    
    protected $avecFichier = null;
    
    /**
     * Retourne le flag indiquant s'il faut vérifier ou pas la présence/absence de fichier pour chaque pièce justificative.
     * 
     * @return boolean|null null : peu importe ; true : présence ; false : absence 
     */
    public function getAvecFichier()
    {
        return $this->avecFichier;
    }

    /**
     * Spécifie s'il faut vérifier ou pas la présence/absence de fichier pour chaque pièce justificative.
     * 
     * @param boolean|null $avecFichier null : peu importe ; true : présence ; false : absence 
     * @return self
     */
    public function setAvecFichier($avecFichier = true)
    {
        $this->avecFichier = $avecFichier;
        
        $this->reset();
        
        return $this;
    }
    
    protected $avecValidation = null;
    
    /**
     * Retourne le flag indiquant s'il faut vérifier ou pas la présence/absence de validation pour chaque pièce justificative.
     * 
     * @return boolean|null null : peu importe ; true : présence ; false : absence 
     */
    public function getAvecValidation()
    {
        return $this->avecValidation;
    }

    /**
     * Spécifie s'il faut vérifier ou pas la présence/absence de validation pour chaque pièce justificative.
     * 
     * @param boolean|null $avecValidation null : peu importe ; true : présence ; false : absence 
     * @return self
     */
    public function setAvecValidation($avecValidation = true)
    {
        $this->avecValidation = $avecValidation;
        
        $this->reset();
        
        return $this;
    }
    
    /**
     * @return PieceJointeService
     */
    private function getServicePieceJointe()
    {
        return $this->getServiceLocator()->get('applicationPieceJointe');
    }
    
    /**
     * @return TypePieceJointeStatutService
     */
    private function getServiceTypePieceJointeStatut()
    {
        return $this->getServiceLocator()->get('applicationTypePieceJointeStatut');
    }
}
