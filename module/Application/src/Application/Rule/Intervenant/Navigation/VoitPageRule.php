<?php

namespace Application\Rule\Intervenant\Navigation;

use Application\Rule\Intervenant\IntervenantRule;
use Application\Entity\Db\Intervenant;
use Application\Service\Workflow\AbstractWorkflow;

/**
 * Description of VoitPageRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class VoitPageRule extends IntervenantRule
{
    use \Application\Traits\AnneeAwareTrait;
    
    /**
     * @var array
     */
    private $page;
    
    /**
     * @var AbstractWorkflow
     */
    private $wf;
    
    /**
     * 
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @param array $page
     * @param \Application\Service\Workflow\AbstractWorkflow $wf
     */
    public function __construct(Intervenant $intervenant, array $page, AbstractWorkflow $wf)
    {
        parent::__construct($intervenant);
        
        $this->page = $page;
        $this->wf   = $wf;
    }
    
    /**
     * @see \Application\Rule\AbstractRule
     */
    public function execute()
    {
        if (!isset($this->page['route'])) {
            return true;
        }
        
        $route = $this->page['route'];

        // recherche dans le workflow de l'étape correspondant à la route de la page
        $step = $this->wf->getStepForRoute($route);
        if (null === $step) {
            // si aucune étape correspondante n'est trouvée, on masque la page
            $this->setMessage("Aucune formation trouvée correspondant à la route '$route'.");
            return false;
        }
        
        // la page est masquée si elle correspond à une étape située après l'étape courante
//        if ($step !== $this->wf->getCurrentStep() && !$this->wf->isStepBeforeCurrentStep($step)) {
        if ($this->wf->isStepAfterCurrentStep($step)) {
            $this->setMessage("%s ne peut pas encore accéder à cette formation.");
            return false;
        }
        
        return true;
    }
    
    public function isRelevant()
    {
        return true;
    }
}
