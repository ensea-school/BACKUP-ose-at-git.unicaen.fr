<?php

namespace Utilisateur\Controller;

use Application\Controller\AbstractController;
use Application\Service\Traits\ContextServiceAwareTrait;
use Laminas\Authentication\AuthenticationService;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Unicaen\Framework\Authorize\Authorize;
use Unicaen\Framework\User\UserManager;
use Laminas\View\Model\JsonModel;
use Lieu\Entity\Db\Structure;
use Lieu\Service\StructureServiceAwareTrait;
use UnicaenAuthentification\Authentication\SessionIdentity;
use UnicaenAuthentification\Authentication\Storage\Usurpation;
use UnicaenAuthentification\Service\UserContext;
use Utilisateur\Connecteur\LdapConnecteur;
use Utilisateur\Entity\Db\Role;
use Utilisateur\Service\UtilisateurServiceAwareTrait;


class UtilisateurController extends AbstractController
{
    use ContextServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UtilisateurServiceAwareTrait;

    public function __construct(
        private readonly UserManager           $userManager,
        private readonly UserContext           $userContext,
        private readonly LdapConnecteur        $ldap,
        private readonly AuthenticationService $authenticationService,
    )
    {
    }



    public function selectionnerProfilAction($addFlashMessage = true): JsonModel
    {
        $roleId      = $this->axios()->fromPost('role');
        $structureId = $this->axios()->fromPost('structure');
        $route       = $this->axios()->fromPost('route');

        $this->userManager->setProfile($roleId);

        $profile = $this->userManager->getProfile();

        /** @var Role $role */
        $role = $profile->getContext('role');

        if ($role) {
            if ($role->getPeutChangerStructure()) {
                $structure = $this->getServiceStructure()->get($structureId);
                $profile->setContext('structure', $structure);
            } else {
                /** @var Structure $structure */
                $structure = $profile->getContext('structure');
            }
        } else {
            // intervenant
            $structure = null;
        }

        if ($role) {
            $message = sprintf("Vous endossez à présent le profil utilisateur <strong>%s</strong>.", $role->getLibelle());
        } else {
            $message = sprintf("Vous endossez à présent le profil intervenant <strong>%s</strong>.", $profile->getDisplayName());
        }
        if ($structure) {
            $message .= sprintf(' pour la structure <strong>%s</strong>', $structure);
        }
        $this->flashMessenger()->addSuccessMessage($message);

        $needGoHome = !$this->isAllowed(Authorize::routeResource($route));

        $data = [
            'data' => [
                'needGoHome' => $needGoHome,
            ],
        ];

        return new JsonModel($data);
    }



    public function usurperIdentiteAction(): Response
    {
        $request = $this->getRequest();
        if (!$request instanceof Request) {
            exit(1);
        }

        $usernameUsurpe = $request->getQuery('identity', $request->getPost('identity'));
        if (!$usernameUsurpe) {
            return $this->redirect()->toRoute('home');
        }


        $utilisateurUsurpe = $this->ldap->getUtilisateur($usernameUsurpe);

        if ($utilisateurUsurpe === null) {
            $this->flashMessenger()->addErrorMessage(
                "La demande d'usurpation du compte '$usernameUsurpe' a échoué car aucun compte utilisateur correspondant " .
                "n'a été trouvé."
            );
            return $this->redirect()->toRoute('home');
        }

        $this->userContext->usurperIdentite($usernameUsurpe);

        return $this->redirect()->toRoute('home');
    }



    public function stopperUsurpationAction(): Response
    {
        $currentIdentityArray = $this->userContext->getIdentity();
        $usurpateur           = $currentIdentityArray[Usurpation::TYPE][Usurpation::KEY_USURPATEUR];
        $sessionIdentity      = SessionIdentity::newInstance($usurpateur->getUsername(), $this->userContext->getAuthenticationType());
        $this->authenticationService->getStorage()->write($sessionIdentity);

        return $this->redirect()->toRoute('home');
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