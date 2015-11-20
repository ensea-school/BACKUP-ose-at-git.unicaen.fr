<?php

namespace Application\Controller;

use Application\Acl\Role;
use Application\Service\Traits\IntervenantAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 *
 */
class IndexController extends AbstractActionController
{
    use \Application\Service\Traits\ContextAwareTrait,
        \Application\Service\Traits\AnneeAwareTrait,
        IntervenantAwareTrait;



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

        if ($role && $personnel = $role->getPersonnel()) {
            // URL de la page affichant les indicateurs auxquels est abonné l'utilisateur
            $view->setVariable('abonnementsUrl', $this->url()->fromRoute('indicateur/abonnements', ['personnel' => $personnel->getId()]));
        }

        return $view;
    }



    public function changementAnneeAction()
    {
        /* Prise en compte du changement d'année!! */
        $annee = $this->params()->fromRoute('annee');
        if ($annee) {
            $annee = $this->getServiceAnnee()->get($annee);
            $this->getServiceContext()->setAnnee($annee);

            $role = $this->getServiceContext()->getSelectedIdentityRole();
            if ($role instanceof Role && $role->getIntervenant()) {
                $intervenant = $this->getServiceIntervenant()->getBySourceCode($role->getIntervenant()->getSourceCode());
                $this->getServiceUserContext()->setNextSelectedIdentityRole($intervenant->getStatut()->getRoleId());
            }
        }

        return [];
    }



    /**
     * @return UserContext
     */
    private function getServiceUserContext()
    {
        return $this->getServiceLocator()->get('authUserContext');
    }
}