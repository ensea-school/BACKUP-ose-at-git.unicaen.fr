<?php

namespace Application\Controller;

use Application\Entity\Db\Agrement;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\TblAgrement;
use Application\Entity\Db\TypeAgrement;
use Application\Entity\Db\WfIntervenantEtape;
use Application\Form\Agrement\Traits\SaisieAwareTrait;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\AgrementAwareTrait;
use Application\Service\Traits\IntervenantAwareTrait;
use Application\Service\Traits\ServiceAwareTrait;
use Application\Service\Traits\StructureAwareTrait;
use Application\Service\Traits\TblAgrementServiceAwareTrait;
use Zend\Form\Element\Checkbox;
use Zend\View\Model\ViewModel;
use Application\Service\Traits\ContextAwareTrait;

/**
 * Opérations sur les agréments.
 *
 *
 */
class AgrementController extends AbstractController
{
    use TblAgrementServiceAwareTrait;
    use AgrementAwareTrait;
    use IntervenantAwareTrait;
    use ServiceAwareTrait;
    use ContextAwareTrait;
    use SaisieAwareTrait;
    use StructureAwareTrait;



    /**
     * Initialisation des filtres Doctrine pour les historique.
     * Objectif : laisser passer les enregistrements passés en historique pour mettre en évidence ensuite les erreurs
     * éventuelles
     * (services sur des enseignements fermés, etc.)
     */
    protected function initFilters()
    {
        $this->em()->getFilters()->enable('historique')->init([
            Agrement::class,
            TypeAgrement::class,
        ]);
    }



    /**
     * Page de menu des agréments
     */
    public function indexAction()
    {
        return [];
    }



    /**
     * Détails d'un agrément.
     *
     * @return ViewModel
     */
    public function voirAction()
    {
        $agrement = $this->getEvent()->getParam('agrement');

        return compact('agrement');
    }



    /**
     * Liste des agréments d'un type donné, concernant un intervenant.
     */
    public function listerAction()
    {
        $this->initFilters();

        $role         = $this->getServiceContext()->getSelectedIdentityRole();
        $typeAgrement = $this->getEvent()->getParam('typeAgrement');
        /* @var $typeAgrement TypeAgrement */
        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

        $qb = $this->getServiceTblAgrement()->finderByTypeAgrement($typeAgrement);
        $this->getServiceTblAgrement()->finderByIntervenant($intervenant, $qb);

        $this->getServiceTblAgrement()->leftJoin('applicationAgrement', $qb, 'agrement', true);

        $tas = $this->getServiceTblAgrement()->getList($qb);
        /* @var $tas TblAgrement[] */

        $needStructure = false;
        $hasActions    = false;
        $data          = [];
        foreach ($tas as $ta) {

            /* Actions éventuelles */
            if (($a = $ta->getAgrement()) && $this->isAllowed($ta, $ta->getTypeAgrement()->getPrivilegeSuppression())) {

                $params      = [
                    'agrement'    => $a->getId(),
                    'intervenant' => $ta->getIntervenant()->getSourceCode(),
                ];
                $actionUrl   = $this->url()->fromRoute('intervenant/agrement/supprimer', $params);
                $actionLabel = '<span class="glyphicon glyphicon-trash"></span> Retirer l\'agrément';
            } elseif (!$ta->getAgrement() && $this->isAllowed($ta, $ta->getTypeAgrement()->getPrivilegeEdition())) {
                $params = [
                    'typeAgrement' => $ta->getTypeAgrement()->getId(),
                    'intervenant'  => $ta->getIntervenant()->getSourceCode(),
                ];
                if ($ta->getStructure()) $params['structure'] = $ta->getStructure()->getId();

                $actionUrl   = $this->url()->fromRoute('intervenant/agrement/ajouter', $params);
                $actionLabel = '<span class="glyphicon glyphicon-ok"></span> Agréer';
            } else {
                $actionUrl   = null;
                $actionLabel = null;
            }

            $data[] = compact('ta', 'actionUrl', 'actionLabel');
            if (!$hasActions && $actionUrl) $hasActions = true;
            if ($ta->getStructure()) $needStructure = true;
        }

        return compact('role', 'typeAgrement', 'intervenant', 'data', 'needStructure', 'hasActions');
    }



