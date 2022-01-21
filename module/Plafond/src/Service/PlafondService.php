<?php

namespace Plafond\Service;

use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\FonctionReferentiel;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Service;
use Application\Entity\Db\ServiceReferentiel;
use Application\Entity\Db\StatutIntervenant;
use Application\Entity\Db\Structure;
use Application\Entity\Db\VolumeHoraire;
use Application\Service\AbstractEntityService;
use Application\Service\Traits\TypeVolumeHoraireServiceAwareTrait;
use Plafond\Entity\Db\Plafond;
use Application\Entity\Db\TypeVolumeHoraire;
use Plafond\Entity\Db\PlafondApplication;
use Plafond\Entity\Db\PlafondEtat;
use Plafond\Entity\Db\PlafondPerimetre;
use Plafond\Entity\Db\PlafondReferentiel;
use Plafond\Entity\Db\PlafondStatut;
use Plafond\Entity\Db\PlafondStructure;
use Plafond\Entity\PlafondControle;
use Plafond\Interfaces\PlafondConfigInterface;
use UnicaenTbl\Service\Traits\QueryGeneratorServiceAwareTrait;
use UnicaenTbl\Service\Traits\TableauBordServiceAwareTrait;

/**
 * Description of PlafondService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method Plafond get($id)
 * @method Plafond[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method Plafond newEntity()
 *
 */
class PlafondService extends AbstractEntityService
{
    use TableauBordServiceAwareTrait;
    use QueryGeneratorServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;

    /**
     * @var PlafondPerimetre[]
     */
    protected array $perimetres;

    /**
     * @var PlafondEtat[]
     */
    protected array $etats;



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass(): string
    {
        return Plafond::class;
    }



    /**
     * @param Structure|Intervenant|ElementPedagogique|VolumeHoraire|FonctionReferentiel $entity
     * @param TypeVolumeHoraire                                                          $typeVolumeHoraire
     * @param bool                                                                       $pourBlocage
     *
     * @return PlafondControle[]
     */
    public function controle(TypeVolumeHoraire $typeVolumeHoraire, $entity, bool $pourBlocage = false): array
    {
        $sqls = [];
        if ($entity instanceof Structure) {
            $sqls[] = $this->makeControleQuery($typeVolumeHoraire, $entity, null, $pourBlocage, false, true);
        } elseif ($entity instanceof Intervenant) {
            $sqls[] = $this->makeControleQuery($typeVolumeHoraire, $entity, null, $pourBlocage);
            $sqls[] = $this->makeControleQuery($typeVolumeHoraire, $entity, PlafondPerimetre::STRUCTURE, $pourBlocage);
            if (!$pourBlocage) {
                $sqls[] = $this->makeControleQuery($typeVolumeHoraire, $entity, PlafondPerimetre::ELEMENT, $pourBlocage, false, true);
                $sqls[] = $this->makeControleQuery($typeVolumeHoraire, $entity, PlafondPerimetre::REFERENTIEL, $pourBlocage, false, true);
                $sqls[] = $this->makeControleQuery($typeVolumeHoraire, $entity, PlafondPerimetre::VOLUME_HORAIRE, $pourBlocage, false, true);
            }
        } elseif ($entity instanceof ElementPedagogique) {
            $sqls[] = $this->makeControleQuery($typeVolumeHoraire, $entity, null, $pourBlocage, false, true);
        } elseif ($entity instanceof VolumeHoraire) {
            $sqls[] = $this->makeControleQuery($typeVolumeHoraire, $entity, null, $pourBlocage);
        } elseif ($entity instanceof FonctionReferentiel) {
            $sqls[] = $this->makeControleQuery($typeVolumeHoraire, $entity, null, $pourBlocage, false, true);
        } else {
            throw new \Exception('Entité non gérée pour les contrôles de plafonds');
        }

        $sql          = implode("\n\nUNION ALL\n\n", $sqls);
        $res          = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql);
        $depassements = [];
        foreach ($res as $r) {
            $depassements[] = PlafondControle::fromArray($r);
        }

