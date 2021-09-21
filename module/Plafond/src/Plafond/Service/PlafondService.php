<?php

namespace Plafond\Service;

use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\FonctionReferentiel;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Service;
use Application\Entity\Db\ServiceReferentiel;
use Application\Entity\Db\Structure;
use Application\Entity\Db\VolumeHoraire;
use Application\Service\AbstractEntityService;
use Plafond\Entity\Db\Plafond;
use Application\Entity\Db\TypeVolumeHoraire;
use Plafond\Entity\Db\PlafondEtat;
use Plafond\Entity\Db\PlafondPerimetre;
use Plafond\Entity\PlafondControle;
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

        foreach ($perimetres as $perimetre) {
            $cols = $colsPos['TBL_PLAFOND_' . strtoupper($perimetre->getCode())];
            $cols = array_diff($cols, ['ID', 'DEPASSEMENT']);

            $view = "CREATE OR REPLACE FORCE VIEW V_TBL_PLAFOND_" . strtoupper($perimetre->getCode()) . ' AS';
            $view .= "\nSELECT";
            foreach ($cols as $col) {
                $alias = 'p';
                if ($col == 'PLAFOND_ETAT_ID') $alias = 'pa';
                $view .= "\n  $alias.$col,";
            }
            $view     .= "\n  CASE WHEN p.heures > p.plafond + p.derogation + 0.05 THEN 1 ELSE 0 END depassement";
            $view     .= "\nFROM\n(";
            $plafonds = $perimetre->getPlafond();
            $first    = true;
            $hasQuery = false;
            foreach ($plafonds as $plafond) {
                if ($this->testRequete($plafond)) {

                    $hasQuery = true;
                    if (!$first) $view .= "\n\n  UNION ALL\n";
                    $view  .= "\n  SELECT " . $plafond->getId() . " PLAFOND_ID, 0 DEROGATION, p.* FROM (\n    ";
                    $view  .= str_replace("\n", "\n    ", $plafond->getRequete());
                    $view  .= "\n  ) p";
                    $first = false;
                }
            }
            if (!$hasQuery) {
                $view .= "\n  SELECT ";
                foreach ($cols as $col) {
                    $view .= "NULL $col,";
                }
                $view = substr($view, 0, -1);
                $view .= " FROM dual WHERE 0 = 1";
            }
            $view .= "\n) p";
            $view .= "\nJOIN plafond_application pa ON pa.plafond_id = p.plafond_id AND pa.type_volume_horaire_id = p.type_volume_horaire_id AND p.annee_id BETWEEN COALESCE(pa.annee_debut_id,p.annee_id) AND COALESCE(pa.annee_fin_id,p.annee_id)";
            $view .= "\nWHERE\n  1=1";
            foreach ($cols as $col) {
                if ($col != 'PLAFOND' && $col != 'HEURES' && $col != 'DEROGATION') {
                    $view .= "\n  /*@$col=p.$col*/";
                }
            }
            $this->getEntityManager()->getConnection()->exec($view);
        }
    }



    public function testRequete(Plafond $plafond): bool
    {
        $colsPos = require getcwd() . '/data/ddl_columns_pos.php';
        $cols    = $colsPos['TBL_PLAFOND_' . strtoupper($plafond->getPlafondPerimetre()->getCode())];
        $cols    = array_diff($cols, ['ID', 'PLAFOND_ID', 'PLAFOND_ETAT_ID', 'DEROGATION', 'DEPASSEMENT']);

        try {
            $sql = 'SELECT * FROM (' . $plafond->getRequete() . ') r WHERE ROWNUM = 1';
            $res = $this->getEntityManager()->getConnection()->fetchAll($sql);

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
        $parimetres = $this->getPerimetres();
        if (isset($parimetres[$code])) {
            return $parimetres[$code];
        } else {
            return null;
        }
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