<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Interfaces\PersonnelAwareInterface;

/**
 *
 */
class IndexController extends AbstractActionController
{
    use \Application\Service\Traits\ContextAwareTrait;

    /**
     *
     * @return type
     */
    public function indexAction()
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $view = new ViewModel([
            'annee' => $this->getServiceContext()->getAnnee(),
            'role'  => $role,
        ]);

        if ($role instanceof PersonnelAwareInterface && $role->getPersonnel()) {
            $personnel = $role->getPersonnel();
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
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $view = new ViewModel([
            'role'  => $role,
            'title' => "Gestion",
        ]);

        return $view;
    }
}