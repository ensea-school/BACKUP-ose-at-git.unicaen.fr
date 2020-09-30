<?php

namespace Application\Controller;


use Application\Service\Traits\EmployeurServiceAwareTrait;
use Zend\View\Model\JsonModel;

/**
 * Description of EmployeurController
 *
 * @author Antony Le Courtes <antony.lecourtes@unicaen.fr>
 */
class EmployeurController extends AbstractController
{

    use EmployeurServiceAwareTrait;

    public function indexAction()
    {
        $critere    = $this->params()->fromPost('critere');
        $employeurs = $this->getServiceEmployeur()->rechercheEmployeur($critere, 1000);


        return compact('employeurs');
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