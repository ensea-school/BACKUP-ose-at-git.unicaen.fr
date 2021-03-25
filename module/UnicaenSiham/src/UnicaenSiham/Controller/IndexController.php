<?php

namespace UnicaenSiham\Controller;


use UnicaenSiham\Exception\SihamException;
use UnicaenSiham\Service\Traits\SihamAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;


class IndexController extends AbstractActionController
{
    use SihamAwareTrait;

    public function indexAction(): array
    {
        $agents = [];
        try {

            $agents = $this->siham->recupererListeAgents(['nomUsuel' => 'dup%']);
        } catch (SihamException $e) {
            echo $e->getMessage();
        }

        return compact('agents');
    }



    public function voirAction(): array
    {
        $agent = [];
        try {
            $agent = $this->siham->recupDonneesPersonnellesAgent(['listeMatricules' => ['UCN000159222', 'UCN000200042']]);
        } catch (SihamException $e) {
            echo $e->getMessage();
        }

        return compact('agent');
    }
}