    public function saisirAction()
    {
        $this->initFilters();

        /* @var $agrement Agrement */
        $agrement = $this->getEvent()->getParam('agrement');
        if (!$agrement) {
            $agrement = $this->getServiceAgrement()->newEntity();
            $agrement->setType($this->getEvent()->getParam('typeAgrement'));
            $agrement->setIntervenant($this->getEvent()->getParam('intervenant'));
            $agrement->setStructure($this->getEvent()->getParam('structure'));
        }

        $title = sprintf("Agrément par %s <small>%s</small>", $agrement->getType()->toString(true), $agrement->getIntervenant());

        $form = $this->getFormAgrementSaisie();
        $form->bindRequestSave($agrement, $this->getRequest(), function ($a) {
            $this->getServiceAgrement()->save($a);
        });

        return compact('title', 'form');
    }



    public function saisirLotAction()
    {
        $typeAgrement = $this->getEvent()->getParam('typeAgrement');
        /* @var $typeAgrement TypeAgrement */

        $title = sprintf("Agrément par %s", $typeAgrement->toString(true));
        $role  = $this->getServiceContext()->getSelectedIdentityRole();

        $form = $this->getFormAgrementSaisie();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $dateDecision = $form->get('dateDecision')->normalizeDate($form->get('dateDecision')->getValue());
                $agreer       = $this->params()->fromPost('agreer', []);

                foreach ($agreer as $a => $val) {
                    if ('1' === $val) {
                        $ids = explode('-', $a);

                        $agrement = $this->getServiceAgrement()->newEntity();
                        /* @var $agrement Agrement */
                        $agrement->setDateDecision($dateDecision);
                        $agrement->setType($typeAgrement);
                        if (isset($ids[0])) {
                            $agrement->setIntervenant($this->getServiceIntervenant()->get($ids[0]));
                        }
                        if (isset($ids[1])) {
                            $agrement->setStructure($this->getServiceStructure()->get($ids[1]));
                        }
                        try {
                            $this->getServiceAgrement()->save($agrement);
                        } catch (\Exception $e) {
                            $this->flashMessenger()->addErrorMessage($e->getMessage());
                        }
                    }
                }
            }
        }

        $dql = "
        SELECT
          wie, i, s
        FROM
          " . WfIntervenantEtape::class . " wie
          JOIN wie.intervenant i
          JOIN wie.etape we
          LEFT JOIN wie.structure s
        WHERE
          i.annee = :annee
          AND we.code = :typeAgrement
          AND wie.atteignable = true
          AND wie.realisation = 0
          " . ($role->getStructure() ? 'AND (wie.structure = :structure OR (wie.structure IS NULL AND i.structure = :structure))' : '') . "
        ORDER BY
          s.libelleCourt, i.nomUsuel, i.prenom
        ";

        $query = $this->em()->createQuery($dql);
        $query->setParameter('annee', $this->getServiceContext()->getAnnee());
        $query->setParameter('typeAgrement', $typeAgrement->getCode());
        if ($role->getStructure()) {
            $query->setParameter('structure', $role->getStructure());
        }

        $res = $query->getResult();

        /* @var $res WfIntervenantEtape[] */

        $needStructure = false;
        $needAction    = false;
        $data          = [];
        $canEdit       = $this->isAllowed(Privileges::getResourceId($typeAgrement->getPrivilegeEdition()));
        foreach ($res as $wie) {

            if ($canEdit) {
                $ids = [
                    $wie->getIntervenant()->getId(),
                ];
                if ($wie->getStructure()) {
                    $ids[] = $wie->getStructure()->getId();
                }

                $checkbox = new Checkbox('agreer[' . implode('-', $ids) . ']');
                $checkbox->setValue(45);
                $needAction = true;
            } else {
                $checkbox = null;
            }
            if ($wie->getStructure()) $needStructure = true;
            $data[] = compact('wie', 'actionUrl', 'checkbox');
        }


        return compact('title', 'form', 'typeAgrement', 'data', 'needStructure', 'needAction');
    }



    public function supprimerAction()
    {
        if (!($agrement = $this->getEvent()->getParam('agrement'))) {
            throw new \RuntimeException('L\'identifiant n\'est pas bon ou n\'a pas été fourni');
        }

        $form = $this->makeFormSupprimer(function () use ($agrement) {
            $this->getServiceAgrement()->delete($agrement);
        });

        return compact('agrement', 'form');
    }

}