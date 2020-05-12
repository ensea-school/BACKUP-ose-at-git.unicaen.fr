<?php

namespace Application\Controller;


use Application\Service\Traits\EmployeurServiceAwareTrait;

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
        $employeurs = $this->getServiceEmployeur()->rechercheEmployeur($critere);


        return compact('employeurs');
    }

    public function rechercheAction()
    {

        $employeurs = $this->getServiceEmployeur()->rechercheEmployeur();
        return compact('employeurs');
    }

    public function rechercheJsonAction()
    {
        $critere    = $this->params()->fromPost('critere');

        $employeurs = $this->getServiceEmployeur()->rechercheEmployeur($critere);
        $employeursJson = json_encode($employeurs);

        return $employeursJson;
    }



}