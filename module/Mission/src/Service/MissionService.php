<?php

namespace Mission\Service;

use Application\Provider\Privileges;
use Application\Service\AbstractEntityService;
use Application\Service\Traits\FichierServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use Mission\Assertion\SaisieAssertion;
use Mission\Entity\Db\Candidature;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\Prime;
use Mission\Entity\Db\VolumeHoraireMission;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use UnicaenVue\View\Model\AxiosModel;
use Workflow\Service\TypeValidationServiceAwareTrait;
use Workflow\Service\ValidationServiceAwareTrait;

/**
 * Description of MissionService
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 *
 * @method Mission get($id)
 * @method Mission[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method Mission newEntity()
 *
 */
class MissionService extends AbstractEntityService
{
    use TypeVolumeHoraireServiceAwareTrait;
    use SourceServiceAwareTrait;
    use ValidationServiceAwareTrait;
    use TypeValidationServiceAwareTrait;
    use FichierServiceAwareTrait;

    /**
     * Retourne la classe des entités
     *
     * @return string
     * @throws \RuntimeException
     */
    public function getEntityClass(): string
    {
        return Mission::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(): string
    {
        return 'm';
    }



    public function data(array $parameters): AxiosModel
    {
        $dql = "
        SELECT 
          m, tm, str, tr, valid, vh, vvh, ctr, tvh
        FROM 
          " . Mission::class . " m
          JOIN m.typeMission tm
          JOIN m.structure str
          LEFT JOIN m.tauxRemu tr
          LEFT JOIN m.validations valid WITH valid.histoDestruction IS NULL
          LEFT JOIN m.volumesHoraires vh WITH vh.histoDestruction IS NULL
          LEFT JOIN vh.typeVolumeHoraire tvh
          LEFT JOIN vh.validations vvh WITH vvh.histoDestruction IS NULL
          LEFT JOIN vh.contrat ctr WITH ctr.histoDestruction IS NULL
        WHERE 
          m.histoDestruction IS NULL 
          " . dqlAndWhere([
                                                                            'intervenant' => 'm.intervenant',
                                                                            'mission'     => 'm',
                                                                        ], $parameters) . "
        ORDER BY
          m.dateDebut,
          vh.histoCreation
        ";

        $query = $this->getEntityManager()->createQuery($dql)->setParameters($parameters);

        $properties = [
            'id',
            'libelle',
            'libelleMission',
            ['typeMission', ['libelle', 'accompagnementEtudiants', 'besoinFormation']],
            'dateDebut',
            'dateFin',
            ['structure', ['libelle']],
            ['tauxRemu', ['libelle']],
            ['tauxRemuMajore', ['libelle']],
            'heuresFormation',
            'description',
            'histoCreation',
            'histoCreateur',
            'heures',
            'heuresValidees',
            'heuresRealisees',
            ['volumesHorairesPrevus', [
                'id',
                'heures',
                'valide',
                'validation',
                'canValider',
                'canDevalider',
                'canSupprimer',
                'histoCreation',
                'histoCreateur',
            ]],
            'etudiantsSuivis',
            'contrat',
            'valide',
            'validation',
            'canSaisie',
            'canAddHeures',
            'canValider',
            'canDevalider',
            'canSupprimer',
        ];

        $triggers = [
            '/'                      => function (Mission $original, array $extracted) {
                $extracted['canSaisie']    = $this->getAuthorize()->isAllowed($original, Privileges::MISSION_EDITION);
                $extracted['canAddHeures'] = $this->getAuthorize()->isAllowed($original, SaisieAssertion::CAN_ADD_HEURES);
                $extracted['canValider']   = $this->getAuthorize()->isAllowed($original, Privileges::MISSION_VALIDATION);
                $extracted['canDevalider'] = $this->getAuthorize()->isAllowed($original, Privileges::MISSION_DEVALIDATION);
                $extracted['canSupprimer'] = $extracted['canSupprimer'] && $this->getAuthorize()->isAllowed($original, Privileges::MISSION_EDITION);

                return $extracted;
            },
            '/volumesHorairesPrevus' => function ($original, $extracted) {
                //$extracted['canSaisie'] &= $this->getAuthorize()->isAllowed($original, Privileges::MISSION_EDITION);
                $extracted['canValider']   = $this->getAuthorize()->isAllowed($original, Privileges::MISSION_VALIDATION);
                $extracted['canDevalider'] = $this->getAuthorize()->isAllowed($original, Privileges::MISSION_DEVALIDATION);
                $extracted['canSupprimer'] = $extracted['canSupprimer'] && $this->getAuthorize()->isAllowed($original, Privileges::MISSION_EDITION);

                return $extracted;
            },
        ];

