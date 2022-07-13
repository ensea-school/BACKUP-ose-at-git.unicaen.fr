<?php

namespace Application\Controller;

use Application\Service\Traits\MotifModificationServiceServiceAwareTrait;
use Application\Entity\Db\MotifModificationServiceDu;
use Application\Form\MotifModificationService\Traits\MotifModificationServiceSaisieFormAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;

class MotifModificationServiceController extends AbstractController
{
    use MotifModificationServiceServiceAwareTrait;
    use MotifModificationServiceSaisieFormAwareTrait;


    public function indexAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            MotifModificationServiceDu::class,
        ]);

        $motifModificationServices = $this->getServiceMotifModificationService()->getList();

        return compact('motifModificationServices');
    }



    public function saisieAction()
    {
        /* @var $motifModificationService MotifModificationServiceDu */

        $motifModificationServiceDu = $this->getEvent()->getParam('motifModificationServiceDu');

        $form = $this->getFormMotifModificationServiceMotifModificationServiceSaisie();
        if (empty($motifModificationServiceDu)) {
            $title                      = 'Création d\'un nouveau motif de modification de service dû';
            $motifModificationServiceDu = $this->getServiceMotifModificationService()->newEntity();
        } else {
            $title = 'Édition d\'un motif de modification de service dû';
        }

        $form->bindRequestSave($motifModificationServiceDu, $this->getRequest(), function (MotifModificationServiceDu $fr) {
            try {
                $this->getServiceMotifModificationService()->save($fr);
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
            $this->getServiceMotifModificationService()->delete($motifModificationServiceDu);
            $this->flashMessenger()->addSuccessMessage("Motif de Modification de Service supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
        }

        return new MessengerViewModel();
    }
}
