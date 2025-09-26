<?php

namespace Dossier\Controller;


use Application\Controller\AbstractController;
use Application\Provider\Privileges;
use Dossier\Entity\Db\Employeur;
use Dossier\Form\Traits\EmployeurSaisieFormAwareTrait;
use Dossier\Service\Traits\EmployeurServiceAwareTrait;
use Laminas\View\Model\JsonModel;
use UnicaenApp\View\Model\MessengerViewModel;
use UnicaenVue\View\Model\VueModel;

/**
 * Description of EmployeurController
 *
 * @author Antony Le Courtes <antony.lecourtes@unicaen.fr>
 */
class EmployeurController extends AbstractController
{

    use EmployeurServiceAwareTrait;
    use EmployeurSaisieFormAwareTrait;

    public function indexAction()
    {
        $canEdit    = $this->isAllowed(Privileges::getResourceId(Privileges::REFERENTIEL_COMMUN_EMPLOYEUR_EDITION));
        $critere    = $this->params()->fromPost('critere');
        $employeurs = $this->getServiceEmployeur()->rechercheEmployeur($critere, 1000);


        return compact('employeurs', 'canEdit');
    }



    public function employeurAction()
    {
        $vm = new VueModel();
        $vm->setTemplate('employeur/liste-employeur');

        return $vm;
    }



    public function dataEmployeurAction()
    {
        $post = $this->axios()->fromPost();

        return $this->getServiceEmployeur()->getDataEmployeur($post);
    }



    public function saisieAction()
    {
        $employeur = $this->getEvent()->getParam('employeur');

        if (empty($employeur)) {
            $title     = "Ajout d'un nouvel employeur";
            $employeur = $this->getServiceEmployeur()->newEntity();
        } else {
            $title = "Édition d'un employeur";
        }

        $form = $this->getFormEmployeurSaisie();


        $form->bindRequestSave($employeur, $this->getRequest(), function () use ($employeur, $form){
            /**
             * @var Employeur $employeur
             */
            $raisonSociale = $employeur->getRaisonSociale() ? strtolower($employeur->getRaisonSociale()) : '';
            $nomCommercial = $employeur->getNomCommercial() ? strtolower($employeur->getNomCommercial()) : '';

            $employeur->setCritereRecherche($raisonSociale . ' ' . $nomCommercial . ' ' . $employeur->getSiret());
            $employeur->setSourceCode($employeur->getSiret());
            $this->getServiceEmployeur()->save($employeur);
            $this->flashMessenger()->addSuccessMessage(
                "Ajout réussi"
            );
        });

        return compact('form', 'title');
    }



    public function supprimerAction()
    {
        $employeur = $this->getEvent()->getParam('employeur');
        $this->getServiceEmployeur()->delete($employeur, true);

        return new MessengerViewModel();
    }



    public function rechercheAction()
    {

        $term = $this->params()->fromQuery('term');


        $employeurs = $this->getServiceEmployeur()->rechercheEmployeur($term);

        return new JsonModel($employeurs);
    }



    public function rechercheJsonAction()
    {
        $critere = $this->params()->fromPost('critere');

        $employeurs     = $this->getServiceEmployeur()->rechercheEmployeur($critere);
        $employeursJson = json_encode($employeurs);

        return $employeursJson;
    }

}