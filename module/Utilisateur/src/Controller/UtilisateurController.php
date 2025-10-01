<?php

namespace Utilisateur\Controller;

use Application\Service\Traits\ContextServiceAwareTrait;
use Framework\User\UserManager;
use Laminas\View\Model\JsonModel;
use Lieu\Entity\Db\Structure;
use Lieu\Service\StructureServiceAwareTrait;
use UnicaenAuthentification\Controller\UtilisateurController as BaseController;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Utilisateur\Entity\Db\Role;
use Utilisateur\Service\UtilisateurServiceAwareTrait;


class UtilisateurController extends BaseController
{
    use ContextServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UtilisateurServiceAwareTrait;
    use UserServiceAwareTrait;

    public function __construct(
        private readonly UserManager $userManager,
    )
    {
    }



    /**
     * Traite les requêtes AJAX POST de sélection d'un profil utilisateur.
     * La sélection est mémorisé en session par le service UserContext.
     */
    public function selectionnerProfilAction($addFlashMessage = true)
    {
        $roleId = $this->axios()->fromPost('role');
        $structureId = $this->axios()->fromPost('structure');

        $this->userManager->setProfile($roleId);

        $profile = $this->userManager->getProfile();

        /** @var Role $role */
        $role = $profile->getContext('role');

        if ($role) {
            if ($role->getPeutChangerStructure()) {
                $structure = $this->getServiceStructure()->get($structureId);
            } else {
                /** @var Structure $structure */
                $structure = $profile->getContext('structure');
            }
        }else{
            // intervenant
            $structure = null;
        }

        $this->getServiceContext()->setStructure($structure);

        if ($role) {
            $message = sprintf("Vous endossez à présent le profil utilisateur <strong>%s</strong>.", $role->getLibelle());
        }else{
            $message = sprintf("Vous endossez à présent le profil intervenant <strong>%s</strong>.", $profile->getDisplayName());
        }
        if ($structure){
            $message .= sprintf(' pour la structure <strong>%s</strong>', $structure);
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



    public function rechercheAction()
    {

        if (!($term = $this->params()->fromQuery('term'))) {
            return new JsonModel([]);
        }

        $result = $this->getServiceUtilisateur()->rechercheUtilisateurs($term);

        return new JsonModel($result);
    }
}