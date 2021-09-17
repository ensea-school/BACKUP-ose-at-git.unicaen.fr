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
     * @param Intervenant       $intervenant
     * @param TypeVolumeHoraire $typeVolumeHoraire
     *
     * @return PlafondControle[]
     */
    public function controle(Intervenant $intervenant, TypeVolumeHoraire $typeVolumeHoraire, bool $depassementsUniquement = true): array
    {
        $filtreDepassement = $depassementsUniquement ? "AND pd.heures > pd.plafond + pd.derogation + 0.05" : "";

        $sql = "
            SELECT
              pm.code perimetre,
              p.code plafond_code,
              p.libelle plafond_libelle,
              pd.heures heures,
              pd.plafond plafond,
              pd.derogation derogation
            FROM 
              v_tbl_plafond_intervenant pd
              JOIN plafond p ON p.id = pd.plafond_id
              JOIN plafond_perimetre pm ON pm.id = p.plafond_perimetre_id
            WHERE 
              pd.intervenant_id = :intervenant
              AND pd.type_volume_horaire_id = :typeVolumeHoraire
              $filtreDepassement
        ";

        $params = [
            'intervenant'       => $intervenant->getId(),
            'typeVolumeHoraire' => $typeVolumeHoraire->getId(),
        ];

        $res          = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql, $params);
        $depassements = [];
        foreach ($res as $r) {
            $depassements[] = $this->depassementFromArray($r);
        }

        return $depassements;
    }



    /**
     * @param array $a
     *
     * @return PlafondControle
     */
    private function depassementFromArray(array $a): PlafondControle
    {
        $depassement = new PlafondControle();
        $depassement->setPlafondLibelle($a['PLAFOND_LIBELLE']);
        if ($a['PLAFOND_ETAT_CODE'] == 'bloquant') $depassement->setBloquant(true);
        $depassement->setPlafond($a['PLAFOND']);
        $depassement->setHeures($a['HEURES']);

        return $depassement;
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
            $cols = array_diff($cols, ['ID']);

            $view = "CREATE OR REPLACE FORCE VIEW V_TBL_PLAFOND_" . strtoupper($perimetre->getCode()) . ' AS';
            $view .= "\nSELECT";
            foreach ($cols as $col) {
                $view .= "\n  p.$col,";
            }
            $view     = substr($view, 0, -1);
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
        $cols    = array_diff($cols, ['ID', 'PLAFOND_ID', 'PLAFOND_ETAT_ID', 'DEROGATION']);

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
            $this->calculer('structure', 'STRUCTURE_ID', $entity->getId());
        }

        if ($entity instanceof Intervenant) {
            $this->calculer('intervenant', 'INTERVENANT_ID', $entity->getId());
            if ($entity->getStructure()) {
                $this->calculerDepuisEntite($entity->getStructure());
            }
        }

        if ($entity instanceof ElementPedagogique) {
            $this->calculer('element', 'ELEMENT_PEDAGOGIQUE_ID', $entity->getId());
            if ($entity->getStructure()) {
                $this->calculerDepuisEntite($entity->getStructure());
            }
        }

        if ($entity instanceof FonctionReferentiel) {
            $this->calculer('referentiel', 'FONCTION_REFERENTIEL_ID', $entity->getId());
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



    public function construireTableauxBord()
    {
        $this->getServiceQueryGenerator()->updateProcedures();
    }



    public function construire()
    {
        $this->construireVues();
        $this->construireTableauxBord();
    }



    /**
     * @return PlafondPerimetre[]
     */
    public function getPerimetres(): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p');
        $qb->from(PlafondPerimetre::class, 'p', 'p.id');
        $qb->addOrderBy("p.ordre");
        $perimetres = $qb->getQuery()->execute();

        return $perimetres;
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