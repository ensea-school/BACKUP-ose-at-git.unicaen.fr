<?php

namespace Application\Controller;

use Application\Entity\Db\WfEtapeDep;
use Application\Form\Workflow\Traits\DependanceFormAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\WfEtapeDepServiceAwareTrait;
use Application\Service\Traits\WfEtapeServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use UnicaenApp\Exception\LogicException;
use UnicaenApp\View\Model\MessengerViewModel;
use UnicaenTbl\Service\TableauBordServiceAwareTrait;


/**
 * Description of WorkflowController
 *
 * @method \Doctrine\ORM\EntityManager            em()
 * @method \Application\Controller\Plugin\Context context()
 *
 */
class WorkflowController extends AbstractController
{
    use ContextServiceAwareTrait;
    use WfEtapeDepServiceAwareTrait;
    use DependanceFormAwareTrait;
    use WorkflowServiceAwareTrait;
    use WfEtapeServiceAwareTrait;
    use TableauBordServiceAwareTrait;


    public function indexAction()
    {
        return [];
    }



    public function dependancesAction()
    {
        $dql    = '
        SELECT
          we
        FROM
          Application\Entity\Db\WfEtape we
        ORDER BY
          we.ordre
        ';
        $etapes = $this->em()->createQuery($dql)->getResult();


        $dql = '
        SELECT
          wed, es, ep
        FROM
          Application\Entity\Db\WfEtapeDep wed
          JOIN wed.etapeSuiv es
          JOIN wed.etapePrec ep
        ORDER BY
          es.ordre, ep.ordre
        ';

        $query = $this->em()->createQuery($dql);

        $d = $query->getResult();
        /* @var $d WfEtapeDep[] */
        $deps = [];
        foreach ($d as $dep) {
            $deps[$dep->getEtapeSuiv()->getId()][$dep->getEtapePrec()->getId()] = $dep;
        }

        return compact('etapes', 'deps');
    }



    public function saisieDepAction()
    {
        $wfEtapeDep = $this->getEvent()->getParam('wfEtapeDep');
        /* @var $wfEtapeDep WfEtapeDep */

        if (!$wfEtapeDep) {
            $etapeSuivanteId = $this->params()->fromQuery('etapeSuivante');

            $wfEtapeDep = $this->getServiceWfEtapeDep()->newEntity();
            if ($etapeSuivanteId) {
                $etapeSuivante = $this->getServiceWfEtape()->get($etapeSuivanteId);
                $wfEtapeDep->setEtapeSuiv($etapeSuivante);
            }
        }

        $title = "Saisie d'une dépendance";

        $form = $this->getFormWorkflowDependance();
        $form->bindRequestSave($wfEtapeDep, $this->getRequest(), function ($wfEtapeDep) {
            try {
                $this->getServiceWfEtapeDep()->save($wfEtapeDep);
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('title', 'form');
    }



    public function suppressionDepAction()
    {
        if (!($wfEtapeDep = $this->getEvent()->getParam('wfEtapeDep'))) {
            throw new \RuntimeException('L\'identifiant n\'est pas bon ou n\'a pas été fourni');
        }

        $form = $this->makeFormSupprimer(function () use ($wfEtapeDep) {
            $this->getServiceWfEtapeDep()->delete($wfEtapeDep);
        });

        return compact('wfEtapeDep', 'form');
    }



    public function calculerToutAction()
    {
        $action = $this->params()->fromQuery('action') === '1';
        $title  = 'Calcul du workflow...';
        $error  = null;

        if ($action) {
            try {
                $this->getServiceWorkflow()->calculerTout();
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        return compact('action', 'title', 'error');
    }



    public function calculTableauxBordAction()
    {
        $result = $this->getServiceTableauBord()->calculerTout(['formule'],function (array $d) {
            $tblLine = 'Tableau de bord : ' . str_pad($d['tableau-bord'], 30);
            $c       = \OseAdmin::instance()->console();
            $c->print($tblLine);
            $c->print('Calcul en cours...', $c::COLOR_LIGHT_PURPLE);
        }, function (array $d) {
            $c       = \OseAdmin::instance()->console();
            $tblLine = 'Tableau de bord : ' . str_pad($d['tableau-bord'], 30);
            $c->print("\r" . $tblLine);
            if ($d['result']) {
                $duree = round($d['duree'], 3) . ' secondes';
                $c->println('Effectué en ' . $duree, $c::COLOR_GREEN);
            } else {
                $c->println('Erreur : ' . $d['exception']->getMessage(), $c::BG_RED);
            }
        });
        $c      = \OseAdmin::instance()->console();
        $c->println('Fin du calcul des tableaux de bord');
        if ($result) {
            $c->println('Tout c\'est bien passé', $c::COLOR_GREEN);
        } else {
            $c->println('Attention : des erreurs ont été rencontrées!!', $c::BG_RED);
        }
    }



    public function feuilleDeRouteRefreshAction()
    {
        /** @var Intervenant $intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        if ($intervenant) {
            $errors = $this->getServiceWorkflow()->calculerTableauxBord([], $intervenant);
        }

        if (empty($errors)) {
            $this->flashMessenger()->addSuccessMessage('Feuille de route actualisée.');
        } else {
            foreach ($errors as $error) {
                $this->flashMessenger()->addErrorMessage($error->getMessage());
            }
        }

        return new MessengerViewModel();
    }



    public function feuilleDeRouteBtnNextAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

        $wfEtapeCode = $this->params()->fromRoute('wfEtapeCode');
        if (!$wfEtapeCode) {
            throw new LogicException('L\'étape du workflow doit être précisée');
        }

        return compact('intervenant', 'wfEtapeCode');
    }
}