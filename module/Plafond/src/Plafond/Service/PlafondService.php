<?php

namespace Plafond\Service;

use Application\Entity\Db\Intervenant;
use Application\Service\AbstractEntityService;
use Plafond\Entity\Db\Plafond;
use Application\Entity\Db\TypeVolumeHoraire;
use Plafond\Entity\Db\PlafondPerimetre;
use Plafond\Entity\PlafondDepassement;

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
     * @return PlafondDepassement[]
     */
    public function controle(Intervenant $intervenant, TypeVolumeHoraire $typeVolumeHoraire): array
    {
        $sql          = file_get_contents('data/Query/plafond.sql');
        $sql          = str_replace('/*i.id*/', 'AND intervenant_id = ' . $intervenant->getId(), $sql) . ' AND tvh.id = ' . $typeVolumeHoraire->getId();
        $sql          = preg_replace('/--(.*)\n/Uis', "\n", $sql);
        $res          = $this->getEntityManager()->getConnection()->fetchAll($sql);
        $depassements = [];
        foreach ($res as $r) {
            $depassements[] = $this->depassementFromArray($r);
        }

        return $depassements;
    }



    /**
     * @param array $a
     *
     * @return PlafondDepassement
     */
    private function depassementFromArray(array $a): PlafondDepassement
    {
        $depassement = new PlafondDepassement();
        $depassement->setPlafondLibelle($a['PLAFOND_LIBELLE']);
        if ($a['PLAFOND_ETAT_CODE'] == 'bloquant') $depassement->setBloquant(true);
        $depassement->setPlafond($a['PLAFOND']);
        $depassement->setHeures($a['HEURES']);

        return $depassement;
    }



    public function construireVues()
    {
        $pcols = [
            'composante'     => ['ANNEE_ID', 'STRUCTURE_ID', 'TYPE_VOLUME_HORAIRE_ID', 'PLAFOND', 'HEURES'],
            'intervenant'    => ['ANNEE_ID', 'INTERVENANT_ID', 'TYPE_VOLUME_HORAIRE_ID', 'PLAFOND', 'HEURES'],
            'element'        => ['ANNEE_ID', 'ELEMENT_PEDAGOGIQUE_ID', 'TYPE_VOLUME_HORAIRE_ID', 'PLAFOND', 'HEURES'],
            'volume_horaire' => ['ANNEE_ID', 'ELEMENT_PEDAGOGIQUE_ID', 'TYPE_INTERVENTION_ID', 'TYPE_VOLUME_HORAIRE_ID', 'PLAFOND', 'HEURES'],
            'referentiel'    => ['ANNEE_ID', 'FONCTION_REFERENTIEL_ID', 'TYPE_VOLUME_HORAIRE_ID', 'PLAFOND', 'HEURES'],
        ];

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
            $cols = $pcols[$perimetre->getCode()];
            $view = "CREATE OR REPLACE FORCE VIEW V_TBL_PLAFOND_" . strtoupper($perimetre->getCode()) . ' AS';
            $view .= "\nSELECT";
            foreach ($cols as $col) {
                $view .= "\n  p.$col,";
            }
            $view     .= "\n  p.DEROGATION,";
            $view     .= "\n  p.PLAFOND_ID\nFROM\n(";
            $plafonds = $perimetre->getPlafond();
            $first    = true;
            $hasQuery = false;
            foreach ($plafonds as $plafond) {
                if ($plafond->getRequete()) {
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
                $view .= " 0 DEROGATION, NULL PLAFOND_ID FROM dual WHERE 0 = 1";
            }
            $view .= "\n) p";
            $view .= "\nJOIN plafond_application pa ON pa.plafond_id = p.plafond_id AND pa.type_volume_horaire_id = p.type_volume_horaire_id AND p.annee_id BETWEEN COALESCE(pa.annee_debut_id,p.annee_id) AND COALESCE(pa.annee_fin_id,p.annee_id)";
            $view .= "\nWHERE\n  1=1";
            $view .= "\n  /*@PLAFOND_ID=p.PLAFOND_ID*/";
            foreach ($cols as $col) {
                if ($col != 'PLAFOND' && $col != 'HEURES') {
                    $view .= "\n  /*@$col=p.$col*/";
                }
            }
            $this->getEntityManager()->getConnection()->exec($view);
        }
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