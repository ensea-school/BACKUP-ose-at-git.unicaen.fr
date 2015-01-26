<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Contrat;

/**
 * Règle métier déterminant si le contrat/avenant d'un intervenant peut être dévalidé.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutDevaliderContratRule extends \Application\Rule\AbstractRule
{
    use \Application\Service\Initializer\ContratServiceAwareTrait;
    use \Application\Traits\IntervenantAwareTrait;
    
    const MESSAGE_NON_VALIDE     = 'messageNonValide';
    const MESSAGE_CONTRAT_INIIAL = 'messageContratInitial';

    /**
     * Message template definitions
     * @var array
     */
    protected $messageTemplates = array(
        self::MESSAGE_NON_VALIDE     => "%value% n'est pas encore validé.",
        self::MESSAGE_CONTRAT_INIIAL => "Le contrat initial ne peut pas être dévalidé.",
    );
    
    private $contrat;
    
    public function __construct(Intervenant $intervenant, Contrat $contrat)
    {
        parent::__construct();
        $this->setIntervenant($intervenant);
        $this->contrat = $contrat;
    }
    
    public function execute()
    {
        $contratToString = $this->contrat->toString(true);
            
        if (!($validation = $this->contrat->getValidation())) {
            $this->message(self::MESSAGE_NON_VALIDE, $contratToString);
            return false;
        }
            
        if (!$this->contrat->estUnAvenant()) {
            $this->message(self::MESSAGE_CONTRAT_INIIAL);
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
        return true;
    }
}