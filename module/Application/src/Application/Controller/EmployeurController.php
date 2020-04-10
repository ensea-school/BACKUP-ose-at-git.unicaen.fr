<?php

namespace Application\Controller;


use Application\Service\Traits\EmployeurServiceAwareTrait;
use Zend\Console\Console;

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
        $critere      = $this->params()->fromPost('critere');
        $employeurs = $this->getServiceEmployeur()->rechercheEmployeur( $critere);


        return compact('employeurs');
    }

    public function rechercheAction()
    {

        $employeurs = $this->getServiceEmployeur()->rechercheEmployeur($critere);
        return compact('employeurs');
    }


}