<?php

namespace Plafond\Service;

use Application\Service\AbstractEntityService;
use Enseignement\Entity\Db\Service;
use Enseignement\Entity\Db\VolumeHoraire;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Entity\Db\Statut;
use Lieu\Entity\Db\Structure;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\TypeMission;
use Mission\Entity\Db\VolumeHoraireMission;
use OffreFormation\Entity\Db\ElementPedagogique;
use Plafond\Entity\Db\Plafond;
use Plafond\Entity\Db\PlafondEtat;
use Plafond\Entity\Db\PlafondMission;
use Plafond\Entity\Db\PlafondPerimetre;
use Plafond\Entity\Db\PlafondReferentiel;
use Plafond\Entity\Db\PlafondStatut;
use Plafond\Entity\Db\PlafondStructure;
use Plafond\Entity\PlafondControle;
use Plafond\Entity\PlafondQueryParams;
use Plafond\Interfaces\PlafondConfigInterface;
use Plafond\Interfaces\PlafondDataInterface;
use Plafond\Interfaces\PlafondPerimetreInterface;
use Referentiel\Entity\Db\FonctionReferentiel;
use Referentiel\Entity\Db\ServiceReferentiel;
use Referentiel\Entity\Db\VolumeHoraireReferentiel;
use RuntimeException;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use Unicaen\BddAdmin\BddAwareTrait;
use UnicaenTbl\Service\TableauBordServiceAwareTrait;

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
    use TypeVolumeHoraireServiceAwareTrait;
    use BddAwareTrait;

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
     * @param PlafondDataInterface $entity
     * @param TypeVolumeHoraire $typeVolumeHoraire
     *
     * @return PlafondControle[]
     */
    public function data(PlafondDataInterface $entity, TypeVolumeHoraire $typeVolumeHoraire): array
    {
        $sqls = $this->dataMakeQueries($entity, $typeVolumeHoraire);

        $sql = implode("\n\nUNION ALL\n\n", $sqls);
        $res = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql);
        $depassements = [];
        foreach ($res as $r) {
            $depassements[] = PlafondControle::fromArray($r);
        }

        return $depassements;
    }



    protected function dataMakeQueries(PlafondDataInterface $entity, TypeVolumeHoraire $typeVolumeHoraire): array
    {
        $pqr = new PlafondQueryParams();
        $pqr->typeVolumeHoraire = $typeVolumeHoraire;

        $sPqr = $pqr->sub();
        $sPqr->bloquantOuDepassementUniquement = true;


        if ($entity instanceof Intervenant) {
            $pqr->add($entity);
            $sPqr->add($entity->getStructure());

            foreach ($entity->getService() as $service) {
                if ($ep = $service->getElementPedagogique()) {
                    $sPqr->add($ep);
                    $sPqr->add($ep->getStructure());
                }
            }

            foreach ($entity->getServiceReferentiel() as $referentiel) {
                $sPqr->add($referentiel->getStructure());
                $sPqr->add($referentiel);
            }

            foreach ($entity->getMissions() as $mission) {
                $sPqr->add($mission->getStructure());
                $sPqr->add($mission->getTypeMission());
            }
        }

        if ($entity instanceof Service) {
            $pqr->add($entity->getElementPedagogique());
        }

        if ($entity instanceof VolumeHoraire) {
            $pqr->add($entity->getService());
        }

        if ($entity instanceof ServiceReferentiel) {
            $pqr->add($entity->getFonctionReferentiel());
        }

        if ($entity instanceof VolumeHoraireReferentiel) {
            $pqr->add($entity->getServiceReferentiel());
        }

        if ($entity instanceof Mission) {
            $pqr->add($entity->getTypeMission());
        }

        if ($entity instanceof VolumeHoraireMission) {
            $pqr->add($entity->getMission());
        }

        $sqls = [];

        $this->makeQuery($pqr, $sqls);
        $this->makeQuery($sPqr, $sqls);

        return $sqls;
    }



    /**
     * @param PlafondPerimetreInterface $entity
     * @param TypeVolumeHoraire $typeVolumeHoraire
     * @param bool $pourBlocage
     *
     * @return PlafondControle[]
     */
    public function controle(PlafondDataInterface $entity, TypeVolumeHoraire $typeVolumeHoraire): array
    {
        $pqr = new PlafondQueryParams();
        $pqr->typeVolumeHoraire = $typeVolumeHoraire;
        $pqr->useView = true;
        $pqr->depassementsUniquement = true;

        $this->controlePop($entity, $pqr);

        $sqls = [];
        $this->makeQuery($pqr, $sqls);


        $sql = implode("\n\nUNION\n\n", $sqls);
        $res = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql);
        $depassements = [];
        foreach ($res as $r) {
            $depassements[] = PlafondControle::fromArray($r);
        }

        return $depassements;
    }



    protected function controlePop(?PlafondDataInterface $entity, PlafondQueryParams $pqr)
    {
        $pqr->add($entity);

        if ($entity instanceof Intervenant) {
            $this->controlePop($entity->getStructure(), $pqr);
        }

        if ($entity instanceof ElementPedagogique) {
            $this->controlePop($entity->getStructure(), $pqr);
        }

        if ($entity instanceof Service) {
            $this->controlePop($entity->getElementPedagogique(), $pqr);
            $this->controlePop($entity->getIntervenant(), $pqr);
            $this->controlePop($entity->getStructure(), $pqr);
        }

        if ($entity instanceof VolumeHoraire) {
            $this->controlePop($entity->getService(), $pqr);
        }

        if ($entity instanceof ServiceReferentiel) {
            /* Ca n'a pas de sens de limiter le nombre d'heures total par fonction uniquement */
            //$this->controlePop($entity->getFonctionReferentiel(), $pqr);
            $this->controlePop($entity->getIntervenant(), $pqr);
            $this->controlePop($entity->getStructure(), $pqr);
        }

        if ($entity instanceof VolumeHoraireReferentiel) {
            $this->controlePop($entity->getServiceReferentiel(), $pqr);
        }

        if ($entity instanceof Mission) {
            $this->controlePop($entity->getTypeMission(), $pqr);
            $this->controlePop($entity->getIntervenant(), $pqr);
            $this->controlePop($entity->getStructure(), $pqr);
        }

        if ($entity instanceof VolumeHoraireMission) {
            $this->controlePop($entity->getMission(), $pqr);
        }
    }



    /**
     * @param TypeVolumeHoraire $typeVolumeHoraire
     * @param Intervenant $intervenant
     *
     * @return PlafondControle[]
     */
    public function derogations(TypeVolumeHoraire $typeVolumeHoraire, Intervenant $intervenant): array
    {
        $sql = "
        SELECT
          p.id id,
          p.numero numero,
          p.libelle libelle,
          p.message message,
          pp.code perimetre,
          pe.code etat,
          CASE pe.code WHEN 'bloquant' THEN 1 ELSE 0 END bloquant,
          CASE WHEN COALESCE(tpi.heures,0) - ps.heures > 0 THEN 1 ELSE 0 END depassement,
          COALESCE(tpi.heures,0) heures,
          ps.heures plafond,
          COALESCE(pd.heures,0) derogation
        FROM
          intervenant i
          JOIN type_volume_horaire tvh ON 1=1
          JOIN statut s ON s.id = i.statut_id
          JOIN plafond_statut ps ON ps.statut_id = s.id AND ps.histo_destruction IS NULL
          JOIN plafond p ON p.id = ps.plafond_id
          JOIN plafond_perimetre pp ON pp.id = p.plafond_perimetre_id
          JOIN plafond_etat pe ON pe.id = CASE tvh.code WHEN 'PREVU' THEN ps.plafond_etat_prevu_id WHEN 'REALISE' THEN ps.plafond_etat_realise_id ELSE 0 END
          LEFT JOIN tbl_plafond_intervenant tpi ON tpi.intervenant_id = i.id AND tpi.plafond_id = p.id AND tpi.type_volume_horaire_id = tvh.id
          LEFT JOIN plafond_derogation pd ON pd.histo_destruction IS NULL AND pd.intervenant_id = i.id AND pd.plafond_id = p.id
        WHERE
          i.id = :intervenant
          AND tvh.id = :typeVolumeHoraire
          AND pe.code NOT IN ('desactive', 'indicateur')
        ";

        $params = [
            'intervenant'       => $intervenant->getId(),
            'typeVolumeHoraire' => $typeVolumeHoraire->getId(),
        ];
        $res = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql, $params);

        $depassements = [];
        foreach ($res as $r) {
            $depassements[] = PlafondControle::fromArray($r);
        }

        return $depassements;
    }



    protected function makeQuery(PlafondQueryParams $pqr, array &$sqls)
    {
        foreach ($pqr->entities as $class => $entities) {
            $sql = $this->doMakeQuery($pqr, $class, $entities);
            if ($sql) {
                $sqls[] = $sql;
            }
        }
    }



    protected function doMakeQuery(PlafondQueryParams $pqr, string $class, array $entities): string
    {
        $filters = [];


        switch ($class) {
            case Structure::class:
                $perimetre = PlafondPerimetre::STRUCTURE;
                $join = "JOIN STRUCTURE entity ON entity.id = pd.STRUCTURE_ID";
                $libVal = 'entity.libelle_court';
                $groupBy = 'entity.libelle_court';
                $filters['pd.STRUCTURE_ID'] = $entities;
                $filters['pd.ANNEE_ID'] = (int)$this->getServiceContext()->getAnnee()->getId();
                break;

            case Intervenant::class:
                $perimetre = PlafondPerimetre::INTERVENANT;
                $join = "JOIN INTERVENANT entity ON entity.id = pd.INTERVENANT_ID";
                $libVal = "entity.prenom || ' ' || entity.nom_usuel";
                $groupBy = 'entity.prenom, entity.nom_usuel';
                $filters['pd.INTERVENANT_ID'] = $entities;
                break;

            case ElementPedagogique::class:
                $perimetre = PlafondPerimetre::ELEMENT;
                $join = "JOIN ELEMENT_PEDAGOGIQUE entity ON entity.id = pd.ELEMENT_PEDAGOGIQUE_ID";
                $libVal = 'entity.libelle';
                $groupBy = 'entity.libelle';
                $filters['pd.ELEMENT_PEDAGOGIQUE_ID'] = $entities;
                break;

            case VolumeHoraire::class: // à retravailler : pas utilisé pour le moment
                $perimetre = PlafondPerimetre::VOLUME_HORAIRE;
                $join = "JOIN ELEMENT_PEDAGOGIQUE entity1 ON entity1.id = pd.ELEMENT_PEDAGOGIQUE_ID\n";
                $join .= "JOIN TYPE_INTERVENTION entity2 ON entity2.id = pd.TYPE_INTERVENTION_ID";
                $libVal = "entity1.code || ' ' || entity2.code ";
                $groupBy = 'entity1.code, entity2.code';
                //$filters[] = [];
                throw new \Exception('à finaliser');
                foreach($entities as $entity){

                }
                $filters['pd.ELEMENT_PEDAGOGIQUE_ID'] = (int)$pqr->entity->getService()->getElementPedagogique()->getId();
                $filters['pd.TYPE_INTERVENTION_ID'] = (int)$pqr->entity->getTypeIntervention()->getId();
                break;

            case ServiceReferentiel::class:
                $intervenants = [];
                $fonctions = [];
                foreach( $entities as $entity){
                    /* @var $entity ServiceReferentiel */
                    if ($intervenant = $entity->getIntervenant()){
                        $intervenants[$intervenant->getId()] = $intervenant;
                    }
                    if ($fonction = $entity->getFonctionReferentiel()){
                        $fonctions[$fonction->getId()] = $fonction;
                    }
                }

                $perimetre = PlafondPerimetre::REFERENTIEL;
                $join = "JOIN FONCTION_REFERENTIEL entity ON entity.id = pd.FONCTION_REFERENTIEL_ID";
                $libVal = 'entity.libelle_court';
                $groupBy = 'entity.libelle_court';
                $filters['pd.FONCTION_REFERENTIEL_ID'] = $fonctions;
                $filters['pd.INTERVENANT_ID'] = $intervenants;
                $filters['pd.ANNEE_ID'] = (int)$this->getServiceContext()->getAnnee()->getId();
                break;

            /* Ca n'a pas de sens de limiter le nombre d'heures total par fonction uniquement */
            /*case FonctionReferentiel::class:
                $perimetre = PlafondPerimetre::REFERENTIEL;
                $join = "JOIN FONCTION_REFERENTIEL entity ON entity.id = pd.FONCTION_REFERENTIEL_ID";
                $libVal = 'entity.libelle_court';
                $groupBy = 'entity.libelle_court';
                $filters['pd.FONCTION_REFERENTIEL_ID'] = $entities;
                $filters['pd.ANNEE_ID'] = (int)$this->getServiceContext()->getAnnee()->getId();
                break;*/

            case TypeMission::class:
                $perimetre = PlafondPerimetre::MISSION;
                $join = "JOIN TYPE_MISSION entity ON entity.id = pd.TYPE_MISSION_ID";
                $libVal = 'entity.libelle';
                $groupBy = 'entity.libelle';
                $filters['pd.TYPE_MISSION_ID'] = $entities;
                $filters['pd.ANNEE_ID'] = (int)$this->getServiceContext()->getAnnee()->getId();
                break;
            default:
                return '';
        }

        if ($pqr->bloquantUniquement) {
            $filters['pe.bloquant'] = 1;
        }
        if ($pqr->depassementsUniquement) {
            $filters['pd.depassement'] = 1;
        }
        if ($pqr->bloquantOuDepassementUniquement) {
            $filters[] = '(pd.depassement = 1 OR pe.bloquant = 1)';
        }

        $sqlFilters = '';
        foreach ($filters as $v => $c) {
            if (is_int($v)){ // Pas de colonne précise à filtrer
                if (is_array($c)){
                    // Liste de valeurs avec des OR.
                    throw new \Exception('à finaliser');
                    foreach( $c as $cc ){

                    }
                }else{
                    // filtre personnalisé
                    $sqlFilters .= "\n  AND $c";
                }
            }else{ // la colonne est précisée.
                if (is_int($c)){ // si la valeur est un entier
                    $sqlFilters .= "\n  AND $v = $c";
                }elseif (is_array($c) && count($c) == 1) { // si c'est un tableau d'entités à une entrée
                    $c = array_key_first($c);
                    $sqlFilters .= "\n  AND $v = $c";
                }elseif(is_array($c)){ // si c'est un tableau d'entités
                    $c = implode(',', array_keys($c));
                    $sqlFilters .= "\n  AND $v IN ($c)";
                }
            }
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
              CASE WHEN p.message IS NOT NULL THEN replace(p.message, ':sujet', $libVal) ELSE p.libelle END message,
              pp.code        perimetre,
              pe.code        etat,
              pe.bloquant    bloquant,
              pd.depassement depassement,
              SUM(pd.heures) heures,
              pd.plafond     plafond,
              pd.derogation  derogation
            FROM 
              " . ($pqr->useView ? 'v_' : '') . "tbl_plafond_" . $perimetre . " pd
              JOIN plafond               p ON p.id = pd.plafond_id
              JOIN plafond_etat         pe ON pe.id = pd.plafond_etat_id
              JOIN plafond_perimetre    pp ON pp.id = p.plafond_perimetre_id
              $join
            WHERE
              pe.code NOT IN ('desactive', 'indicateur')
              AND pd.type_volume_horaire_id = " . ((int)$pqr->typeVolumeHoraire->getId())."
              $sqlFilters
            GROUP BY
              p.id, p.numero, p.libelle, p.message, pp.code, pe.code,
              pe.bloquant, pd.depassement, pd.plafond, pd.derogation,
              $groupBy
        ";

        return $sql;
    }



    public function construireVues()
    {
        $colsPos = require getcwd() . '/data/ddl_columns_pos.php';

        $dql = "
        SELECT
          pp, p
        FROM
          Plafond\Entity\Db\PlafondPerimetre pp
          LEFT JOIN pp.plafond p
        ";

        $q = $this->getEntityManager()->createQuery($dql);


        /** @var $perimetres PlafondPerimetre[] */
        $perimetres = $q->execute();

        $tvhPrevuId = $this->getServiceTypeVolumeHoraire()->getPrevu()->getId();
        $tvhRealiseId = $this->getServiceTypeVolumeHoraire()->getRealise()->getId();
        $configTablesJoin = [
            "structure"      => "plafond_structure ps ON ps.plafond_id = p.plafond_id AND ps.structure_id = p.structure_id AND ps.annee_id = p.annee_id AND ps.histo_destruction IS NULL",
            "intervenant"    => "plafond_statut ps ON ps.plafond_id = p.plafond_id AND ps.statut_id = i.statut_id AND ps.annee_id = i.annee_id AND ps.histo_destruction IS NULL",
            "element"        => "plafond_statut ps ON 1 = 0",
            "volume_horaire" => "plafond_statut ps ON 1 = 0",
            "referentiel"    => "plafond_referentiel ps ON ps.plafond_id = p.plafond_id AND ps.fonction_referentiel_id = p.fonction_referentiel_id AND ps.annee_id = i.annee_id AND ps.histo_destruction IS NULL",
            "mission"        => "plafond_mission ps ON ps.plafond_id = p.plafond_id AND ps.type_mission_id = p.type_mission_id AND ps.annee_id = i.annee_id AND ps.histo_destruction IS NULL",
        ];

        foreach ($perimetres as $perimetre) {
            $cols = $colsPos['TBL_PLAFOND_' . strtoupper($perimetre->getCode())];
            $cols = array_diff($cols, ['ID', 'DEPASSEMENT']);

            $view = "CREATE OR REPLACE FORCE VIEW V_TBL_PLAFOND_" . strtoupper($perimetre->getCode()) . ' AS';
            $view .= "\nSELECT";
            foreach ($cols as $col) {
                if ($col != 'PLAFOND_ETAT_ID' && $col != 'DEROGATION' && $col != 'DEPASSEMENT' && $col != 'PLAFOND') {
                    $view .= "\n  p.$col,";
                }
            }
            $view .= "\n  COALESCE(p.PLAFOND,ps.heures,0) PLAFOND,";
            if (!in_array($perimetre->getCode(), [$perimetre::VOLUME_HORAIRE, $perimetre::ELEMENT])) {
                $view .= "\n  CASE";
                $view .= "\n    WHEN p.type_volume_horaire_id = $tvhPrevuId THEN ps.plafond_etat_prevu_id";
                $view .= "\n    WHEN p.type_volume_horaire_id = $tvhRealiseId THEN ps.plafond_etat_realise_id";
                $view .= "\n    ELSE COALESCE(p.plafond_etat_id,1)";
                $view .= "\n  END plafond_etat_id,";
            } else {
                $view .= "\n  COALESCE(p.plafond_etat_id,1) plafond_etat_id,";
            }

            $view .= "\n  COALESCE(pd.heures, 0) derogation,";
            $view .= "\n  CASE WHEN p.heures > COALESCE(p.PLAFOND,ps.heures,0) + COALESCE(pd.heures, 0) + 0.05 THEN 1 ELSE 0 END depassement";
            $view .= "\nFROM\n  (";
            $plafonds = $perimetre->getPlafond();
            $first = true;
            $hasQuery = false;
            foreach ($plafonds as $plafond) {
                $testRes = $this->testRequete($plafond);

                if ($testRes['success']) {

                    $hasQuery = true;
                    if (!$first) $view .= "\n\n    UNION ALL\n";
                    $view .= "\n  SELECT";

                    foreach( $cols as $col ){
                        $colVal = "NULL $col";
                        switch($col){
                            case 'PLAFOND_ID':
                                $colVal = (string)$plafond->getId()." $col";
                                break;
                            case 'DEROGATION':
                                $colVal = null;
                                break;
                            default:
                                if (in_array($col, $testRes['columns'])){
                                    $colVal = 'p.'.$col;
                                }
                        }
                        if ($colVal !== null){
                            $view .= " $colVal,";
                        }
                    }
                    $view = substr($view, 0, -1); // on retire la dernière virgule
                    $view .= " FROM (\n    ";
                    $view .= str_replace("\n", "\n      ", $plafond->getRequete());
                    $view .= "\n    ) p";
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
            if (!in_array($perimetre->getCode(), [$perimetre::STRUCTURE, $perimetre::ELEMENT])) {
                $view .= "\n  JOIN intervenant i ON i.id = p.intervenant_id";
            }
            $view .= "\n  LEFT JOIN " . $configTablesJoin[$perimetre->getCode()];
            $view .= "\n  LEFT JOIN plafond_derogation pd ON pd.plafond_id = p.plafond_id AND pd.intervenant_id = p.intervenant_id AND pd.histo_destruction IS NULL";
            $view .= "\nWHERE\n";
            if (!in_array($perimetre->getCode(),[$perimetre::VOLUME_HORAIRE, $perimetre::ELEMENT])) {
                $view .= "  CASE\n";
                $view .= "    WHEN p.type_volume_horaire_id = $tvhPrevuId THEN ps.plafond_etat_prevu_id\n";
                $view .= "    WHEN p.type_volume_horaire_id = $tvhRealiseId THEN ps.plafond_etat_realise_id\n";
                $view .= "  END IS NOT NULL";
            } else {
                $view .= "  1=1";
            }
            foreach ($cols as $col) {
                if ($col != 'PLAFOND' && $col != 'HEURES' && $col != 'DEROGATION') {
                    $view .= "\n  /*@$col=p.$col*/";
                }
            }
            $this->getEntityManager()->getConnection()->executeStatement($view);
        }
    }



    public function testRequete(Plafond $plafond): array
    {
        $colsPos = require getcwd() . '/data/ddl_columns_pos.php';
        $cols = $colsPos['TBL_PLAFOND_' . strtoupper($plafond->getPlafondPerimetre()->getCode())];
        $cols = array_diff($cols, ['ID', 'PLAFOND_ID', 'PLAFOND_ETAT_ID', 'DEROGATION', 'DEPASSEMENT', 'PLAFOND']);

        $return = ['success' => true];

        try {
            $sql = 'SELECT * FROM (' . $plafond->getRequete() . ') r WHERE ROWNUM = 1';
            $res = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql);

            if (empty($res)) {
                $return['success'] = false;
                $return['error'] = 'Le plafond ne retourne aucune donnée';

                return $return;
            }

            $res = $res[0];
            $return['columns'] = array_keys($res);

            foreach ($cols as $col) {
                if (!isset($res[$col])) {
                    $return['sucess'] = false;
                    $return['error'] = 'Colonne ' . $col . ' manquante';

                    return $return;
                }
            }

            return $return;
        } catch (\Exception $e) {
            $return['success'] = false;
            $return['error'] = $e->getMessage();

            return $return;
        }
    }



    /**
     * @param array $tableauxBords
     * @param Intervenant|int $intervenant
     */
    public function calculerDepuisEntite(PlafondDataInterface $entity)
    {
        if ($entity instanceof Structure) {
            $this->calculer(PlafondPerimetre::STRUCTURE, 'STRUCTURE_ID', $entity->getId());
        }

        if ($entity instanceof Statut) {
            // on calcule pout toute l'année, car on ne dispose pas du filtre par statut
            $this->calculer(PlafondPerimetre::INTERVENANT, 'ANNEE_ID', $entity->getAnnee()->getId());
        }

        if ($entity instanceof Intervenant) {
            $this->calculer(PlafondPerimetre::INTERVENANT, 'INTERVENANT_ID', $entity->getId());
            if ($entity->getStructure()) {
                $this->calculerDepuisEntite($entity->getStructure());
            }
        }

        if ($entity instanceof ElementPedagogique) {
            $this->calculer(PlafondPerimetre::ELEMENT, 'ELEMENT_PEDAGOGIQUE_ID', $entity->getId());
            $this->calculer(PlafondPerimetre::VOLUME_HORAIRE, 'ELEMENT_PEDAGOGIQUE_ID', $entity->getId());
            if ($entity->getStructure()) {
                $this->calculerDepuisEntite($entity->getStructure());
            }
        }

        if ($entity instanceof FonctionReferentiel) {
            $this->calculer(PlafondPerimetre::REFERENTIEL, 'FONCTION_REFERENTIEL_ID', $entity->getId());
        }

        if ($entity instanceof VolumeHoraire) {
            if ($entity->getService()) {
                if ($entity->getService()->getElementPedagogique()) {
                    $this->calculer(PlafondPerimetre::VOLUME_HORAIRE, 'ELEMENT_PEDAGOGIQUE_ID', $entity->getService()->getElementPedagogique());
                }

                $this->calculerDepuisEntite($entity->getService());
            }
        }

        if ($entity instanceof Service) {
            if ($entity->getElementPedagogique()) {
                $this->calculerDepuisEntite($entity->getElementPedagogique());
            }
            if ($entity->getIntervenant()) {
                $this->calculerDepuisEntite($entity->getIntervenant());
            }
        }

        if ($entity instanceof ServiceReferentiel) {
            if ($entity->getFonctionReferentiel()) {
                $this->calculerDepuisEntite($entity->getFonctionReferentiel());
            }
            if ($entity->getIntervenant()) {
                $this->calculerDepuisEntite($entity->getIntervenant());
            }
            if ($entity->getStructure()) {
                $this->calculerDepuisEntite($entity->getStructure());
            }
        }

        if ($entity instanceof VolumeHoraireReferentiel) {
            if ($entity->getServiceReferentiel()) {
                $this->calculerDepuisEntite($entity->getServiceReferentiel());
            }
        }

        if ($entity instanceof TypeMission) {
            $this->calculer(PlafondPerimetre::MISSION, 'TYPE_MISSION_ID', $entity->getId());
        }

        if ($entity instanceof Mission) {
            if ($entity->getTypeMission()) {
                $this->calculerDepuisEntite($entity->getTypeMission());
            }
            if ($entity->getIntervenant()) {
                $this->calculerDepuisEntite($entity->getIntervenant());
            }
            if ($entity->getStructure()) {
                $this->calculerDepuisEntite($entity->getStructure());
            }
        }

        if ($entity instanceof VolumeHoraireMission) {
            if ($entity->getMission()) {
                $this->calculerDepuisEntite($entity->getMission());
            }
        }
    }



    /**
     * @param string|PlafondPerimetre $perimetre
     * @param string|null $param
     * @param string|null $value
     */
    public function calculer($perimetre, ?string $param = null, ?string $value = null)
    {
        if ($perimetre instanceof PlafondPerimetre) $perimetre = $perimetre->getCode();
        $tblName = 'plafond_' . $perimetre;
        if ($param){
            $params = [$param => $value];
        }else{
            $params = [];
        }
        $this->getServiceTableauBord()->calculer($tblName, $params);
    }



    public function construire()
    {
        $this->getBdd()->data()->run('update', 'INDICATEUR');
        $this->construireVues();
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
        if ($entity instanceof Structure) {
            return PlafondStructure::class;
        }
        if ($entity instanceof Statut) {
            return PlafondStatut::class;
        }
        if ($entity instanceof FonctionReferentiel) {
            return PlafondReferentiel::class;
        }
        if ($entity instanceof TypeMission) {
            return PlafondMission::class;
        }
        throw new \Exception("L'entité fournie ne permet pas de récupérer une configuration de plafond");
    }



    /**
     * @param Plafond|int $plafond
     * @param null|Structure|FonctionReferentiel|Statut $entity
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
            PlafondStructure::class   => 'LEFT JOIN p.plafondStructure pc WITH pc.annee = :annee AND pc.histoDestruction IS NULL AND pc.structure = :entity',
            PlafondStatut::class      => 'LEFT JOIN p.plafondStatut pc WITH pc.annee = :annee AND pc.histoDestruction IS NULL AND pc.statut = :entity',
            PlafondReferentiel::class => 'LEFT JOIN p.plafondReferentiel pc WITH pc.annee = :annee AND pc.histoDestruction IS NULL AND pc.fonctionReferentiel = :entity',
            PlafondMission::class     => 'LEFT JOIN p.plafondMission pc WITH pc.annee = :annee AND pc.histoDestruction IS NULL AND pc.typeMission = :entity',
        ];

        $annee = $this->getServiceContext()->getAnnee();
        $class = $this->entityToConfigClass($entity);
        $getter = 'get' . substr($class, strrpos($class, '\\') + 1);
        $where = [];

        $params = ['annee' => $annee];
        if ($entity) {
            $params['entity'] = $entity->getId();
        }

        if ($plafondId > 0) {
            $where[] = "p.id = :pid";
            $params['pid'] = $plafondId;
        }

        if ($perimetre = $class::getPerimetreCode()) {
            $where[] = "prm.code = :perimetre";
            $params['perimetre'] = $perimetre;
        }

        $dql = "
        SELECT
          p, prm" . ($joins[$class] ? ', pc' : '') . "
        FROM
          " . Plafond::class . " p
          JOIN p.plafondPerimetre prm
          " . $joins[$class] . "
        " . (empty($where) ? '' : ('WHERE ' . implode(' AND ', $where))) . "
        ORDER BY
            prm.libelle, p.libelle
        ";

        /** @var Plafond[] $plafonds */
        $plafonds = $this->getEntityManager()->createQuery($dql)->setParameters($params)->getResult();
        $pcs = [];
        foreach ($plafonds as $plafond) {
            $configCount = $plafond->$getter()->count();
            switch ($configCount) {
                case 0:
                    $pc = new $class;
                    $pc->setPlafond($plafond);
                    if ($entity) $pc->setEntity($entity);
                    $pc->setEtatPrevu($this->getEtat(PlafondEtat::DESACTIVE));
                    $pc->setEtatRealise($this->getEtat(PlafondEtat::DESACTIVE));
                    $pc->setHeures(0);
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

        if (!$plafondConfig->getId()) {
            $plafondConfig->getPlafond()->addConfig($plafondConfig);
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