<?php

namespace Application\Controller;

use Application\Entity\Db\CentreCout;
use Application\Entity\Db\CentreCoutStructure;
use Application\Service\Traits\CentreCoutServiceAwareTrait;
use Application\Service\Traits\CentreCoutStructureServiceAwareTrait;
use Application\Exception\DbException;
use Application\Form\CentreCout\Traits\CentreCoutSaisieFormAwareTrait;
use Application\Form\CentreCout\Traits\CentreCoutStructureSaisieFormAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;

class CentreCoutController extends AbstractController
{
    use CentreCoutServiceAwareTrait;
    use CentreCoutStructureServiceAwareTrait;
    use CentreCoutSaisieFormAwareTrait;
    use CentreCoutStructureSaisieFormAwareTrait;


    public function indexAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            CentreCout::class,
        ]);
        $this->em()->getFilters()->enable('historique')->init([
            CentreCoutStructure::class,
        ]);

        $centreCouts = $this->getServiceCentreCout()->getList();
        $centreCoutStructures = $this->getServiceCentreCoutStructure()->getList();

        return compact('centreCouts','centreCoutStructures');
    }



    public function saisieAction()
    {
        /* @var $centreCout CentreCout */

        $centreCout = $this->getEvent()->getParam('centre-cout');

        $form = $this->getFormCentreCoutSaisie();
        if (empty($centreCout)) {
            $title = 'Création d\'un nouveau Centre de Cout';
            $centreCout = $this->getServiceCentreCout()->newEntity();
        } else {
            $title = 'Édition d\'une Centre de Cout';
        }

        $form->bindRequestSave($centreCout, $this->getRequest(), function (CentreCout $fr) {
            try {
                $this->getServiceCentreCout()->save($fr);
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
        $centreCout = $this->getEvent()->getParam('centre-cout');

        try {
            $this->getServiceCentreCout()->delete($centreCout);
            $this->flashMessenger()->addSuccessMessage("Centre de Cout supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
        }
        return new MessengerViewModel(compact('centreCout'));
    }

    public function saisieStructureAction()
    {
        /* @var $centreCout CentreCout */
        /* @var $centreCoutStructure CentreCoutStructure */

        $centreCout = $this->getEvent()->getParam('centre-cout');
        $centreCoutStructure = $this->getEvent()->getParam('centre-cout-structure');

        $form = $this->getFormCentreCoutStructureSaisie();
        if (empty($centreCoutStructure)) {
            $title = 'Création d\'une structure pour le Centre de Cout';
            $centreCoutStructure = $this->getServiceCentreCoutStructure()->newEntity()
                ->setCentreCout($centreCout);
        } else {
            $title = 'Édition d\'une structure pour le Centre de Cout';
        }

        $form->bindRequestSave($centreCoutStructure, $this->getRequest(), function (CentreCoutStructure $ccs) {
            try {
                $this->getServiceCentreCoutStructure()->save($ccs);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $e = DbException::translate($e);
                $this->flashMessenger()->addErrorMessage($e->getMessage() . ':' . $ccs->getId());
            }
        });

        return compact('form', 'title');
    }

    public function deleteStructureAction()
    {
        $centreCoutStructure = $this->getEvent()->getParam('centre-cout-structure');

        try {
            $this->getServiceCentreCoutStructure()->delete($centreCoutStructure);
            $this->flashMessenger()->addSuccessMessage("Structure plus liée au centre de cout.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
        }
        return new MessengerViewModel(compact('centreCoutStructure'));
    }
}
