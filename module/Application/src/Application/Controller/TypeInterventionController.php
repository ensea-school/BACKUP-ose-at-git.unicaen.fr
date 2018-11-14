<?php

namespace Application\Controller;

use Application\Entity\Db\Structure;
use Application\Service\Traits\TypeInterventionStructureServiceAwareTrait;
use Application\Service\Traits\TypeInterventionServiceAwareTrait;
use Application\Entity\Db\TypeIntervention;
use Application\Entity\Db\TypeInterventionStructure;
use Application\Form\TypeIntervention\Traits\TypeInterventionSaisieFormAwareTrait;
use Application\Form\TypeIntervention\Traits\TypeInterventionStructureSaisieFormAwareTrait;
use Application\Exception\DbException;
use UnicaenApp\View\Model\MessengerViewModel;
use Application\Service\Traits\ContextServiceAwareTrait;

class TypeInterventionController extends AbstractController
{
    use TypeInterventionServiceAwareTrait;
    use TypeInterventionStructureServiceAwareTrait;
    use TypeInterventionSaisieFormAwareTrait;
    use TypeInterventionStructureSaisieFormAwareTrait;
    use ContextServiceAwareTrait;



    /* @var $tiss TypeInterventionStructure */
    public function indexAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            TypeIntervention::class,
        ]);


        $this->em()->getFilters()->enable('historique')->init([
            TypeInterventionStructure::class,
        ]);
        $anneeId = $this->getServiceContext()->getAnnee()->getId();

        $dql                          = "
        SELECT
          sti, adeb, afin
        FROM
          " . \Application\Entity\Db\TypeInterventionStructure::class . " sti
          LEFT JOIN sti.anneeDebut adeb
          LEFT JOIN sti.anneeFin afin
        WHERE
          COALESCE($anneeId,$anneeId) BETWEEN COALESCE(adeb.id,$anneeId) AND COALESCE(afin.id,$anneeId)
        "; // COALESCE($anneeId,$anneeId) bizarre mais c'est pour contourner un bug de doctrine!!!!!!

        $sti                = $this->em()->createQuery($dql)->getResult();
        $typesInterventionsStructures = [];
        foreach ($sti as $tiss) {
            $stiID                      = $tiss->getId();
            $typesInterventionsStructures[$stiID] = $tiss;
        }

        $allTypesInterventions        = $this->getServiceTypeIntervention()->getList();

        $dql                          = "
        SELECT
          ti, adeb, afin
        FROM
          " . \Application\Entity\Db\TypeIntervention::class . " ti
          LEFT JOIN ti.anneeDebut adeb
          LEFT JOIN ti.anneeFin afin
        WHERE
          COALESCE($anneeId,$anneeId) BETWEEN COALESCE(adeb.id,$anneeId) AND COALESCE(afin.id,$anneeId)
        "; // COALESCE($anneeId,$anneeId) bizarre mais c'est pour contourner un bug de doctrine!!!!!!

        /* @var $tis TypeIntervention[] */
        $tis                = $this->em()->createQuery($dql)->getResult();
        $typesInterventions = [];
        foreach ($tis as $ti) {
            $tiID                      = $ti->getId();
            $typesInterventions[$tiID] = $ti;
        }

        foreach ($typesInterventionsStructures as $tiss) {
            $ti = $tiss->getTypeIntervention();
            if ((!$tiss->getAnneeDebut() || $tiss->getAnneeDebut()->getId() <= $anneeId) && (!$tiss->getAnneeFin() || $tiss->getAnneeFin()->getId() >= $anneeId)) {
                if ((!in_array($ti, $typesInterventions)) && (in_array($ti, $allTypesInterventions))) {
                    $tiID = $ti->getId();
                    $typesInterventions[$tiID] = $ti;
                }
            }
        }

        return compact('typesInterventions', 'typesInterventionsStructures');
    }



    public function saisieAction()
    {
        /* @var $typeIntervention TypeIntervention */

        $typeIntervention = $this->getEvent()->getParam('typeIntervention');
        $form             = $this->getFormTypeInterventionSaisie();
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
                $e = DbException::translate($e);
                $this->flashMessenger()->addErrorMessage($e->getMessage() . ':' . $ti->getId());
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
            $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
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
        $form                      = $this->getFormTypeInterventionStructureSaisie();
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
                $e = DbException::translate($e);
                $this->flashMessenger()->addErrorMessage($e->getMessage() . ':' . $tis->getId());
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
            $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
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
                    $e   = DbException::translate($e);
                    $txt .= ':' . $e->getMessage();
                }
            }
        }

        return new JsonModel(['msg' => 'Tri des champs effectué']);
    }
}
