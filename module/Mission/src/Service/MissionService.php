<?php

namespace Mission\Service;

use Application\Acl\Role;
use Application\Entity\Db\Validation;
use Application\Provider\Privilege\Privileges;
use Application\Service\AbstractEntityService;
use Application\Service\Traits\FichierServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use Application\Service\Traits\TypeValidationServiceAwareTrait;
use Application\Service\Traits\ValidationServiceAwareTrait;
use Contrat\Entity\Db\Contrat;
use Mission\Assertion\SaisieAssertion;
use Mission\Entity\Db\Candidature;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\VolumeHoraireMission;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use UnicaenVue\View\Model\AxiosModel;

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
    public function getEntityClass (): string
    {
        return Mission::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias (): string
    {
        return 'm';
    }



    public function data (array $parameters): AxiosModel
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



    public function deleteVolumeHoraire (VolumeHoraireMission $volumeHoraireMission): self
    {
        $volumeHoraireMission->historiser();
        $this->saveVolumeHoraire($volumeHoraireMission);

        return $this;
    }



    public function saveVolumeHoraire (VolumeHoraireMission $vhm): self
    {
        if (!$vhm->getTypeVolumeHoraire()) {
            $vhm->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->getPrevu());
        }
        if (!$vhm->getSource()) {
            $vhm->setSource($this->getServiceSource()->getOse());
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
        }

        $this->getEntityManager()->persist($vhm);
        $this->getEntityManager()->flush($vhm);

        return $this;
    }



    public function createMissionFromCandidature (Candidature $candidature): ?Mission
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



    /**
     * @param Mission $entity
     *
     * @return Mission
     */
    public function save ($entity)
    {
        foreach ($entity->getVolumesHorairesPrevus() as $vh) {
            $this->saveVolumeHoraire($vh);
        }

        parent::save($entity);

        return $entity;
    }



    public function getContratPrimeMission (array $parameters)
    {


        $sql = "
        WITH contrat_mission AS (
            SELECT 
                    c.id                    contrat_id,
                    m.date_debut 			date_debut_contrat,
                    m.date_fin              date_fin_contrat,
                    m.libelle_mission		libelle_mission,
                    m.intervenant_id        intervenant_id,
                    tm.libelle              type_mission,
                    s.libelle_court         libelle_structure,
                    c.declaration_id	    declaration_id,
                    c.date_refus_prime      date_refus_prime,
                    c.histo_destruction     histo_destruction
            FROM mission m 
            JOIN contrat c ON c.mission_id = m.id
            JOIN type_mission tm ON tm.id = m.type_mission_id 
            JOIN structure s ON s.id = m.structure_id 
            JOIN validation v ON v.id = c.validation_id AND v.histo_destruction IS null 
            WHERE 
                c.type_contrat_id = (SELECT id FROM type_contrat WHERE code = 'CONTRAT')
                AND c.histo_destruction IS NULL
                AND c.date_retour_signe IS NOT NULL
        )
        SELECT DISTINCT 
                cm.contrat_id            contrat_id,
                cm.date_debut_contrat	 date_debut_contrat,
                cm.date_fin_contrat		 date_fin_contrat,
                cm.libelle_mission		 libelle_mission,
                cm.intervenant_id        intervenant_id,
                cm.type_mission          type_mission,
                cm.libelle_structure     libelle_structure,
                cm.declaration_id	     declaration_id,
                cm.date_refus_prime      date_refus_prime,
                cm.histo_destruction     histo_destruction,
                f.nom					 fichier_nom,
                f.validation_id          validation_id,
                f.histo_creation         date_depot,
                u.display_name           user_depot,
                vf.histo_modification    date_validation,
                u2.display_name          user_validation,
                ROWNUM                   numero
        FROM contrat_mission cm
        LEFT JOIN fichier f ON f.id = cm.declaration_id
        LEFT JOIN utilisateur u ON f.histo_createur_id = u.id 
        LEFT JOIN validation vf ON vf.id = f.validation_id AND vf.histo_destruction IS null 
        LEFT JOIN utilisateur u2 ON u2.id = vf.histo_createur_id 
        LEFT JOIN contrat_mission cm_suiv ON cm_suiv.histo_destruction IS NULL 
                                         AND cm_suiv.date_fin_contrat <> cm.date_fin_contrat
                                         AND cm_suiv.intervenant_id = cm.intervenant_id 
                                         AND cm.date_fin_contrat BETWEEN cm_suiv.date_debut_contrat-1 AND cm_suiv.date_fin_contrat
        WHERE  
            cm.intervenant_id = 12291	
            AND cm.date_fin_contrat < SYSDATE
            AND cm_suiv.contrat_id IS NULL       
        ORDER BY cm.date_debut_contrat ASC
       ";

        $data       = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql, ['intervenant' => $parameters['intervenant']]);
        $triggers   = [];
        $properties = [];


        return new AxiosModel($data, $properties, $triggers);
    }

}