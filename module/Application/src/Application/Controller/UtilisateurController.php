<?php

namespace Application\Controller;

use Application\Acl\Role;
use UnicaenAuth\Controller\UtilisateurController as BaseController;

use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\StructureAwareTrait;

/**
 *
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class UtilisateurController extends BaseController
{
    use ContextServiceAwareTrait;
    use StructureAwareTrait;


    /**
     * Traite les requêtes AJAX POST de sélection d'un profil utilisateur.
     * La sélection est mémorisé en session par le service AuthUserContext.
     */
    public function selectionnerProfilAction($addFlashMessage = true)
    {
        parent::selectionnerProfilAction($addFlashMessage = false);

        $role        = $this->getAuthUserContextService()->getSelectedIdentityRole();
        /* @var $role Role */
        $structureId = $this->getRequest()->getPost('structure-'.$role->getRoleId());

        if ($role->getPerimetre() && $role->getPerimetre()->isEtablissement()) {
            $structure = null;
            if ($structureId) {
                $structure = $this->getServiceStructure()->get($structureId);
            }
            $this->getServiceContext()->setStructure($structure);

            $message = sprintf("Vous endossez à présent le profil utilisateur <strong>%s</strong>%s.",
                    $role->getRoleName(),
                    $structure ? " pour la structure <strong>$structure</strong>" : null);
        }
        else {
            $message = sprintf("Vous endossez à présent le profil utilisateur <strong>%s</strong>.", $role);
            if ($s = $role->getStructure()){
                $this->getServiceContext()->setStructure($s);
            }
        }

        $this->flashMessenger()->addSuccessMessage($message);

        exit;
    }
}