<?php

namespace Application\Controller;

use Application\Acl\Role;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\UtilisateurServiceAwareTrait;
use Lieu\Service\StructureServiceAwareTrait;
use UnicaenAuthentification\Controller\UtilisateurController as BaseController;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;


class UtilisateurController extends BaseController
{
    use ContextServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UtilisateurServiceAwareTrait;
    use UserServiceAwareTrait;


    /**
     * Traite les requêtes AJAX POST de sélection d'un profil utilisateur.
     * La sélection est mémorisé en session par le service UserContext.
     */
    public function selectionnerProfilAction($addFlashMessage = true)
    {
        parent::selectionnerProfilAction($addFlashMessage = false);

        $role = $this->getServiceContext()->getSelectedIdentityRole();
        /* @var $role Role */
        $structureId = $this->getRequest()->getPost('structure-' . $role->getRoleId());

        if ($role->getPerimetre() && $role->getPerimetre()->isEtablissement()) {
            $structure = null;
            if ($structureId) {
                $structure = $this->getServiceStructure()->get($structureId);
            }
            $this->getServiceContext()->setStructure($structure);

            $message = sprintf("Vous endossez à présent le profil utilisateur <strong>%s</strong>%s.",
                $role->getRoleName(),
                $structure ? " pour la structure <strong>$structure</strong>" : null);
        } else {
            $message = sprintf("Vous endossez à présent le profil utilisateur <strong>%s</strong>.", $role);
            if ($s = $role->getStructure()) {
                $this->getServiceContext()->setStructure($s);
            }
        }

        $this->flashMessenger()->addSuccessMessage($message);

        exit;
    }



    public function changementMotDePasseAction()
    {
        $utilisateur = $this->getRequest()->getParam('utilisateur');
        $motDePasse  = $this->getRequest()->getParam('mot-de-passe');

        $userObject = $this->getServiceUtilisateur()->getByUsername($utilisateur);

        if (!$userObject) {
            throw new \Exception("Utilisateur $utilisateur non trouvé");
        }

        $this->getServiceUtilisateur()->changerMotDePasse($userObject, $motDePasse);
    }



    public function creationAction()
    {
        $data                     = $this->getRequest()->getParam('data');
        $data                     = json_decode(base64_decode($data));
        $data->{'date-naissance'} = \DateTime::createFromFormat('d/m/Y', $data->{'date-naissance'});

        $this->getServiceUtilisateur()->creerUtilisateur(
            $data->nom,
            $data->prenom,
            $data->{'date-naissance'},
            $data->login,
            $data->{'mot-de-passe'},
            (array)$data->params
        );
    }
}