<?php

namespace Application\View\Helper;

use Application\Service\NavbarService;
use Application\Service\Traits\ContextServiceAwareTrait;
use Unicaen\Framework\Navigation\Navigation;
use Unicaen\Framework\Navigation\Page;
use Unicaen\Framework\Router\Router;
use Unicaen\Framework\User\UserManager;
use Laminas\View\Helper\AbstractHtmlElement;
use Lieu\Service\StructureServiceAwareTrait;
use UnicaenApp\Traits\SessionContainerTrait;
use Utilisateur\Entity\Db\Role;
use Utilisateur\Provider\UserProvider;

/**
 * Description of UtilisateurViewHelper
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class LayoutViewHelper extends AbstractHtmlElement
{
    use ContextServiceAwareTrait;
    use StructureServiceAwareTrait;
    use SessionContainerTrait;


    public function __construct(
        private readonly NavbarService $navbarService,
        private readonly Navigation    $navigation,
        private readonly UserManager   $userManager,
        private readonly Router        $router,
        private readonly UserProvider  $userProvider,
    )
    {

    }



    public function __invoke(): self
    {
        return $this;
    }



    public function navbarData(): array
    {
        return [
            'appName'   => $this->navbarService->appName(),
            'appTitle'  => $this->navbarService->appTitle(),
            'appUrl'    => $this->navbarService->appUrl(),
            'annees'    => $this->navbarService->annees(),
            'annee'     => $this->navbarService->annee(),
            'menuItems' => $this->navbarService->menuItems(),
            'connexion' => $this->connexionData(),
            //'menuActive' => $this->
        ];
    }



    public function connexionData(): array
    {
        if (!$this->userManager->isConnected()) {
            return ['connecte' => false];
        }

        $user           = $this->userManager->getUser();
        $profile        = $this->userManager->getProfile();
        $needStructures = false;
        $roles          = [];

        $ps = $this->userManager->getProfiles();
        foreach ($ps as $p) {
            /** @var Role $role */
            $r = $p->getContext('role');

            if (!$needStructures && $r?->getPeutChangerStructure()) {
                $needStructures = true;
            }
            $roles[$p->getId()] = [
                'libelle'              => $p->getDisplayName(),
                'peutChangerStructure' => $r?->getPeutChangerStructure() ?? false,
            ];
        }

        $structures = [];
        if ($needStructures) {
            $structures = $this->getStructures();
        }

        return [
            'utilisateurNom'    => $user->getDisplayName(),
            'roleNom'           => $profile?->getDisplayName() ?? 'Authentifié(e)',
            'roleId'            => $profile?->getId(),
            'connecte'          => true,
            'usurpationEnabled' => $this->userManager->isUsurpationEnabled(),
            'usurpationEnCours' => $this->userManager->isUsurpationEnCours(),
            'roles'             => $roles,
            'route'             => $this->router->getCurrentRoute()?->getName(),
            'structureId'       => $this->userProvider->getCustomStructure()?->getId(),
            'structures'        => $structures,
        ];
    }



    /**
     * @return array|Page[]
     */
    public function footerData(): array
    {
        $pages = $this->navigation->home->getPages();
        foreach ($pages as $pn => $page) {
            if (!$page->isFooter()) {
                unset($pages[$pn]);
            }
        }

        return $pages;
    }



    /**
     * @return array|Page[]
     */
    public function menuData(): array
    {
        $currentPage = $this->navigation->getCurrentPage();

        if (!$currentPage) {
            return [];
        }

        if ($currentPage->getParent() === $this->navigation->home) {
            return [];
        }

        $refPage = $this->navigation->getCurrentSubHomePage();

        if (!$refPage) {
            return [];
        }

        return $refPage->getVisiblePages();
    }



    /**
     * Retourne la liste des structures associées à des rôles.
     *
     * @return array
     */
    private function getStructures()
    {
        $session = $this->getSessionContainer();
        if (!isset($session->structures)) {
            $qb                  = $this->getServiceStructure()->finderByHistorique();
            $s                   = $this->getServiceStructure()->getList($qb);
            $session->structures = [];
            foreach ($s as $structure) {
                if ($structure->getLevel() > 0) {
                    $session->structures[$structure->getId()] = str_repeat('&nbsp;', $structure->getLevel() * 4) . (string)$structure;
                } else {
                    $session->structures[$structure->getId()] = (string)$structure;
                }
            }
        }

        return $session->structures;
    }
}