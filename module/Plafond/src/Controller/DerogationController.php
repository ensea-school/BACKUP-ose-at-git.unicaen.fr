<?php

namespace Plafond\Controller;

use Application\Controller\AbstractController;
use Application\Provider\Privileges;
use Intervenant\Entity\Db\Intervenant;
use Plafond\Entity\Db\PlafondDerogation;
use Plafond\Entity\Db\PlafondEtat;
use Plafond\Service\PlafondServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;


/**
 * Description of DerogationController
 *
 * @author UnicaenCode
 */
class DerogationController extends AbstractController
{
    use TypeVolumeHoraireServiceAwareTrait;
    use PlafondServiceAwareTrait;

    public function indexAction()
    {
        $intervenant          = $this->getEvent()->getParam('intervenant');
        $typesVolumesHoraires = $this->getServiceTypeVolumeHoraire()->getList();

        $canEdit = $this->isAllowed($intervenant, Privileges::PLAFONDS_DEROGATIONS_EDITION);

        if ($canEdit && $this->params()->fromPost('action') == 'save') {
            $this->saveDerogations($intervenant);
        }

        $data = [];
        foreach ($typesVolumesHoraires as $typeVolumeHoraire) {
            $reponse = $this->getServicePlafond()->derogations($typeVolumeHoraire, $intervenant);
            $tvh     = $typeVolumeHoraire->getCode();

            foreach ($reponse as $pc) {
                if (!isset($data[$pc->getId()])) {
                    $data[$pc->getId()] = [
                        'libelle'    => $pc->getLibelle(),
                        'derogation' => floatToString($pc->getDerogation()),
                    ];
                }
                $data[$pc->getId()][$tvh . '-etat']        = $this->getServicePlafond()->getEtat($pc->getEtat())->getLibelle();
                $data[$pc->getId()][$tvh . '-heures']      = floatToString($pc->getHeures());
                $data[$pc->getId()][$tvh . '-plafond']     = floatToString($pc->getPlafond());
                $data[$pc->getId()][$tvh . '-depassement'] = $pc->isDepassement();
                if ($pc->isDepassement()) {
                    if ($pc->getEtat() == PlafondEtat::INFORMATIF) {
                        $data[$pc->getId()][$tvh . '-class'] = 'bg-warning';
                    } elseif ($pc->getEtat() == PlafondEtat::BLOQUANT) {
                        $data[$pc->getId()][$tvh . '-class'] = 'bg-danger';
                    }
                }
            }
        }

        return compact('intervenant', 'data', 'typesVolumesHoraires', 'canEdit');
    }



    protected function saveDerogations(Intervenant $intervenant)
    {
        $this->em()->getFilters()->enable('historique')->init([
            PlafondDerogation::class,
        ]);

        $derogations = [];

        $post = $this->params()->fromPost();
        unset($post['action']);
        foreach ($post as $id => $heures) {
            $id     = (int)substr($id, 8);
            $heures = stringToFloat($heures);
            if ($heures != 0) {
                $derogations[$id]['new'] = $heures;
            }
        }

        /* @var $entities PlafondDerogation[] */
        $dql      = "SELECT pd, p
        FROM   " . PlafondDerogation::class . " pd
        JOIN pd.plafond p
        WHERE pd.intervenant = :intervenant";
        $entities = $this->em()->createQuery($dql)->execute(compact('intervenant'));
        foreach ($entities as $entity) {
            $derogations[$entity->getPlafond()->getId()]['old'] = $entity;
        }

        $changed = false;
        foreach ($derogations as $id => $derogation) {
            if (isset($derogation['old']) && !isset($derogation['new'])) {
                // delete
                $derogation['old']->historiser();
                $this->em()->persist($derogation['old']);
                $this->em()->flush($derogation['old']);
                $changed = true;
            } elseif (!isset($derogation['old']) && isset($derogation['new'])) {
                // insert
                $derog = new PlafondDerogation();
                $derog->setIntervenant($intervenant);
                $derog->setPlafond($this->getServicePlafond()->get($id));
                $derog->setHeures($derogation['new']);
                $this->em()->persist($derog);
                $this->em()->flush($derog);
                $changed = true;
            } elseif (isset($derogation['old']) && isset($derogation['new'])) {
                if ($derogation['old']->getHeures() != $derogation['new']) {
                    // update
                    $derogation['old']->setHeures($derogation['new']);
                    $this->em()->persist($derogation['old']);
                    $this->em()->flush($derogation['old']);
                    $changed = true;
                }
            }
        }

        if ($changed) {
            $this->flashMessenger()->addSuccessMessage('Les modifications ont bien été prises en compte');
        }
        $this->getServicePlafond()->calculerDepuisEntite($intervenant);
    }

}