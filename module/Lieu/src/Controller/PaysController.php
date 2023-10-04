<?php

namespace Lieu\Controller;

use Application\Controller\AbstractController;
use Application\Controller\Exception;
use Application\Service\Traits\ContextServiceAwareTrait;
use Lieu\Form\PaysSaisieFormAwareTrait;
use Lieu\Service\PaysServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;

/**
 * Description of PaysController
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class PaysController extends AbstractController
{
    use ContextServiceAwareTrait;
    use PaysSaisieFormAwareTrait;
    use PaysServiceAwareTrait;


    public function indexAction()
    {
        $query = $this->em()->createQuery('SELECT p FROM Lieu\Entity\Db\Pays p WHERE p.histoDestruction is null');
        $payss = $query->getResult();

        return compact('payss');
    }



    public function saisieAction()
    {
        $pays = $this->getEvent()->getParam('pays');
        $form = $this->getFormPaysSaisie();

        if (empty($pays)) {
            $title = "Création d'un nouveau pays";
            $pays = $this->getServicePays()->newEntity();
        } else {
            $title = "Edition d'un pays";
        }
        $form->bindRequestSave($pays, $this->getRequest(), function () use ($pays, $form) {
            try {
                $this->getServicePays()->save($pays);
                $this->flashMessenger()->addSuccessMessage(
                    "Ajout réussi"
                );
            } catch (Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'title');
    }



    public function supprimerAction()
    {
        $pays = $this->getEvent()->getParam('pays');
        $this->getServicePays()->delete($pays, true);

        return new MessengerViewModel();
    }
}