        return new AxiosModel($query, $properties, $triggers);
    }



    public function deleteVolumeHoraire(VolumeHoraireMission $volumeHoraireMission): self
    {
        $volumeHoraireMission->historiser();
        $this->saveVolumeHoraire($volumeHoraireMission);

        return $this;
    }



    public function controleChevauchementOk(VolumeHoraireMission $vhm): bool
    {
        $sql    = "
        SELECT
          vhm.id
        FROM
          volume_horaire_mission vhm
          JOIN mission m ON m.id = vhm.mission_id
        WHERE
          vhm.histo_destruction IS NULL
          AND vhm.type_volume_horaire_id = :typeVolumeHoraire
          AND m.intervenant_id = :intervenant
          AND vhm.horaire_debut IS NOT NULL
          AND vhm.horaire_fin IS NOT NULL
          AND vhm.id <> :id
          AND NOT(
            :horaireDebut >= to_char(vhm.horaire_fin, 'YYYY-mm-dd HH24:MI') OR :horaireFin <= to_char(vhm.horaire_debut, 'YYYY-mm-dd HH24:MI')
          )";
        $params = [
            'typeVolumeHoraire' => $vhm->getTypeVolumeHoraire()->getId(),
            'intervenant'       => $vhm->getMission()->getIntervenant()->getId(),
            'horaireDebut'      => $vhm->getHoraireDebut()->format('Y-m-d H:i'),
            'horaireFin'        => $vhm->getHoraireFin()->format('Y-m-d H:i'),
            'id'                => $vhm->getId() ?? 0,
        ];
        $r      = $this->entityManager->getConnection()->fetchAssociative($sql, $params);

        return $r === false;
    }



    public function saveVolumeHoraire(VolumeHoraireMission $vhm): self
    {
        if (!$vhm->getTypeVolumeHoraire()) {
            $vhm->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->getPrevu());
        }
        if (!$vhm->getSource()) {
            $vhm->setSource($this->getServiceSource()->getOse());
        }
        if (!$vhm->getSourceCode()) {
            $vhm->setSourceCode(uniqid());
        }

        if ($vhm->getTypeVolumeHoraire()->isRealise() && $vhm->estNonHistorise()) {
            if ($vhm->getHoraireFin() < $vhm->getMission()->getDateDebut()) {
                throw new \Exception('La date renseignée est antérieure au début de la mission');
            }
            $dateFin = $vhm->getMission()->getDateFin()->modify('+1 day'); // jour de fin révolu
            if ($vhm->getHoraireDebut() > $dateFin) {
                throw new \Exception('La date renseignée est postérieure à la fin de la mission');
            }

            $now = new \DateTime();
            $now->modify('+10 minutes'); // tolérance de 10 minutes
            if ($vhm->getHoraireFin() > $now) {
                throw new \Exception('Vous ne pouvez saisir de suivi avant qu\'il ne soit terminé');
            }

            if (!$this->controleChevauchementOk($vhm)) {
                throw new \Exception('Impossible d\'ajouter ce suivi : il y a chevauchement avec d\'autres heures renseignées');
            }
        }

        $this->getEntityManager()->persist($vhm);
        $this->getEntityManager()->flush($vhm);

        return $this;
    }



    public function deletePrimeMissions(Prime $prime)
    {
        $missions = $prime->getMissions();
        foreach ($missions as $mission) {
            $mission->setPrime(null);
            $this->save($mission);
        }

        return true;
    }



    /**
     * @param Mission $entity
     *
     * @return Mission
     */
    public function save($entity): Mission
    {
        foreach ($entity->getVolumesHorairesPrevus() as $vh) {
            $this->saveVolumeHoraire($vh);
        }

        parent::save($entity);

        return $entity;
    }



    public function createMissionFromCandidature(Candidature $candidature): ?Mission
    {
        $mission = $this->newEntity();
        $mission->setEntityManager($this->getEntityManager());
        $mission->setIntervenant($candidature->getIntervenant());
        $mission->setTypeMission($candidature->getOffre()->getTypeMission());
        $mission->setTauxRemu($candidature->getOffre()->getTypeMission()->getTauxRemu());
        $mission->setTauxRemuMajore($candidature->getOffre()->getTypeMission()->getTauxRemuMajore());
        $mission->setDateDebut($candidature->getOffre()->getDateDebut());
        $mission->setDateFin($candidature->getOffre()->getDateFin());
        $mission->setDescription($candidature->getOffre()->getDescription());
        $mission->setStructure($candidature->getOffre()->getStructure());
        $mission->setHeures($candidature->getOffre()->getNombreHeures());
        $mission->setLibelleMission($candidature->getOffre()->getTitre());

        $this->save($mission);

        return $mission;
    }

}