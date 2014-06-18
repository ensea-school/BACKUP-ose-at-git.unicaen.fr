<?php

namespace Application\Service;

use Application\Rule\Intervenant\Navigation\VoitPageRule;

/**
 * Description of NavigationPageVisibility
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class NavigationPageVisibility extends AbstractService
{
    public function __invoke(array &$page)
    {
        $role  = $this->getContextProvider()->getSelectedIdentityRole();
        $annee = $this->getContextProvider()->getGlobalContext()->getAnnee();
        
        if ($role instanceof \Application\Acl\IntervenantRole) {
            $intervenant    = $role->getIntervenant();
            $voitPage = new VoitPageRule($intervenant, $page);
            $voitPage->setAnnee($annee);
            if (!$voitPage->execute()) {
                return false;
            }
        }
        
        return true;
    }
}