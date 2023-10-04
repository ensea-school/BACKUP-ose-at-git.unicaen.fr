<?php

namespace Lieu\Controller;

use Application\Controller\AbstractController;
use Application\Service\Traits\ContextServiceAwareTrait;
use Lieu\Form\DepartementSaisieFormAwareTrait;
use Lieu\Service\DepartementServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;

/**
 * Description of DepartementController
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class DepartementController extends AbstractController
{
    use ContextServiceAwareTrait;
    use DepartementServiceAwareTrait;
    use DepartementSaisieFormAwareTrait;
    use DepartementServiceAwareTrait;


    public function indexAction()
    {
        $query        = $this->em()->createQuery('SELECT d FROM Lieu\Entity\Db\Departement d WHERE d.histoDestruction is null');
        $departements = $query->getResult();

        return compact('departements');
    }



    public function saisieAction()
    {
        $departement = $this->getEvent()->getParam('departement');
        $form        = $this->getFormDepartementSaisie();

        if (empty($departement)) {
            $title       = "Création d'un nouveau département";
            $departement = $this->getServiceDepartement()->newEntity();
        } else {
            $title = "Edition d'un département";
        }
        $form->bindRequestSave($departement, $this->getRequest(), function () use ($departement) {
            $this->getServiceDepartement()->save($departement);
            $this->flashMessenger()->addSuccessMessage(
                "Ajout réussi"
            );
        });


        return compact('form', 'title');
    }



    public function supprimerAction()
    {
        $departement = $this->getEvent()->getParam('departement');
        $this->getServiceDepartement()->delete($departement, true);

        return new MessengerViewModel();
    }
}