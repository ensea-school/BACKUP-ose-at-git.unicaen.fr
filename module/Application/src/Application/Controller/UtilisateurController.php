<?php

namespace Application\Controller;

use UnicaenAuth\Controller\UtilisateurController as BaseController;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class UtilisateurController extends BaseController
{
    use \Application\Service\Traits\ContextAwareTrait;
    
    /**
     * Traite les requêtes AJAX POST de sélection d'un profil utilisateur.
     * La sélection est mémorisé en session par le service AuthUserContext.
     */
    public function selectionnerProfilAction($addFlashMessage = true)
    {
        parent::selectionnerProfilAction($addFlashMessage = false);
        
        $role        = $this->getAuthUserContextService()->getSelectedIdentityRole();
        $structureId = $this->getRequest()->getPost('structure');
        
        if ($role instanceof \Application\Acl\AdministrateurRole) {
            $structure = null;
            if ($structureId) {
                $structure = $this->getServiceLocator()->get('ApplicationStructure')->get($structureId);
            }
            $this->getServiceContext()->setStructure($structure);

            $message = sprintf("Vous endossez à présent le profil utilisateur <strong>%s</strong>%s.",
                    $role->getRoleName(),
                    $structure ? " pour la structure <strong>$structure</strong>" : null); 
        }
        else {
            $message = sprintf("Vous endossez à présent le profil utilisateur <strong>%s</strong>.", $role);
        }
        
        $this->flashMessenger()->addSuccessMessage($message);
        
        exit;
    }
}