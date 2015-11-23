<?php

namespace Application\Service;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\Structure as StructureEntity;
use Application\Entity\Db\Periode as PeriodeEntity;
use Application\Entity\Db\Annee as AnneeEntity;
use Application\Entity\Db\TypeIntervenant;
use Application\Service\Traits\StatutIntervenantAwareTrait;
use Common\Exception\RuntimeException;
use Doctrine\ORM\QueryBuilder;
use Import\Processus\Import;
use UnicaenApp\Util;

/**
 * Description of Intervenant
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Intervenant extends AbstractEntityService
{
    use StatutIntervenantAwareTrait;



    /**
     * @param string  $critere
     * @param integer $limit
     *
     * @return array
     */
    public function recherche($critere, $limit = 50)
    {
        if (strlen($critere) < 2) return [];

        $anneeId = (int)$this->getServiceContext()->getAnnee()->getId();

        $critere  = Util::reduce($critere);
        $criteres = explode('_', $critere);

        $sql     = 'SELECT * FROM V_INTERVENANT_RECHERCHE WHERE rownum <= ' . (int)$limit . ' AND annee_id = ' . $anneeId;
        $sqlCri  = '';
        $criCode = 0;

        foreach ($criteres as $c) {
            $cc = (int)$c;
            if (0 === $cc) {
                if ($sqlCri != '') $sqlCri .= ' AND ';
                $sqlCri .= 'critere LIKE q\'[%' . $c . '%]\'';
            } else {
                $criCode = $cc;
            }
        }
        $orc = '';
        if ($sqlCri != '') {
            $orc[] = '(' . $sqlCri . ')';
        }
        if ($criCode) {
            $orc[] = 'source_code LIKE \'%' . $criCode . '%\'';
        }
        $orc = implode(' OR ', $orc);
        $sql .= ' AND (' . $orc . ') ORDER BY nom_usuel, prenom';
//        sqlDump($sql);

        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);

        $intervenants = [];
        while ($r = $stmt->fetch()) {
            $intervenants[$r['SOURCE_CODE']] = [
                'civilite'         => $r['CIVILITE'],
                'nom'              => $r['NOM_USUEL'],
                'prenom'           => $r['PRENOM'],
                'date-naissance'   => new \DateTime($r['DATE_NAISSANCE']),
                'structure'        => $r['STRUCTURE'],
                'numero-personnel' => $r['SOURCE_CODE'],
            ];
        }

        return $intervenants;
    }



    /**
     * Recherche par :
     * - id source exact (numéro Harpege ou autre),
     * - ou nom usuel (et prénom),
     * - ou nom patronymique (et prénom).
     *
     * @param string $term
     *
     * @return QueryBuilder
     */
    public function finderByNomPrenomId($term, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $term = str_replace(' ', '', $term);

        $concatNomUsuelPrenom = new \Doctrine\ORM\Query\Expr\Func('CONVERT',
            [$qb->expr()->concat($alias . '.nomUsuel', $alias . '.prenom'), '?3']
        );
        $concatNomPatroPrenom = new \Doctrine\ORM\Query\Expr\Func('CONVERT',
            [$qb->expr()->concat($alias . '.nomPatronymique', $alias . '.prenom'), '?3']
        );
        $concatPrenomNomUsuel = new \Doctrine\ORM\Query\Expr\Func('CONVERT',
            [$qb->expr()->concat($alias . '.prenom', $alias . '.nomUsuel'), '?3']
        );
        $concatPrenomNomPatro = new \Doctrine\ORM\Query\Expr\Func('CONVERT',
            [$qb->expr()->concat($alias . '.prenom', $alias . '.nomPatronymique'), '?3']
        );

        $qb
//                ->select('i.')
            ->where($alias . '.sourceCode = ?1')
            ->orWhere($qb->expr()->like($qb->expr()->upper($concatNomUsuelPrenom), $qb->expr()->upper('CONVERT(?2, ?3)')))
            ->orWhere($qb->expr()->like($qb->expr()->upper($concatNomPatroPrenom), $qb->expr()->upper('CONVERT(?2, ?3)')))
            ->orWhere($qb->expr()->like($qb->expr()->upper($concatPrenomNomUsuel), $qb->expr()->upper('CONVERT(?2, ?3)')))
            ->orWhere($qb->expr()->like($qb->expr()->upper($concatPrenomNomPatro), $qb->expr()->upper('CONVERT(?2, ?3)')))
            ->orderBy($alias . '.nomUsuel, ' . $alias . '.prenom');

        $qb->setParameters([1 => $term, 2 => "%$term%", 3 => 'US7ASCII']);

//        print_r($qb->getQuery()->getSQL()); var_dump($qb->getQuery()->getParameters());die;

        return $qb;
    }



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return IntervenantEntity::class;
    }



    /**
     *
     * @param string      $sourceCode
     * @param AnneeEntity $annee
     *
     * @return IntervenantEntity
     */
    public function getBySourceCode($sourceCode, AnneeEntity $annee = null)
    {
        if (null == $sourceCode) return null;

        if (!$annee) {
            $annee = $this->getServiceContext()->getAnnee();
        }

        $findParams = ['sourceCode' => $sourceCode, 'annee' => $annee->getId()];
        $repo       = $this->getRepo();

        $result = $repo->findOneBy($findParams);
        if (!$result) {
            $import = $this->getServiceLocator()->get('importProcessusImport');
            /* @var $import Import */
            $import->intervenant($sourceCode);
            $result = $repo->findOneBy($findParams); // on retente
        }

        return $result;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'int';
    }



    /**
     * Finder par étape franchie dans le workflow de l'intervenant.
     *
     * @param string       $codeEtape Ex: WfEtape::CODE_PJ_SAISIE
     * @param QueryBuilder $qb
     *
     * @return QueryBuilder
     * @see \Application\Entity\Db\WfEtape
     */
    public function finderByWfEtapeFranchie($codeEtape, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb
            ->join("$alias.wfIntervenantEtape", "p", \Doctrine\ORM\Query\Expr\Join::WITH, "p.franchie = 1")
            ->join("p.etape", "e", \Doctrine\ORM\Query\Expr\Join::WITH, "e.code = :codeEtape")
            ->setParameter('codeEtape', $codeEtape);

        return $qb;
    }



    /**
     * Finder par étape courante dans le workflow de l'intervenant.
     *
     * @param string       $codeEtape Ex: \Application\Entity\Db\WfEtape::CODE_PIECES_JOINTES
     * @param QueryBuilder $qb
     *
     * @return QueryBuilder
     */
    public function finderByWfEtapeCourante($codeEtape, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb
            ->join("$alias.wfIntervenantEtape", "p", \Doctrine\ORM\Query\Expr\Join::WITH, "p.courante = 1")
            ->join("p.etape", "e", \Doctrine\ORM\Query\Expr\Join::WITH, "e.code = :codeEtape")
            ->setParameter('codeEtape', $codeEtape);

        return $qb;
    }



    /**
     * Ajoutant les critères permettant de ne retenir que les intervenants ayant fourni
     * une pièce justificative qui n'a pas encore été validée.
     *
     * @param QueryBuilder $qb
     *
     * @return QueryBuilder
     */
    public function finderByPieceJointeFournieNonValidee(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb
            ->join("$alias.dossier", "d")
            ->join("d.pieceJointe", "pj")
            ->leftJoin("pj.validation", "vpj")
            ->andWhere("vpj IS NULL");

        return $qb;
    }



    public function finderByMiseEnPaiement(StructureEntity $structure = null, PeriodeEntity $periode = null, QueryBuilder $qb = null, $alias = null)
    {
        $serviceMIS = $this->getServiceLocator()->get('applicationMiseEnPaiementIntervenantStructure');
        /* @var $serviceMIS MiseEnPaiementIntervenantStructure */

        $serviceMiseEnPaiement = $this->getServiceLocator()->get('applicationMiseEnPaiement');
        /* @var $serviceMiseEnPaiement MiseEnPaiement */

        $serviceStructure = $this->getServiceLocator()->get('applicationStructure');
        /* @var $serviceStructure Structure */

        list($qb, $alias) = $this->initQuery($qb, $alias);

        $this->join($serviceMIS, $qb, 'miseEnPaiementIntervenantStructure', false, $alias);
        $serviceMIS->join($serviceMiseEnPaiement, $qb, 'miseEnPaiement');

        if ($structure) {
            $serviceMIS->finderByStructure($structure, $qb);
        }
        if ($periode) {
            $serviceMIS->finderByPeriode($periode, $qb);
        }

        return $qb;
    }



    /**
     * Importe un intervenant si besoin.
     *
     * @param string $sourceCode Code source
     *
     * @return IntervenantEntity
     * @throws RuntimeException Intervenant déjà importé ou introuvable après import
     */
    public function importer($sourceCode)
    {
        if ($intervenant = $this->getBySourceCode($sourceCode)) {
            return $intervenant;
        }

        $import = $this->getServiceLocator()->get('importProcessusImport');
        /* @var $import Import */
        $import->intervenant($sourceCode);

        if (!($intervenant = $this->getRepo()->findOneBySourceCode($sourceCode))) {
            //    throw new RuntimeException("Vous n'êtes pas autorisé à vous connecter à OSE avec ce compte. Vous vous prions de vous rapprocher de votre composante pour en obtenir un valide.");
        }

        return $intervenant;
    }



    /**
     * Retourne la liste des intervenants
     *
     * @param QueryBuilder|null $queryBuilder
     * @param string|null       $alias
     *
     * @return \Application\Entity\Db\Intervenant[]
     */
    public function getList(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.nomUsuel, $alias.prenom");

        return parent::getList($qb, $alias);
    }



    /**
     * Recherche d'intervenant par le "source code" et l'année.
     *
     * @param string            $sourceCode Code de l'intervenant dans la source de données (ex: numéro Harpege)
     * @param AnneeEntity       $annee      Année concernée
     * @param QueryBuilder|null $queryBuilder
     * @param string|null       $alias
     *
     * @return QueryBuilder
     */
    public function finderBySourceCodeAndAnnee($sourceCode, AnneeEntity $annee, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb
            ->andWhere("$alias.sourceCode = :code AND $alias.annee = :annee")
            ->setParameter('code', $sourceCode)
            ->setParameter('annee', $annee);

        return $qb;
    }



    /**
     * Filtre par le type d'intervenant
     *
     * @param TypeIntervenant   $typeIntervenant Type de l'intervenant
     * @param QueryBuilder|null $queryBuilder
     * @param string|null       $alias
     *
     * @return QueryBuilder
     */
    public function finderByType(TypeIntervenant $typeIntervenant, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $sStatut = $this->getServiceStatutIntervenant();

        $this->join($sStatut, $qb, 'statut', false, $alias);
        $sStatut->finderByTypeIntervenant($typeIntervenant, $qb);

        return $qb;
    }
}
