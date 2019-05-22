<?php

namespace Application\Controller;

use Application\Service\Traits\UtilisateurServiceAwareTrait;
use UnicaenAuth\Service\Traits\UserServiceAwareTrait;


/**
 * Description of AdministrationController
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class AdministrationController extends AbstractController
{
    use UtilisateurServiceAwareTrait;
    use UserServiceAwareTrait;



    public function indexAction()
    {
        return [];
    }



    public function changementMotDePasseAction()
    {
        $utilisateur = $this->getRequest()->getParam('utilisateur');
        $motDePasse  = $this->getRequest()->getParam('mot-de-passe');

        $userObject = $this->getServiceUtilisateur()->getByUsername($utilisateur);
        if (!$userObject) {
            throw new \Exception("Utilisateur $utilisateur non trouvé");
        }

        if (strlen($motDePasse) < 6){
            throw new \Exception("Mot de passe trop court : il doit faire au moint 6 caractères");
        }

        $this->userService->updateUserPassword( $userObject, $motDePasse);
    }
}