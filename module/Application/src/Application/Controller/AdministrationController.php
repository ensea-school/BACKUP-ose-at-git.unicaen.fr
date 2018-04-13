<?php

namespace Application\Controller;

use Application\Service\Traits\UtilisateurServiceAwareTrait;


/**
 * Description of AdministrationController
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class AdministrationController extends AbstractController
{
    use UtilisateurServiceAwareTrait;



    /**
     *
     * @return type
     */
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

        $userObject->setPassword($motDePasse, true);
        $this->getServiceUtilisateur()->save($userObject);
    }
}