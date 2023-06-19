<?php

namespace Service\Controller;

use Application\Controller\AbstractController;
use Service\Entity\Db\MotifModificationServiceDu;
use Service\Form\MotifModificationServiceSaisieFormAwareTrait;
use Service\Service\MotifModificationServiceDuServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;

class MotifModificationServiceController extends AbstractController
{
    use MotifModificationServiceDuServiceAwareTrait;
    use MotifModificationServiceSaisieFormAwareTrait;


    public function indexAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            MotifModificationServiceDu::class,
        ]);

        $motifModificationServices = $this->getServiceMotifModificationServiceDu()->getList();

        return compact('motifModificationServices');
    }



    public function saisieAction()
    {
        /* @var $motifModificationService MotifModificationServiceDu */

        $motifModificationServiceDu = $this->getEvent()->getParam('motifModificationServiceDu');

        $form = $this->getFormMotifModificationServiceMotifModificationServiceSaisie();
        if (empty($motifModificationServiceDu)) {
            $title                      = 'Création d\'un nouveau motif de modification de service dû';
            $motifModificationServiceDu = $this->getServiceMotifModificationServiceDu()->newEntity();
        } else {
            $title = 'Édition d\'un motif de modification de service dû';
        }

        $form->bindRequestSave($motifModificationServiceDu, $this->getRequest(), function (MotifModificationServiceDu $fr) {
            try {
                $this->getServiceMotifModificationServiceDu()->save($fr);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'title');
    }



    public function deleteAction()
    {
        $motifModificationServiceDu = $this->getEvent()->getParam('motifModificationServiceDu');

        try {
            $this->getServiceMotifModificationServiceDu()->delete($motifModificationServiceDu);
            $this->flashMessenger()->addSuccessMessage("Motif de Modification de Service supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel();
    }
}
