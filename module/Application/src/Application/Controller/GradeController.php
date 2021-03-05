<?php

namespace Application\Controller;

use Application\Entity\Db\Grade;
use Application\Form\Grade\Traits\GradeSaisieFormAwareTrait;
use Application\Service\Traits\GradeServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;

class GradeController extends AbstractController
{

    use GradeServiceAwareTrait;
    use GradeSaisieFormAwareTrait;


    public function indexAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            Grade::class,
        ]);

        $grades = $this->getServiceGrade()->getList();

        return compact('grades');
    }



    public function saisieAction()
    {
        $grade = $this->getEvent()->getParam('grade');

        $form = $this->getFormGradeSaisie();

        if (empty($grade)) {
            $title  = "Création d'un nouveau grade";
            $voirie = $this->getServiceGrade()->newEntity();
        } else {
            $title = "Edition d'un grade";
        }

        $form->bindRequestSave($grade, $this->getRequest(), function (Grade $v) {
            try {
                $this->getServiceGrade()->save($v);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'title');
    }

    /* public function deleteAction()
     {
         $voirie = $this->getEvent()->getParam('voirie');
         try {
             $this->getServiceVoirie()->delete($voirie);
             $this->flashMessenger()->addSuccessMessage("Voirie supprimée avec succés");
         } catch (\Exception $e) {
             $this->flasheMessenger()->addErrorMessage($this->translate($e));
         }

         return new MessengerViewModel(compact('voirie'));
     }*/
}