<?php

namespace Application\Controller;


use Application\Entity\Db\Employeur;
use Application\Form\Employeur\Traits\EmployeurSaisieFormAwareTrait;
use Application\Service\Traits\EmployeurServiceAwareTrait;
use Laminas\View\Model\JsonModel;
use UnicaenApp\View\Model\MessengerViewModel;

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
        $critere    = $this->params()->fromPost('critere');
        $employeurs = $this->getServiceEmployeur()->rechercheEmployeur($critere, 1000);


        return compact('employeurs');
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


        $form->bindRequestSave($employeur, $this->getRequest(), function () use ($employeur, $form) {
            /**
             * @var Employeur $employeur
             */
            $employeur->setCritereRecherche($employeur->getRaisonSociale() . ' ' . $employeur->getNomCommercial() . ' ' . $employeur->getSiret());
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


        /*$this->em()->getFilters()->enable('historique')->init([
            Employeur::class,
        ]);*/
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