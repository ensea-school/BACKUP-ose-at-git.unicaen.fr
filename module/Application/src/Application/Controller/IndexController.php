<?php

namespace Application\Controller;

use Application\Acl\Role;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use UnicaenAuth\Service\Traits\UserContextServiceAwareTrait;
use Zend\View\Model\ViewModel;

/**
 *
 */
class IndexController extends AbstractController
{
    use \Application\Service\Traits\ContextServiceAwareTrait;
    use \Application\Service\Traits\AnneeServiceAwareTrait;
    use IntervenantServiceAwareTrait;
    use UserContextServiceAwareTrait;
    use ParametresServiceAwareTrait;



    /**
     *
     * @return type
     */
    public function indexAction()
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $documentation = [
            'vacataires' => $this->getServiceParametres()->get('doc-intervenant-vacataires'),
            'permanents' => $this->getServiceParametres()->get('doc-intervenant-permanents'),
        ];

        $view = new ViewModel([
            'annee'         => $this->getServiceContext()->getAnnee(),
            'documentation' => $documentation,
            'context'       => $this->getServiceContext(),
        ]);

        if ($role && $this->isAllowed(Privileges::getResourceId(Privileges::INDICATEUR_VISUALISATION))) {
            // URL de la page affichant les indicateurs auxquels est abonné l'utilisateur
            $view->setVariable('abonnementsUrl', $this->url()->fromRoute('indicateur/abonnements'));
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
                if ($intervenant) {
                    $this->serviceUserContext()->setNextSelectedIdentityRole($intervenant->getStatut()->getRoleId());
                }
            }
        }

        return [];
    }

}