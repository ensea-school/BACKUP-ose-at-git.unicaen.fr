<?php

namespace Application\Service;

use Application\Rule\Intervenant\Navigation\VoitPageRule;
use Application\Service\Workflow\WorkflowIntervenantAwareTrait;
use Application\Service\Workflow\WorkflowIntervenantAwareInterface;

/**
 * Service chargé de déterminer si une page de navigation doit être visible ou non
 * en fonction de l'état courant du workflow Intervenant. 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class IntervenantNavigationPageVisibility extends AbstractService implements WorkflowIntervenantAwareInterface
{
    use WorkflowIntervenantAwareTrait;
    
    public function __invoke(array &$page)
    {
        $role  = $this->getServiceContext()->getSelectedIdentityRole();
        
        if ($role instanceof \Application\Acl\IntervenantRole) {
            $intervenant = $role->getIntervenant();
            $wf = $this->getWorkflowIntervenant()->setIntervenant($intervenant);
            
            return $this->isPageVisible($intervenant, $page, $wf, $intervenant->getAnnee());
        }
        
        return true;
    }
    
    private function isPageVisible($intervenant, $page, $wf, $annee)
    {
        $voitPage = new VoitPageRule($intervenant, $page, $wf);
//        $voitPage->setAnnee($annee);

        if (!$voitPage->execute()) {
            // si une page fille est visible alors la page mère est quand même visible
            if (isset($page['pages'])) {
                foreach ($page['pages'] as $subpage) {
                    if ($this->isPageVisible($intervenant, $subpage, $wf, $annee)) {
                        return true;
                    }
                }
            }

            return false;
        }
        
        return true;
    }
}