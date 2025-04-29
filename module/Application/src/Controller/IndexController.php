<?php

namespace Application\Controller;

use Administration\Service\ParametresServiceAwareTrait;
use Application\Acl\Role;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Intervenant\Service\IntervenantServiceAwareTrait;
use Laminas\View\Model\ViewModel;

/**
 *
 */
class IndexController extends AbstractController
{
    use ContextServiceAwareTrait;
    use AnneeServiceAwareTrait;
    use IntervenantServiceAwareTrait;
    use ParametresServiceAwareTrait;


    public function indexAction()
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $documentation = [
            'E' => $this->getServiceParametres()->get('doc-intervenant-vacataires'),
            'P' => $this->getServiceParametres()->get('doc-intervenant-permanents'),
            'S' => $this->getServiceParametres()->get('doc-intervenant-etudiants'),
        ];

        $intervenant = $this->getServiceContext()->getIntervenant();
        $utilisateur = $this->getServiceContext()->getUtilisateur();

        $onlyIntervenant = $intervenant && (null == $this->getServiceContext()->getSelectedIdentityRole()->getDbRole());

        $view = new ViewModel([
            'annee'                     => $this->getServiceContext()->getAnnee(),
            'documentation'             => $documentation,
            'context'                   => $this->getServiceContext(),
            'pageAccueil'               => $this->getServiceParametres()->get('page_accueil'),
            'connexionNonAutorise'      => $this->getServiceParametres()->get('connexion_non_autorise'),
            'connexionSansRoleNiStatut' => $this->getServiceParametres()->get('connexion_sans_role_ni_statut'),
            'intervenant' => $intervenant,
            'utilisateur' => $utilisateur,
            'onlyIntervenant' => $onlyIntervenant,
            'noPrivilege' => null == $role?->getDbRole(),
        ]);

        if ($role && $this->isAllowed(Privileges::getResourceId(Privileges::INDICATEUR_VISUALISATION))) {
            // URL de la page affichant les indicateurs auxquels est abonné l'utilisateur
            $view->setVariable('abonnementsUrl', $this->url()->fromRoute('indicateur/abonnements'));
        }

        return $view;
    }



    public function planAction()
    {
        $configPages = \AppAdmin::container()->get('config')['navigation']['default']['home']['pages'];
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        return compact('configPages', 'role');
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
                $intervenant = $this->getServiceIntervenant()->getByCode($role->getIntervenant()->getCode());
                if ($intervenant) {
                    //Correction mauvais refresh du role lors du changement d'année
                    $this->serviceUserContext->setSelectedIdentityRole($intervenant->getStatut()->getRoleId());
                    //$this->serviceUserContext->setNextSelectedIdentityRole($intervenant->getStatut()->getRoleId());
                }
            }
        }

        return [];
    }

}