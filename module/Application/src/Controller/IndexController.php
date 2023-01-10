<?php

namespace Application\Controller;

use Application\Acl\Role;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use UnicaenAuth\Service\Traits\UserContextServiceAwareTrait;
use Laminas\View\Model\ViewModel;

/**
 *
 */
class IndexController extends AbstractController
{
    use ContextServiceAwareTrait;
    use AnneeServiceAwareTrait;
    use IntervenantServiceAwareTrait;
    use UserContextServiceAwareTrait;
    use ParametresServiceAwareTrait;


    /**
     *
     * @return type
     */
    public function indexAction()
    {
        $role = $this->serviceUserContext->getSelectedIdentityRole();

        $documentation = [
            'vacataires' => $this->getServiceParametres()->get('doc-intervenant-vacataires'),
            'permanents' => $this->getServiceParametres()->get('doc-intervenant-permanents'),
        ];

        $view = new ViewModel([
            'annee'                     => $this->getServiceContext()->getAnnee(),
            'documentation'             => $documentation,
            'context'                   => $this->getServiceContext(),
            'pageAccueil'               => $this->getServiceParametres()->get('page_accueil'),
            'connexionNonAutorise'      => $this->getServiceParametres()->get('connexion_non_autorise'),
            'connexionSansRoleNiStatut' => $this->getServiceParametres()->get('connexion_sans_role_ni_statut'),
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



    public function generateProxiesAction()
    {$destPath = $this->em()->getConfiguration()->getProxyDir();

        if (!is_dir($destPath)) {
            mkdir($destPath, 0775, true);
        }

        $destPath = realpath($destPath);

        $metadatas = $this->em()->getMetadataFactory()->getAllMetadata();
        $this->em()->getProxyFactory()->generateProxyClasses($metadatas, $destPath);
    }

}