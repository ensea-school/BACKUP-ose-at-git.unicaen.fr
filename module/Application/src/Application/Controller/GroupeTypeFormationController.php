<?php

namespace Application\Controller;

use Application\Entity\Db\GroupeTypeFormation;
use Application\Service\Traits\GroupeTypeFormationServiceAwareTrait;
use Application\Entity\Db\TypeFormation;
use Application\Service\Traits\TypeFormationServiceAwareTrait;
use Application\Exception\DbException;
use Application\Form\GroupeTypeFormation\Traits\GroupeTypeFormationSaisieFormAwareTrait;
use Application\Form\GroupeTypeFormation\Traits\TypeFormationSaisieFormAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;

class GroupeTypeFormationController extends AbstractController
{
    use GroupeTypeFormationServiceAwareTrait;
    use TypeFormationServiceAwareTrait;
    use GroupeTypeFormationSaisieFormAwareTrait;
    use TypeFormationSaisieFormAwareTrait;


    public function indexAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            GroupeTypeFormation::class,
        ]);

        $this->em()->getFilters()->enable('historique')->init([
            TypeFormation::class,
        ]);

        $groupeTypeFormations = $this->getServiceGroupeTypeFormation()->getList();
        $typeFormations       = $this->getServiceTypeFormation()->getList();

        return compact('groupeTypeFormations', 'typeFormations');
    }



    public function saisieAction()
    {
        /* @var $groupeTypeFormation GroupeTypeFormation */

        $groupeTypeFormation = $this->getEvent()->getParam('groupe-type-formation');

        $form = $this->getFormGroupeTypeFormationSaisie();
        if (empty($groupeTypeFormation)) {
            $title               = 'Création d\'une nouvelle GroupeTypeFormation';
            $groupeTypeFormation = $this->getServiceGroupeTypeFormation()->newEntity();
        } else {
            $title = 'Édition d\'une GroupeTypeFormation';
        }

        $form->bindRequestSave($groupeTypeFormation, $this->getRequest(), function (GroupeTypeFormation $fr) {
            try {
                $this->getServiceGroupeTypeFormation()->save($fr);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $e = DbException::translate($e);
                $this->flashMessenger()->addErrorMessage($e->getMessage() . ':' . $fr->getId());
            }
        });

        return compact('form', 'title');
    }



    public function deleteAction()
    {
        $groupeTypeFormation = $this->getEvent()->getParam('groupe-type-formation');

        try {
            $this->getServiceGroupeTypeFormation()->delete($groupeTypeFormation);
            $this->flashMessenger()->addSuccessMessage("GroupeTypeFormation supprimée avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
        }

        return new MessengerViewModel(compact('groupeTypeFormation'));
    }



    public function typeFormationSaisieAction()
    {
        /* @var $groupeTypeFormation GroupeTypeFormation */
        /* @var $typeFormation TypeFormation */

        $groupeTypeFormation = $this->getEvent()->getParam('groupe-type-formation');
        $typeFormation       = $this->getEvent()->getParam('type-formation');

        $form = $this->getFormTypeFormationSaisie();
        if (empty($typeFormation)) {
            $title         = 'Création d\'un nouveau type de formation';
            $typeFormation = $this->getServiceTypeFormation()->newEntity()
                ->setGroupe($groupeTypeFormation);
            $typeFormation->setServiceStatutaire(true);
        } else {
            $title = 'Édition d\'un Type de Formation';
        }

        $form->bindRequestSave($typeFormation, $this->getRequest(), function (TypeFormation $tf) {
            try {
                $this->getServiceTypeFormation()->save($tf);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $e = DbException::translate($e);
                $this->flashMessenger()->addErrorMessage($e->getMessage() . ':' . $tf->getId());
            }
        });

        return compact('form', 'title');
    }



    public function typeFormationDeleteAction()
    {
        /* @var $typeFormation TypeFormation */
        $typeFormation = $this->getEvent()->getParam('type-formation');

        try {
            $this->getServiceTypeFormation()->delete($typeFormation);
            $this->flashMessenger()->addSuccessMessage("Type de Formation supprimée avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
        }

        return new MessengerViewModel(compact('type de formation'));
    }
}
