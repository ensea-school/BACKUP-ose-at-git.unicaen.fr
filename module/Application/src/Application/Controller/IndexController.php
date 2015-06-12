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
    use \Application\Service\Traits\ContextAwareTrait,
        \Application\Service\Traits\AnneeAwareTrait
    ;

    /**
     *
     * @return type
     */
    public function indexAction()
    {
        /* Prise en compte du changement d'année!! */
        $annee = $this->params()->fromQuery('annee');
        if ($annee){
            $annee = $this->getServiceAnnee()->get($annee);
            $this->getServiceContext()->setAnnee($annee);
        }

        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $view = new ViewModel([
            'annee' => $this->getServiceContext()->getAnnee(),
            'role'  => $role,
        ]);

        if ($role->getPersonnel()) {
            $personnel = $role->getPersonnel();
            // URL de la page affichant les indicateurs auxquels est abonné l'utilisateur
            $view->setVariable('abonnementsUrl', $this->url()->fromRoute('indicateur/abonnements', ['personnel' => $personnel->getId()]));
        }

        return $view;
    }
}