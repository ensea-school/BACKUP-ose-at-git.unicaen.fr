<?php

namespace Application\Controller;

use Administration\Service\ParametresServiceAwareTrait;
use Application\Entity\Db\Annee;
use Application\Provider\Privileges;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Intervenant\Service\IntervenantServiceAwareTrait;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractController
{
    use ContextServiceAwareTrait;
    use AnneeServiceAwareTrait;
    use IntervenantServiceAwareTrait;
    use ParametresServiceAwareTrait;


    public function indexAction()
    {
        $documentation = [
            'E' => $this->getServiceParametres()->get('doc-intervenant-vacataires'),
            'P' => $this->getServiceParametres()->get('doc-intervenant-permanents'),
            'S' => $this->getServiceParametres()->get('doc-intervenant-etudiants'),
        ];

        $intervenant = $this->getServiceContext()->getIntervenant();
        $utilisateur = $this->getServiceContext()->getUtilisateur();

        $onlyIntervenant = $intervenant && !(bool)$this->getServiceContext()->getAffectation();

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
            'noPrivilege' => !(bool)$this->getServiceContext()->getAffectation(),
        ]);

        if ($this->getServiceContext()->getAffectation() && $this->isAllowed(Privileges::getResourceId(Privileges::INDICATEUR_VISUALISATION))) {
            // URL de la page affichant les indicateurs auxquels est abonné l'utilisateur
            $view->setVariable('abonnementsUrl', $this->url()->fromRoute('indicateur/abonnements'));
        }

        return $view;
    }



    public function planAction()
    {
        $configPages = \Unicaen\Framework\Application\Application::getInstance()->container()->get('config')['navigation']['default']['home']['pages'];
        $isConnected = (bool)$this->getServiceContext()->getUtilisateur();

        return compact('configPages', 'isConnected');
    }
    


    public function changementAnneeAction()
    {
        /* Prise en compte du changement d'année!! */
        $annee = $this->params()->fromRoute(Annee::class);
        if ($annee) {
            $this->getServiceContext()->setAnnee($annee);
        }

        return [];
    }

}