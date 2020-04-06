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
        return [];
    }

    public function updateEmployeurAction()
    {


        $ci = Console::getInstance();
        $ci->writeLine("Lancement de la mise à jour de la table employeur");

        $employeurService = $this->getServiceEmployeur();
        $listEmployeurs = $employeurService->getEmployeurs();
        $file = \AppConfig::get('employeur','import-file');
        $data = $employeurService->loadEmployeurFromFile($file);
        $insert = [];



        exit;
    }
}