        return $depassements;
    }



    /**
     * @param TypeVolumeHoraire                                                          $typeVolumeHoraire
     * @param Structure|Intervenant|ElementPedagogique|VolumeHoraire|FonctionReferentiel $entity
     *
     * @return PlafondControle[]
     */
    public function derogations(TypeVolumeHoraire $typeVolumeHoraire, Intervenant $intervenant): array
    {
        $sqls = [];

        $sqls[] = $this->makeControleQuery($typeVolumeHoraire, $intervenant, null, false, false, false);
        //$sqls[] = $this->makeControleQuery($typeVolumeHoraire, $intervenant, PlafondPerimetre::STRUCTURE, false, false, false);
        //$sqls[] = $this->makeControleQuery($typeVolumeHoraire, $intervenant, PlafondPerimetre::ELEMENT, false, false, false);
        //$sqls[] = $this->makeControleQuery($typeVolumeHoraire, $intervenant, PlafondPerimetre::REFERENTIEL, false, false, false);
        //$sqls[] = $this->makeControleQuery($typeVolumeHoraire, $intervenant, PlafondPerimetre::VOLUME_HORAIRE, false, false, false);

        $sql = implode("\n\nUNION ALL\n\n", $sqls);

        $res          = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql);
        $depassements = [];
        foreach ($res as $r) {
            $depassements[] = PlafondControle::fromArray($r);
        }

        return $depassements;
    }



    /**
     * @param TypeVolumeHoraire                                                          $typeVolumeHoraire
     * @param Structure|Intervenant|ElementPedagogique|VolumeHoraire|FonctionReferentiel $entity
     * @param string|PlafondPerimetre|null                                               $perimetre
     * @param bool                                                                       $pourBlocage
     * @param bool                                                                       $bloquantUniquement
     * @param bool                                                                       $depassementsUniquement
     *
     * @return string
     * @throws \Exception
     */
    protected function makeControleQuery(
        TypeVolumeHoraire $typeVolumeHoraire,
        $entity,
        $perimetre = null,
        bool $pourBlocage = false,
        bool $bloquantUniquement = false,
        bool $depassementsUniquement = false): string
    {
        $filters = [];

        if ($perimetre instanceof PlafondPerimetre) $perimetre = $perimetre->getCode();

        if ($entity instanceof Structure) {
            if (!$perimetre) $perimetre = PlafondPerimetre::STRUCTURE;
            $filters['pd.STRUCTURE_ID'] = (int)$entity->getId();
            $filters['pd.ANNEE_ID']     = (int)$this->getServiceContext()->getAnnee()->getId();
        } elseif ($entity instanceof Intervenant) {
            if (!$perimetre) $perimetre = PlafondPerimetre::INTERVENANT;
            $filters['pd.INTERVENANT_ID'] = (int)$entity->getId();
        } elseif ($entity instanceof ElementPedagogique) {
            if (!$perimetre) $perimetre = PlafondPerimetre::ELEMENT;
            $filters['pd.ELEMENT_PEDAGOGIQUE_ID'] = (int)$entity->getId();
        } elseif ($entity instanceof VolumeHoraire) {
            if (!$perimetre) $perimetre = PlafondPerimetre::VOLUME_HORAIRE;
            $filters['pd.ELEMENT_PEDAGOGIQUE_ID'] = (int)$entity->getService()->getElementPedagogique()->getId();
            $filters['pd.TYPE_INTERVENTION_ID']   = (int)$entity->getTypeIntervention()->getId();
        } elseif ($entity instanceof FonctionReferentiel) {
            if (!$perimetre) $perimetre = PlafondPerimetre::REFERENTIEL;
            $filters['pd.FONCTION_REFERENTIEL_ID'] = (int)$entity->getId();
            $filters['pd.ANNEE_ID']                = (int)$this->getServiceContext()->getAnnee()->getId();
        } else {
            throw new \Exception('Entité non reconnue pour la création de la requête de contrôle');
        }

        if ($pourBlocage) {
            $bloquantUniquement = true;
        }
        if ($bloquantUniquement) {
            $depassementsUniquement = true;
            $filters['pe.bloquant'] = 1;
        }
        if ($depassementsUniquement) {
            $filters['pd.depassement'] = 1;
        }

        /*
        Pour un contrôle en vue d'un blocage, il faut avoir recours à la vue, car les TBL n'ont pas encore été mis à jour
        Pour un listage de plafonds normal, on utilise la table pour de raisons de perfs
        */

        $sql = "
            SELECT
              p.id           id,
              p.numero       numero,
              p.libelle      libelle,
              p.libelle      message,
              pp.code        perimetre,
              pe.code        etat,
              pe.bloquant    bloquant,
              pd.depassement depassement,
              pd.heures      heures,
              pd.plafond     plafond,
              pd.derogation  derogation
            FROM 
              " . ($pourBlocage ? 'v_' : '') . "tbl_plafond_" . $perimetre . " pd
              JOIN plafond               p ON p.id = pd.plafond_id
              JOIN plafond_etat         pe ON pe.id = pd.plafond_etat_id
              JOIN plafond_perimetre    pp ON pp.id = p.plafond_perimetre_id
            WHERE
                  pd.type_volume_horaire_id = " . ((int)$typeVolumeHoraire->getId());

        foreach ($filters as $v => $c) {
            $sql .= "\n  AND $v = $c";
        }

        return $sql;
    }



    public function construireVues()
    {
        $colsPos = require getcwd() . '/data/ddl_columns_pos.php';

        $dql = "
        SELECT
          pp, p, pa
        FROM
          Plafond\Entity\Db\PlafondPerimetre pp
          LEFT JOIN pp.plafond p
          LEFT JOIN p.plafondApplication pa
        ";

        $q = $this->getEntityManager()->createQuery($dql);


        /** @var $perimetres PlafondPerimetre[] */
        $perimetres = $q->execute();

        $tvhPrevuId       = $this->getServiceTypeVolumeHoraire()->getPrevu()->getId();
        $tvhRealiseId     = $this->getServiceTypeVolumeHoraire()->getRealise()->getId();
        $configTablesJoin = [
            "structure"      => "plafond_structure ps ON ps.plafond_id = p.plafond_id AND ps.structure_id = p.structure_id AND ps.annee_id = i.annee_id AND ps.histo_destruction IS NULL",
            "intervenant"    => "plafond_statut ps ON ps.plafond_id = p.plafond_id AND ps.statut_intervenant_id = i.statut_id AND ps.annee_id = i.annee_id AND ps.histo_destruction IS NULL",
            "element"        => "plafond_statut ps ON 1 = 0",
            "volume_horaire" => "plafond_statut ps ON 1 = 0",
            "referentiel"    => "plafond_referentiel ps ON ps.plafond_id = p.plafond_id AND ps.fonction_referentiel_id = p.fonction_referentiel_id AND ps.annee_id = i.annee_id AND ps.histo_destruction IS NULL",
        ];

        foreach ($perimetres as $perimetre) {
            $cols = $colsPos['TBL_PLAFOND_' . strtoupper($perimetre->getCode())];
            $cols = array_diff($cols, ['ID', 'DEPASSEMENT']);

            $view = "CREATE OR REPLACE FORCE VIEW V_TBL_PLAFOND_" . strtoupper($perimetre->getCode()) . ' AS';
            $view .= "\nSELECT";
            foreach ($cols as $col) {
                if ($col != 'PLAFOND_ETAT_ID' && $col != 'DEROGATION' && $col != 'DEPASSEMENT') {
                    $view .= "\n  p.$col,";
                }
            }
            $view     .= "\n  CASE";
            $view     .= "\n    WHEN p.type_volume_horaire_id = $tvhPrevuId THEN COALESCE(ps.plafond_etat_prevu_id,pa.plafond_etat_prevu_id)";
            $view     .= "\n    WHEN p.type_volume_horaire_id = $tvhRealiseId THEN COALESCE(ps.plafond_etat_realise_id, pa.plafond_etat_realise_id)";
            $view     .= "\n  END plafond_etat_id,";
            $view     .= "\n  COALESCE(pd.heures, 0) derogation,";
            $view     .= "\n  CASE WHEN p.heures > p.plafond + COALESCE(pd.heures, 0) + 0.05 THEN 1 ELSE 0 END depassement";
            $view     .= "\nFROM\n  (";
            $plafonds = $perimetre->getPlafond();
            $first    = true;
            $hasQuery = false;
            foreach ($plafonds as $plafond) {
                if ($this->testRequete($plafond)) {

                    $hasQuery = true;
                    if (!$first) $view .= "\n\n    UNION ALL\n";
                    $view  .= "\n  SELECT " . $plafond->getId() . " PLAFOND_ID, p.* FROM (\n    ";
                    $view  .= str_replace("\n", "\n      ", $plafond->getRequete());
                    $view  .= "\n    ) p";
                    $first = false;
                }
            }
            if (!$hasQuery) {
                $view .= "\n    SELECT ";
                foreach ($cols as $col) {
                    $view .= "NULL $col,";
                }
                $view = substr($view, 0, -1);
                $view .= " FROM dual WHERE 0 = 1";
            }
            $view .= "\n  ) p";
            $view .= "\n  JOIN intervenant i ON i.id = p.intervenant_id";
            $view .= "\n  LEFT JOIN plafond_application pa ON pa.plafond_id = p.plafond_id AND p.annee_id = pa.annee_id";
            $view .= "\n  LEFT JOIN " . $configTablesJoin[$perimetre->getCode()];
            $view .= "\n  LEFT JOIN plafond_derogation pd ON pd.plafond_id = p.plafond_id AND pd.intervenant_id = p.intervenant_id AND pd.histo_destruction IS NULL";
            $view .= "\nWHERE\n";
            $view .= "  CASE\n";
            $view .= "    WHEN p.type_volume_horaire_id = 1 THEN COALESCE(ps.plafond_etat_prevu_id,pa.plafond_etat_prevu_id)\n";
            $view .= "    WHEN p.type_volume_horaire_id = 2 THEN COALESCE(ps.plafond_etat_realise_id, pa.plafond_etat_realise_id)\n";
            $view .= "  END IS NOT NULL";
            foreach ($cols as $col) {
                if ($col != 'PLAFOND' && $col != 'HEURES' && $col != 'DEROGATION') {
                    $view .= "\n  /*@$col=p.$col*/";
                }
            }
            $this->getEntityManager()->getConnection()->executeStatement($view);
        }
    }



    public function testRequete(Plafond $plafond): bool
    {
        $colsPos = require getcwd() . '/data/ddl_columns_pos.php';
        $cols    = $colsPos['TBL_PLAFOND_' . strtoupper($plafond->getPlafondPerimetre()->getCode())];
        $cols    = array_diff($cols, ['ID', 'PLAFOND_ID', 'PLAFOND_ETAT_ID', 'DEROGATION', 'DEPASSEMENT']);

        try {
            $sql = 'SELECT * FROM (' . $plafond->getRequete() . ') r WHERE ROWNUM = 1';
            $res = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql);

            foreach ($cols as $col) {
                if (!isset($res[0][$col])) {
                    return false;
                }
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }



    /**
     * @param array           $tableauxBords
     * @param Intervenant|int $intervenant
     */
    public function calculerDepuisEntite($entity)
    {
        if ($entity instanceof Structure) {
            $this->calculer(PlafondPerimetre::STRUCTURE, 'STRUCTURE_ID', $entity->getId());
        }

        if ($entity instanceof Intervenant) {
            $this->calculer(PlafondPerimetre::INTERVENANT, 'INTERVENANT_ID', $entity->getId());
            if ($entity->getStructure()) {
                $this->calculerDepuisEntite($entity->getStructure());
            }
        }

        if ($entity instanceof ElementPedagogique) {
            $this->calculer(PlafondPerimetre::ELEMENT, 'ELEMENT_PEDAGOGIQUE_ID', $entity->getId());
            if ($entity->getStructure()) {
                $this->calculerDepuisEntite($entity->getStructure());
            }
        }

        if ($entity instanceof FonctionReferentiel) {
            $this->calculer(PlafondPerimetre::REFERENTIEL, 'FONCTION_REFERENTIEL_ID', $entity->getId());
            if ($entity->getStructure()) {
                $this->calculerDepuisEntite($entity->getStructure());
            }
        }

        if ($entity instanceof Service) {
            if ($entity->getElementPedagogique()) {
                $this->calculerDepuisEntite($entity->getElementPedagogique());
            }
            if ($entity->getIntervenant()) {
                $this->calculerDepuisEntite($entity->getIntervenant());
            }
            if ($entity->getStructure()) {
                $this->calculerDepuisEntite($entity->getStructure());
            }
        }

        if ($entity instanceof ServiceReferentiel) {
            if ($entity->getFonction()) {
                $this->calculerDepuisEntite($entity->getFonction());
            }
            if ($entity->getIntervenant()) {
                $this->calculerDepuisEntite($entity->getIntervenant());
            }
            if ($entity->getStructure()) {
                $this->calculerDepuisEntite($entity->getStructure());
            }
        }

        if ($entity instanceof VolumeHoraire) {
            if ($entity->getService()) {
                if ($entity->getService()->getElementPedagogique()) {
                    $this->calculer('volume_horaire', 'ELEMENT_PEDAGOGIQUE_ID', $entity->getService()->getElementPedagogique());
                }

                $this->calculerDepuisEntite($entity->getService());
            }
        }
    }



    public function calculerPourIntervenant(Intervenant $intervenant): self
    {
        $perimetres = [
            'structure', 'intervenant', 'element', 'volume_horaire', 'referentiel',
        ];
        foreach ($perimetres as $perimetre) {
            $this->getServiceTableauBord()->calculer('plafond_' . $perimetre, 'INTERVENANT_ID', $intervenant->getId());
        }

        return $this;
    }



    /**
     * @param string|PlafondPerimetre $perimetre
     * @param string|null             $param
     * @param string|null             $value
     */
    public function calculer($perimetre, ?string $param = null, ?string $value = null)
    {
        if ($perimetre instanceof PlafondPerimetre) $perimetre = $perimetre->getCode();
        $tblName = 'plafond_' . $perimetre;
        $this->getServiceTableauBord()->calculer($tblName, $param, $value);
    }



    public function construire()
    {
        $this->construireVues();
        $this->getServiceQueryGenerator()->updateProcedures();
    }



    /**
     * @return PlafondPerimetre[]
     */
    public function getPerimetres(): array
    {
        if (empty($this->perimetres)) {
            $qb = $this->getEntityManager()->createQueryBuilder();
            $qb->select('p');
            $qb->from(PlafondPerimetre::class, 'p', 'p.id');
            $qb->addOrderBy("p.ordre");
            $this->perimetres = $qb->getQuery()->execute();
        }

        return $this->perimetres;
    }



    /**
     * @param string $code
     *
     * @return PlafondPerimetre|null
     */
    public function getPerimetre(string $code): ?PlafondPerimetre
    {
        $perimetres = $this->getPerimetres();
        foreach ($perimetres as $perimetre) {
            if ($perimetre->getCode() == $code) {
                return $perimetre;
            }
        }

        return null;
    }



    /**
     * @return PlafondEtat[]
     */
    public function getEtats(): array
    {
        if (empty($this->etats)) {
            $qb = $this->getEntityManager()->createQueryBuilder();
            $qb->select('pe');
            $qb->from(PlafondEtat::class, 'pe', 'pe.code');
            $qb->addOrderBy("pe.id");
            $this->etats = $qb->getQuery()->execute();
        }

        return $this->etats;
    }



    protected function entityToConfigClass($entity = null): string
    {
        if (empty($entity)) {
            return PlafondApplication::class;
        }
        if ($entity instanceof Structure) {
            return PlafondStructure::class;
        }
        if ($entity instanceof StatutIntervenant) {
            return PlafondStatut::class;
        }
        if ($entity instanceof FonctionReferentiel) {
            return PlafondReferentiel::class;
        }
        throw new \Exception("L'entité fournie ne permet pas e récupérer une configuration de plafond");
    }



    /**
     * @param Plafond|int                                          $plafond
     * @param null|Structure|FonctionReferentiel|StatutIntervenant $entity
     *
     * @return PlafondConfigInterface
     */
    public function getPlafondConfig($plafond, $entity = null): PlafondConfigInterface
    {
        $pid = (int)($plafond instanceof Plafond ? $plafond->getId() : $plafond);
        if ($pid === 0) {
            throw new \Exception("Le plafond transmis n'est pas correct ou bien il n'a pas encore d'ID attribué");
        }

        $pf = $this->getterPlafondConfig($pid, $entity);
        if (!isset($pf[$pid])) {
            throw new Exception("Le plafond n'a pas été trouvé");
        }

        return $pf[$pid];
    }



    /**
     * @return PlafondConfigInterface[]
     */
    public function getPlafondsConfig($entity = null): array
    {
        return $this->getterPlafondConfig(0, $entity);
    }



    private function getterPlafondConfig(int $plafondId, $entity = null): array
    {
        $joins = [
            PlafondApplication::class => null,
            PlafondStructure::class   => 'LEFT JOIN p.plafondStructure pc WITH pc.annee = :annee AND pc.histoDestruction IS NULL AND pc.structure = :entity',
            PlafondStatut::class      => 'LEFT JOIN p.plafondStatut pc WITH pc.annee = :annee AND pc.histoDestruction IS NULL AND pc.statutIntervenant = :entity',
            PlafondReferentiel::class => 'LEFT JOIN p.plafondReferentiel pc WITH pc.annee = :annee AND pc.histoDestruction IS NULL AND pc.fonctionReferentiel = :entity',
        ];

        $annee  = $this->getServiceContext()->getAnnee();
        $class  = $this->entityToConfigClass($entity);
        $getter = 'get' . substr($class, strrpos($class, '\\') + 1);
        $where  = [];

        $params = ['annee' => $annee];
        if ($entity) {
            $params['entity'] = $entity->getId();
        }

        if ($plafondId > 0) {
            $where[]       = "p.id = :pid";
            $params['pid'] = $plafondId;
        }

        if ($perimetre = $class::getPerimetreCode()) {
            $where[]             = "prm.code = :perimetre";
            $params['perimetre'] = $perimetre;
        }

        $dql = "
        SELECT
          p, prm, papp" . ($joins[$class] ? ', pc' : '') . "
        FROM
          " . Plafond::class . " p
          JOIN p.plafondPerimetre prm
          LEFT JOIN p.plafondApplication papp WITH papp.annee = :annee AND papp.histoDestruction IS NULL
          " . $joins[$class] . "
        " . (empty($where) ? '' : ('WHERE ' . implode(' AND ', $where))) . "
        ORDER BY
            prm.libelle, p.libelle
        ";

        /** @var Plafond[] $plafonds */
        $plafonds = $this->getEntityManager()->createQuery($dql)->setParameters($params)->getResult();
        $pcs      = [];
        foreach ($plafonds as $plafond) {
            $configCount = $plafond->$getter()->count();
            switch ($configCount) {
                case 0:
                    /* @var $papp PlafondApplication */
                    $papp = $plafond->getPlafondApplication()->first();
                    $pc   = new $class;
                    $pc->setPlafond($plafond);
                    if ($entity) $pc->setEntity($entity);
                    $pc->setEtatPrevu($papp ? $papp->getEtatPrevu() : $this->getEtat(PlafondEtat::DESACTIVE));
                    $pc->setEtatRealise($papp ? $papp->getEtatRealise() : $this->getEtat(PlafondEtat::DESACTIVE));
                    $pc->setHeures($papp ? $papp->getHeures() : 0);
                    $pcs[$plafond->getId()] = $pc;
                break;
                case 1:
                    $pcs[$plafond->getId()] = $plafond->$getter()->first();
                break;
                default:
                    throw new \Exception('Erreur : trop de paramètres (' . $configCount . ') de configuration retournées pour le plafond numéro ' . $plafond->getNumero());
            }
        }

        return $pcs;
    }



    /**
     * @param PlafondConfigInterface $plafondConfig
     *
     * @return PlafondConfigInterface
     */
    public function saveConfig(PlafondConfigInterface $plafondConfig): PlafondConfigInterface
    {
        if (empty($plafondConfig->getEtatPrevu())) {
            $plafondConfig->setEtatPrevu($this->getEtat(PlafondEtat::DESACTIVE));
        }
        if (empty($plafondConfig->getEtatRealise())) {
            $plafondConfig->setEtatRealise($this->getEtat(PlafondEtat::DESACTIVE));
        }

        $this->getEntityManager()->persist($plafondConfig);
        $this->getEntityManager()->flush($plafondConfig);

        return $plafondConfig;
    }



    /**
     * @param string $code
     *
     * @return PlafondEtat|null
     */
    public function getEtat(string $code): ?PlafondEtat
    {
        $etats = $this->getEtats();
        if (isset($etats[$code])) {
            return $etats[$code];
        } else {
            return null;
        }
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(): string
    {
        return 'plafond';
    }
}