<?php

namespace Intervenant\Controller;


use Application\Controller\AbstractController;
use Intervenant\Service\NoteServiceAwareTrait;

class NoteController extends AbstractController
{
    use NoteServiceAwareTrait;

    public function indexAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            Note::class,
        ]);

        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant \Application\Entity\Db\Intervenant */

        if (!$intervenant) {
            throw new \Exception('Intervenant introuvable');
        }

        $notes = $this->getServiceNote()->getByIntervenant($intervenant);
   

        return compact('notes');
    }

}

