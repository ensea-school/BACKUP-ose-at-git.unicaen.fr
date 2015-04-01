<?php

namespace Application\Controller;

use Application\Acl\IntervenantRole;
use Application\Controller\Plugin\Intervenant;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 *
 */
class IndexController extends AbstractActionController implements ContextProviderAwareInterface
{
    use ContextProviderAwareTrait;

    /**
     *
     * @return type
     */
    public function indexAction()
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();

        $view = new ViewModel([
            'annee' => $this->getContextProvider()->getGlobalContext()->getAnnee(),
            'role'  => $role,
        ]);

        if ($role && !$role instanceof IntervenantRole) {
            $personnel = $this->getContextProvider()->getGlobalContext()->getPersonnel();
            // URL de la page affichant les indicateurs auxquels est abonné l'utilisateur
            $view->setVariable('abonnementsUrl', $this->url()->fromRoute('indicateur/abonnements', ['personnel' => $personnel->getId()]));
        }

        return $view;
    }

    /**
     *
     * @return type
     */
    public function gestionAction()
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();

        $view = new ViewModel([
            'annee' => $this->getContextProvider()->getGlobalContext()->getAnnee(),
            'role'  => $role,
            'title' => "Gestion",
        ]);

        return $view;
    }
}