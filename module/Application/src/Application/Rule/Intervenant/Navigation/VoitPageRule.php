<?php

namespace Application\Rule\Intervenant\Navigation;

use Application\Rule\Intervenant\IntervenantRule;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\IntervenantExterieur;
use Application\Rule\Intervenant\PossedeDossierRule;
use Application\Rule\Intervenant\PossedeServicesRule;

/**
 * Description of VoitPageRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class VoitPageRule extends IntervenantRule
{
    use \Application\Traits\AnneeAwareTrait;
    
    const ROUTE_SERVICE        = 'intervenant/services';
    const ROUTE_PIECES_JOINTES = 'intervenant/pieces-jointes';
    
    private $page;
    
    public function __construct(Intervenant $intervenant, array $page)
    {
        parent::__construct($intervenant);
        $this->page = $page;
    }
    
    public function execute()
    {
        if (!isset($this->page['route']) || !in_array($this->page['route'], array(self::ROUTE_SERVICE, self::ROUTE_PIECES_JOINTES))) {
            return true;
        }
        if (!$this->getIntervenant() instanceof IntervenantExterieur) {
            return true;
        }
        
        switch ($this->page['route']) {
            /**
             * Page des enseignements
             */
            case self::ROUTE_SERVICE:
                $possedeDossier = new PossedeDossierRule($this->getIntervenant());
                if (!$possedeDossier->execute()) {
                    $this->setMessage("%s n'a pas encore enregistré ses données personnelles.");
                    return false;
                }
                break;
            /**
             * Page des pièces justificatives
             */
            case self::ROUTE_PIECES_JOINTES:
                $possedeServices = new PossedeServicesRule($this->getIntervenant());
                $possedeServices->setAnnee($this->getAnnee());
                if (!$possedeServices->execute()) {
                    $this->setMessage("%s n'a pas encore enregistré ses enseignements.");
                    return false;
                }
                break;
                
            default:
                break;
        }

        return true;
    }
    
    public function isRelevant()
    {
        return true;
    }
}
