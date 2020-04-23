<?php

namespace Application\Controller;

use Application\Entity\Db\CcActivite;
use Application\Entity\Db\CentreCout;
use Application\Entity\Db\CentreCoutStructure;
use Application\Form\CentreCout\Traits\CentreCoutActiviteSaisieFormAwareTrait;
use Application\Service\Traits\CcActiviteServiceAwareTrait;
use Application\Service\Traits\CentreCoutServiceAwareTrait;
use Application\Service\Traits\CentreCoutStructureServiceAwareTrait;
use Application\Form\CentreCout\Traits\CentreCoutSaisieFormAwareTrait;
use Application\Form\CentreCout\Traits\CentreCoutStructureSaisieFormAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;

class CentreCoutController extends AbstractController
{
    use CentreCoutServiceAwareTrait;
    use CentreCoutStructureServiceAwareTrait;
    use CentreCoutSaisieFormAwareTrait;
    use CentreCoutStructureSaisieFormAwareTrait;
    use CcActiviteServiceAwareTrait;
    use CentreCoutActiviteSaisieFormAwareTrait;


    public function indexAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            CentreCout::class,
        ]);
        $this->em()->getFilters()->enable('historique')->init([
            CentreCoutStructure::class,
        ]);

        $centreCouts          = $this->getServiceCentreCout()->getList();
        $centreCoutStructures = $this->getServiceCentreCoutStructure()->getList();

        return compact('centreCouts', 'centreCoutStructures');
    }



    public function saisieAction()
    {
        /* @var $centreCout CentreCout */

        $centreCout = $this->getEvent()->getParam('centreCout');

        $form = $this->getFormCentreCoutSaisie();
        if (empty($centreCout)) {
            $title      = 'Création d\'un nouveau Centre de Coûts';
            $centreCout = $this->getServiceCentreCout()->newEntity();
        } else {
            $title = 'Édition d\'un Centre de Coûts';
        }

        $form->bindRequestSave($centreCout, $this->getRequest(), function (CentreCout $fr) {
            try {
                $this->getServiceCentreCout()->save($fr);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'title');
    }



    public function deleteAction()
    {
        $centreCout = $this->getEvent()->getParam('centreCout');

        try {
            $this->getServiceCentreCout()->delete($centreCout);
            $this->flashMessenger()->addSuccessMessage("Centre de Coûts supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel(compact('centreCout'));
    }



    public function saisieStructureAction()
    {
        /* @var $centreCout CentreCout */
        /* @var $centreCoutStructure CentreCoutStructure */

        $centreCout          = $this->getEvent()->getParam('centreCout');
        $centreCoutStructure = $this->getEvent()->getParam('centreCoutStructure');

        $form = $this->getFormCentreCoutStructureSaisie();
        if (empty($centreCoutStructure)) {
            $title               = 'Création d\'une structure pour le Centre de Coûts';
            $centreCoutStructure = $this->getServiceCentreCoutStructure()->newEntity()
                ->setCentreCout($centreCout);
        } else {
            $title = 'Édition d\'une structure pour le Centre de Coûts';
        }

        $form->bindRequestSave($centreCoutStructure, $this->getRequest(), function (CentreCoutStructure $ccs) {
            try {
                $this->getServiceCentreCoutStructure()->save($ccs);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'title');
    }



    public function deleteStructureAction()
    {
        $centreCoutStructure = $this->getEvent()->getParam('centreCoutStructure');

        try {
            $this->getServiceCentreCoutStructure()->delete($centreCoutStructure);
            $this->flashMessenger()->addSuccessMessage("Structure plus liée au centre de coûts.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel(compact('centreCoutStructure'));
    }

    public function centreCoutActiviteAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            CcActivite::class
        ]);

        $listeActivites = $this->getServiceCcActivite()->getList();

        return compact('listeActivites');
    }

    public function centreCoutActiviteSaisieAction()
    {
        $centreCoutActivite = $this->getEvent()->getParam('ccActivite');
        $form = $this->getFormCentreCoutActiviteSaisie();
        if (empty($centreCoutActivite)) {
            $title            = 'Création d\'une nouvelle activité de centre de cout';
            $centreCoutActivite = $this->getServiceCcActivite()->newEntity();
        } else {
            $title = 'Édition d\'une activité de centre de cout';
        }
        $form->bindRequestSave($centreCoutActivite, $this->getRequest(), function (ccActivite $ca) {
            try {
                $this->getServiceCcActivite()->save($ca);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'title');

    }

    public function centreCoutActiviteDeleteAction()
    {
        $centreCoutActivite = $this->getEvent()->getParam('ccActivite');

        try {
            $this->getServiceCcActivite()->delete($centreCoutActivite);
            $this->flashMessenger()->addSuccessMessage("Activité supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel(compact('centreCoutActivite'));
    }



}
