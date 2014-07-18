<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Contrat;

/**
 * Description of PeutDevaliderContratRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutDevaliderContratRule extends IntervenantRule
{
    use \Application\Service\Initializer\ContratServiceAwareTrait;
    
    private $contrat;
    
    public function __construct(Intervenant $intervenant, Contrat $contrat)
    {
        parent::__construct($intervenant);
        $this->contrat = $contrat;
    }
    
    public function execute()
    {
        $contratToString = $this->contrat->toString(true);
            
        if (!($validation = $this->contrat->getValidation())) {
            $this->setMessage("$contratToString n'est pas encore validé.");
            return false;
        }
            
        if (!$this->contrat->estUnAvenant()) {
            $this->setMessage("Le contrat initial ne peut pas être dévalidé.");
            return false;
        }
        
//        // Seul le contrat (si aucun avenant validé n'existe) et le dernier avenant validé 
//        // peuvent être dévalidé.
//        // Pourquoi ? Parce que sinon :
//        // - si la dévalidation porte sur un avenant, il faudrait renuméroter les avenants restants
//        // - si la dévalidation porte sur un contrat, il faudrait requalifier le 1er avenant en contrat 
//        // et renuméroter les avenants restants
//        // OR les contrat/avenants ont potentiellement déjà été signés.
//        $qb = $this->getContratService()->finderByIntervenant($this->contrat->getIntervenant());
//        $qb = $this->getContratService()->finderByValidation(true, $qb);
//        $alias = $this->getContratService()->getAlias();
//        $qb->orderBy("$alias.numeroAvenant", "DESC");
//        $contrats = $qb->getQuery()->getResult();
//        $contratDevalidable = reset($contrats);
//        $contratDevalidableToString = lcfirst($contratDevalidable->toString(true));
//        if ($this->contrat !== $contratDevalidable) {
//            $this->setMessage("Il faudrait d'abord dévalider $contratDevalidableToString.");
//            return false;
//        }
        
        return true;
    }
    
    public function isRelevant()
    {
        return $this->getIntervenant()->getStatut()->estVacataire();
    }
}