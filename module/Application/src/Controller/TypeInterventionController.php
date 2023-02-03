<?php

namespace Application\Controller;

use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeInterventionStatut;
use Application\Service\Traits\TypeInterventionStructureServiceAwareTrait;
use Application\Service\Traits\TypeInterventionStatutServiceAwareTrait;
use Application\Service\Traits\TypeInterventionServiceAwareTrait;
use Application\Entity\Db\TypeIntervention;
use Application\Entity\Db\TypeInterventionStructure;
use Application\Form\TypeIntervention\Traits\TypeInterventionSaisieFormAwareTrait;
use Application\Form\TypeIntervention\Traits\TypeInterventionStructureSaisieFormAwareTrait;
use Application\Form\TypeIntervention\Traits\TypeInterventionStatutSaisieFormAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;
use Application\Service\Traits\ContextServiceAwareTrait;

class TypeInterventionController extends AbstractController
{
    use TypeInterventionServiceAwareTrait;
    use TypeInterventionStructureServiceAwareTrait;
    use TypeInterventionStatutServiceAwareTrait;
    use TypeInterventionSaisieFormAwareTrait;
    use TypeInterventionStructureSaisieFormAwareTrait;
    use TypeInterventionStatutSaisieFormAwareTrait;
    use ContextServiceAwareTrait;


    public function indexAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            TypeIntervention::class,
            TypeInterventionStructure::class,
            TypeInterventionStatut::class,
        ]);

        $annee  = $this->getServiceContext()->getAnnee();
        $tiList = $this->getServiceTypeIntervention()->getList();

        $typesInterventions = [];
        foreach ($tiList as $ti) {
            if ($ti->isValide($annee)) {
                $typesInterventions[] = $ti;
            }
        }

        return compact('typesInterventions', 'annee');
    }



    public function statutAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            TypeInterventionStatut::class,
        ]);

        /** @var TypeIntervention $typeIntervention */
        $typeIntervention        = $typeIntervention = $this->getEvent()->getParam('typeIntervention');
        $typeInterventionStatuts = $typeIntervention->getTypeInterventionStatut($this->getServiceContext()->getAnnee());
        $title                   = "Taux spécifiques par statuts pour " . $typeIntervention;

        return compact('typeIntervention', 'typeInterventionStatuts', 'title');
    }



    public function saisieAction()
    {
        /* @var $typeIntervention TypeIntervention */

        $typeIntervention = $this->getEvent()->getParam('typeIntervention');
        $form             = $this->getFormTypeInterventionTypeInterventionSaisie();
        if (empty($typeIntervention)) {
            $title            = 'Création d\'un nouveau type d\'intervention';
            $typeIntervention = $this->getServiceTypeIntervention()->newEntity();
            $typeIntervention->setVisible(true);
        } else {
            $title = 'Édition d\'un type d\'intervention';
        }

        if ($typeIntervention->getOrdre() == null) $typeIntervention->setOrdre(9999);
        $form->bindRequestSave($typeIntervention, $this->getRequest(), function (TypeIntervention $ti) {
            try {
                $this->getServiceTypeIntervention()->save($ti);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'title');
    }



    public function deleteAction()
    {
        /* @var $typeIntervention TypeIntervention */
        $typeIntervention = $this->getEvent()->getParam('typeIntervention');

        try {
            $this->getServiceTypeIntervention()->delete($typeIntervention);
            $this->flashMessenger()->addSuccessMessage("Type d\'intervention supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel(compact('typeIntervention'));
    }



    public function typeInterventionStructureSaisieAction()
    {
        /* @var $typeInterventionStructure TypeInterventionStructure
         * @var $typeIntervention TypeIntervention
         */

        $this->em()->getFilters()->enable('historique')->init([
            Structure::class,
        ]);

        $typeIntervention          = $this->getEvent()->getParam('typeIntervention');
        $typeInterventionStructure = $this->getEvent()->getParam('typeInterventionStructure');
        $form                      = $this->getFormTypeInterventionTypeInterventionStructureSaisie();
        if (empty($typeInterventionStructure)) {
            $title                     = 'Ajouter une exception pour une structure';
            $typeInterventionStructure = $this->getServiceTypeInterventionStructure()->newEntity()
                ->setTypeIntervention($typeIntervention);
        } else {
            $title = 'Édition d\'une exception pour une structure';
        }
        $typeInterventionStructure->setVisible(!$typeIntervention->isVisible());
        $form->bindRequestSave($typeInterventionStructure, $this->getRequest(), function (TypeInterventionStructure $tis) {
            try {
                $this->getServiceTypeInterventionStructure()->save($tis);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'title');
    }



    public function typeInterventionStructureDeleteAction()
    {
        /* @var $typeInterventionStructure TypeInterventionStructure */
        $typeInterventionStructure = $this->getEvent()->getParam('typeInterventionStructure');

        try {
            $this->getServiceTypeInterventionStructure()->delete($typeInterventionStructure);
            $this->flashMessenger()->addSuccessMessage("Type d\'intervention pour une structure supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel(compact('typeInterventionStructure'));
    }



    public function typeInterventionTrierAction()
    {
        /* @var $ti TypeIntervention */
        $txt       = 'result=';
        $champsIds = explode(',', $this->params()->fromPost('champsIds', ''));
        $ordre     = 1;
        foreach ($champsIds as $champId) {
            $txt .= $champId . '=>';
            $ti  = $this->getServiceTypeIntervention()->get($champId);
            if ($ti) {
                $txt .= ';' . $ti->getOrdre();
                $ti->setOrdre($ordre);
                $ordre++;
                try {
                    $this->getServiceTypeIntervention()->save($ti);
                } catch (\Exception $e) {
                    $txt .= ':' . $this->translate($e);
                }
            }
        }

        return new MessengerViewModel();
    }



    public function statutSaisieAction()
    {
        /* @var $typeInterventionStatut TypeInterventionStatut */

        $typeIntervention       = $this->getEvent()->getParam('typeIntervention');
        $typeInterventionStatut = $this->getEvent()->getParam('typeInterventionStatut');
        $form                   = $this->getFormTypeInterventionTypeInterventionStatutSaisie();
        if (empty($typeInterventionStatut)) {
            $title                  = 'Ajout d\'un statut spécifique pour un nouveau type d\'intervention';
            $typeInterventionStatut = $this->getServiceTypeInterventionStatut()->newEntity()
                ->setTypeIntervention($typeIntervention)
                ->setTauxHETDService(1)
                ->setTauxHETDComplementaire(1);
        } else {
            $title = 'Édition d\'un statut pour un type d\'intervention';
        }

        $form->bindRequestSave($typeInterventionStatut, $this->getRequest(), function (TypeInterventionStatut $tis) {
            try {
                $this->getServiceTypeInterventionStatut()->save($tis);
                $this->redirect()->toRoute('type-intervention/statut', ['typeIntervention' => $tis->getTypeIntervention()->getId()]); // redirection vers la page parent en cas de succès
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'title');
    }



    public function statutDeleteAction()
    {
        $ti    = $this->getEvent()->getParam('typeIntervention');
        $tis   = $this->getEvent()->getParam('typeInterventionStatut');
        $title = "Suppression du statut";
        $form  = $this->makeFormSupprimer(function () use ($tis, $ti) {
            $this->getServiceTypeInterventionStatut()->delete($tis);
            $this->redirect()->toRoute('type-intervention/statut', ['typeIntervention' => $ti->getId()]); // redirection vers la page parent en cas de succès
            $this->flashMessenger()->addSuccessMessage('Suppression effectuée');
        });

        return compact('form', 'title');
    }
}
