<?php

namespace Chargens\Service;

use Application\Provider\Privilege\Privileges;
use Application\Service\AbstractEntityService;
use Application\Service\RuntimeException;
use Application\Service\Traits\ContextServiceAwareTrait;
use Chargens\Entity\Db\Scenario;
use Chargens\Entity\Db\SeuilCharge;
use Doctrine\ORM\QueryBuilder;
use Lieu\Entity\Db\Structure;
use Lieu\Service\StructureServiceAwareTrait;
use OffreFormation\Entity\Db\GroupeTypeFormation;
use OffreFormation\Entity\Db\TypeIntervention;
use OffreFormation\Service\Traits\GroupeTypeFormationServiceAwareTrait;
use OffreFormation\Service\Traits\TypeInterventionServiceAwareTrait;
use UnicaenTbl\Service\TableauBordServiceAwareTrait;


/**
 * Description of SeuilChargeService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method SeuilCharge get($id)
 * @method SeuilCharge[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 *
 */
class SeuilChargeService extends AbstractEntityService
{
    use ContextServiceAwareTrait;
    use StructureServiceAwareTrait;
    use ScenarioServiceAwareTrait;
    use GroupeTypeFormationServiceAwareTrait;
    use TypeInterventionServiceAwareTrait;
    use TableauBordServiceAwareTrait;


    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return SeuilCharge::class;
    }



    /**
     * Retourne une nouvelle entité de la classe donnée
     *
     * @return SeuilCharge
     */
    public function newEntity()
    {
        $seuil = parent::newEntity();
        $seuil->setStructure($this->getServiceContext()->getStructure());

        return $seuil;
    }



    /**
     * @param Scenario|integer                 $scenario
     * @param Structure|integer|null           $structure
     * @param GroupeTypeFormation|integer|null $groupeTypeFormation
     * @param TypeIntervention|integer         $typeIntervention
     *
     * @return SeuilCharge|null
     */
    public function getBy($scenario, $structure, $groupeTypeFormation, $typeIntervention)
    {
        $qb = $this->finderByScenario($scenario);
        $this->finderByStructure($structure == 0 ? null : $structure, $qb);
        $this->finderByGroupeTypeFormation($groupeTypeFormation == 0 ? null : $groupeTypeFormation, $qb);
        $this->finderByTypeIntervention($typeIntervention == 0 ? null : $typeIntervention, $qb);
        $this->finderByAnnee($this->getServiceContext()->getAnnee(), $qb);

        $lst = $this->getList($qb);
        if (1 == count($lst)) {
            return reset($lst);
        }

        return null;
    }



    /**
     * @param Scenario|integer                 $scenario
     * @param Structure|integer|null           $structure
     * @param GroupeTypeFormation|integer|null $groupeTypeFormation
     * @param TypeIntervention|integer         $typeIntervention
     * @param integer|null                     $dedoublement
     *
     * @return self
     */
    public function saveBy($scenario, $structure, $groupeTypeFormation, $typeIntervention, $dedoublement)
    {
        $seuil = $this->getBy($scenario, $structure, $groupeTypeFormation, $typeIntervention);

        if ($seuil && null === $dedoublement) {
            return $this->delete($seuil);
        }

        if (!$seuil) {
            if (!$scenario instanceof Scenario) {
                $scenario = $this->getServiceScenario()->get($scenario);
            }
            if (!$structure instanceof Structure) {
                $structure = $this->getServiceStructure()->get($structure);
            }
            if (!$groupeTypeFormation instanceof GroupeTypeFormation) {
                $groupeTypeFormation = $this->getServiceGroupeTypeFormation()->get($groupeTypeFormation);
            }
            if (!$typeIntervention instanceof TypeIntervention) {
                $typeIntervention = $this->getServiceTypeIntervention()->get($typeIntervention);
            }

            $seuil = $this->newEntity();
            $seuil->setAnnee($this->getServiceContext()->getAnnee());
            $seuil->setScenario($scenario);
            $seuil->setStructure($structure);
            $seuil->setGroupeTypeFormation($groupeTypeFormation);
            $seuil->setTypeIntervention($typeIntervention);
        }
        $seuil->setDedoublement($dedoublement);
        $this->save($seuil);

        return $this;
    }



    /**
     * Sauvegarde une entité
     *
     * @param SeuilCharge $entity
     *
     * @return self
     * @throws \RuntimeException
     */
    public function save($entity)
    {
        parent::save($entity);

        $params = [
            'ANNEE_ID'             => $entity->getAnnee()->getId(),
            'SCENARIO_ID'          => $entity->getScenario()->getId(),
            'TYPE_INTERVENTION_ID' => $entity->getTypeIntervention()->getId(),
        ];

        $this->getServiceTableauBord()->calculer('chargens_seuils_def', $params);
        $this->getServiceTableauBord()->calculer('chargens', $params);

        return $entity;
    }



    public function getSeuils(Scenario $scenario)
    {
        $strs = $this->getSeuilsStructures($scenario);
        $tis  = $this->getSeuilsTypesIntervention($scenario);
        $gtfs = $this->getSeuilsGroupesTypesFormation($scenario);

        $sd = $this->getList($this->finderByContext($this->finderByScenario($scenario)));

        $res = [
            'structures' => [],
            'seuils'     => [],
        ];
        $mainLevel = 999;
        foreach ($strs as $structure) {
            if ($structure instanceof Structure) {
                if ($structure->getLevel() < $mainLevel) $mainLevel = $structure->getLevel();
            }
        }
        foreach ($strs as $sid => $structure) {
            $r = [
                'libelle'                 => (string)$structure,
                'level'                   => $structure instanceof Structure ? $structure->getLevel()-$mainLevel : 0,
                'types-interventions'     => [],
                'groupes-type-formations' => [0 => 'Par défaut'],
                'seuils'                  => [],
            ];

            foreach ($tis[0] as $tiid => $ti) {
                $visible = (isset($tis[$sid][$tiid]) && is_bool($tis[$sid][$tiid])) ? $tis[$sid][$tiid] : $ti['visible'];
                if ($visible) {
                    $r['types-interventions'][$tiid] = $ti;
                }
            }

            if (isset($gtfs[$sid])) {
                $r['groupes-type-formations'] += $gtfs[$sid];
            }

            $res['structures'][$sid] = $r;
        }

        foreach ($sd as $seuil) {
            $sid                                = $seuil->getStructure() ? $seuil->getStructure()->getId() : 0;
            $gtfid                              = $seuil->getGroupeTypeFormation() ? $seuil->getGroupeTypeFormation()->getId() : 0;
            $tiid                               = $seuil->getTypeIntervention()->getId();
            $res['seuils'][$sid][$gtfid][$tiid] = $seuil;
        }

        return $res;
    }



    /**
     * @param Scenario $scenario
     * @return array|string[]|Structure[]
     */
    private function getSeuilsStructures(Scenario $scenario): array
    {
        $canViewEtablissement = $this->getAuthorize()->isAllowed(Privileges::getResourceId(Privileges::CHARGENS_SEUIL_ETABLISSEMENT_VISUALISATION));
        $canViewComposantes   = $this->getAuthorize()->isAllowed(Privileges::getResourceId(Privileges::CHARGENS_SEUIL_COMPOSANTE_VISUALISATION));

        $res = [];
        if ($canViewEtablissement) {
            $res[0] = '<i>Par défaut</i>';
        }

        if ($canViewComposantes) {
            $cStructure = $this->getServiceContext()->getStructure();
            if (!$cStructure && $scenario->getStructure()) {
                $cStructure = $scenario->getStructure();
            }

            $qb = $this->getServiceStructure()->finderByHistorique();
            $this->getServiceStructure()->finderByEnseignement($qb);
            if ($cStructure){
                $this->getServiceStructure()->finderByStructure($cStructure, $qb);
            }
            $structures = $this->getServiceStructure()->getList($qb);

            foreach ($structures as $structure) {
                $res[$structure->getId()] = $structure;
            }
        }

        return $res;
    }



    private function getSeuilsTypesIntervention(Scenario $scenario)
    {
        $structure = $this->getServiceContext()->getStructure();
        if (!$structure && $scenario->getStructure()) {
            $structure = $scenario->getStructure();
        }

        $sql = "
        SELECT
          ti.id, 
          ti.code, 
          ti.libelle, 
          tis.structure_id,
          ti.visible all_visible, 
          tis.visible str_visible
        FROM
          type_intervention ti
          LEFT JOIN type_intervention_structure tis ON 
            tis.type_intervention_id = ti.id 
            AND tis.histo_destruction IS NULL
            AND :annee BETWEEN NVL(tis.annee_debut_id,1) AND NVL(tis.annee_fin_id,999999999)
            " . ($structure ? 'AND tis.structure_id = ' . $structure->getId() : '') . "
        WHERE
          ti.histo_destruction IS NULL
        ORDER BY
          ti.ordre
        ";

        $data = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql, ['annee' => $this->getServiceContext()->getAnnee()->getId()]);
        $res  = [];
        foreach ($data as $t) {
            $id          = (int)$t['ID'];
            $code        = (string)$t['CODE'];
            $libelle     = (string)$t['LIBELLE'];
            $structureId = (int)$t['STRUCTURE_ID'];
            $allVisible  = $t['ALL_VISIBLE'] === '1';
            $strVisible  = $t['STR_VISIBLE'] === '1';

            $res[0][$id] = [
                'code'    => $code,
                'libelle' => $libelle,
                'visible' => $allVisible,
            ];
            if ($structureId && ($allVisible || $strVisible)) {
                $res[$structureId][$id] = $strVisible;
            }
        }

        return $res;
    }



    public function getSeuilsGroupesTypesFormation(Scenario $scenario)
    {
        $sql = "
        SELECT DISTINCT
          gtf.id,
          gtf.libelle_long libelle,
          gtf.ordre,
          e.structure_id
        FROM
          etape e
          JOIN type_formation tf ON tf.id = e.type_formation_id
          JOIN groupe_type_formation gtf ON 
            gtf.id = tf.groupe_id 
            AND gtf.histo_destruction IS NULL
        WHERE
          e.histo_destruction IS NULL
          
        ORDER BY
          gtf.ordre
        ";

        $data = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql);
        $res  = [];
        foreach ($data as $t) {
            $id          = (int)$t['ID'];
            $libelle     = (string)$t['LIBELLE'];
            $structureId = (int)$t['STRUCTURE_ID'];

            $res[0][$id]            = $libelle;
            $res[$structureId][$id] = $libelle;
        }

        return $res;
    }



    /**
     *
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param string                     $alias
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function finderByContext(QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        $this->finderByAnnee($this->getServiceContext()->getAnnee(), $qb);
        if ($cStructure = $this->getServiceContext()->getStructure()) {
            $qb->andWhere($alias . '.structure = :structure OR ' . $alias . '.structure IS NULL')->setParameter(
                'structure', $cStructure
            );
        }

        return $qb;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'seuilc';
    }

}