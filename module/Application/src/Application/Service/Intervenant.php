<?php

namespace Application\Service;

use Application\Entity\Db\Finder\FinderIntervenantPermanentWithServiceReferentiel;
use Application\Entity\Db\Finder\FinderIntervenantPermanentWithModificationServiceDu;
use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\Annee as AnneeEntity;
use Application\Entity\Db\Structure as StructureEntity;
use Application\Entity\Db\Periode as PeriodeEntity;
use Common\Exception\RuntimeException;
use Doctrine\ORM\QueryBuilder;
use Import\Processus\Import;

/**
 * Description of Intervenant
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Intervenant extends AbstractEntityService
{
    /**
     * Recherche par :
     * - id source exact (numéro Harpege ou autre), 
     * - ou nom usuel (et prénom), 
     * - ou nom patronymique (et prénom).
     * 
     * @param string $term
     * @return QueryBuilder
     */
    public function finderByNomPrenomId($term, QueryBuilder $qb=null, $alias=null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $term = str_replace(' ', '', $term);
        
        $concatNomUsuelPrenom = new \Doctrine\ORM\Query\Expr\Func('CONVERT', 
                array($qb->expr()->concat($alias.'.nomUsuel', $alias.'.prenom'),
                '?3'));
        $concatNomPatroPrenom = new \Doctrine\ORM\Query\Expr\Func('CONVERT', 
                array($qb->expr()->concat($alias.'.nomPatronymique', $alias.'.prenom'),
                '?3'));
        $concatPrenomNomUsuel = new \Doctrine\ORM\Query\Expr\Func('CONVERT', 
                array($qb->expr()->concat($alias.'.prenom', $alias.'.nomUsuel'),
                '?3'));
        $concatPrenomNomPatro = new \Doctrine\ORM\Query\Expr\Func('CONVERT', 
                array($qb->expr()->concat($alias.'.prenom', $alias.'.nomPatronymique'),
                '?3'));
        
        $qb
//                ->select('i.')
                ->where($alias.'.sourceCode = ?1')
                ->orWhere($qb->expr()->like($qb->expr()->upper($concatNomUsuelPrenom), $qb->expr()->upper('CONVERT(?2, ?3)')))
                ->orWhere($qb->expr()->like($qb->expr()->upper($concatNomPatroPrenom), $qb->expr()->upper('CONVERT(?2, ?3)')))
                ->orWhere($qb->expr()->like($qb->expr()->upper($concatPrenomNomUsuel), $qb->expr()->upper('CONVERT(?2, ?3)')))
                ->orWhere($qb->expr()->like($qb->expr()->upper($concatPrenomNomPatro), $qb->expr()->upper('CONVERT(?2, ?3)')))
                ->orderBy($alias.'.nomUsuel, '.$alias.'.prenom');
        
        $qb->setParameters(array(1 => $term, 2 => "%$term%", 3 => 'US7ASCII'));
        
//        print_r($qb->getQuery()->getSQL()); var_dump($qb->getQuery()->getParameters());die;
        
        return $qb;
    }
    
    /**
     * 
     * @return FinderIntervenantPermanentWithServiceReferentiel
     */
    public function getFinderIntervenantPermanentWithServiceReferentiel()
    {
        $qb = new FinderIntervenantPermanentWithServiceReferentiel($this->getEntityManager(), $this->getContextProvider());

        return $qb;
    }
    
    /**
     * 
     * @return FinderIntervenantPermanentWithModificationServiceDu
     */
    public function getFinderIntervenantPermanentWithModificationServiceDu()
    {
        $qb = new FinderIntervenantPermanentWithModificationServiceDu($this->getEntityManager(), $this->getContextProvider());

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
        return 'Application\Entity\Db\Intervenant';
    }

    /**
     *
     * @param string $sourceCode
     * @return IntervenantEntity
     */
    public function getBySourceCode( $sourceCode )
    {
        if (null == $sourceCode) return null;
        return $this->getRepo()->findOneBy(['sourceCode' => $sourceCode]);
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
     * @param string $codeEtape Ex: WfEtape::CODE_PJ_SAISIE
     * @param QueryBuilder $qb
     * @return QueryBuilder
     * @see \Application\Entity\Db\WfEtape
     */
    public function finderByWfEtapeFranchie($codeEtape, QueryBuilder $qb = null, $alias=null)
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
     * @param string $codeEtape Ex: \Application\Entity\Db\WfEtape::CODE_PIECES_JOINTES
     * @param QueryBuilder $qb
     * @return QueryBuilder
     */
    public function finderByWfEtapeCourante($codeEtape, QueryBuilder $qb = null, $alias=null)
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
     * @return QueryBuilder
     */
    public function finderByPieceJointeFournieNonValidee(QueryBuilder $qb = null, $alias=null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb
                ->join("$alias.dossier", "d")
                ->join("d.pieceJointe", "pj")
                ->leftJoin("pj.validation", "vpj")
                ->andWhere("vpj IS NULL");
        
        return $qb;
    }

    public function finderByMiseEnPaiement(StructureEntity $structure=null, PeriodeEntity $periode=null, QueryBuilder $qb=null, $alias=null)
    {
        $serviceMIS = $this->getServiceLocator()->get('applicationMiseEnPaiementIntervenantStructure');
        /* @var $serviceMIS MiseEnPaiementIntervenantStructure */

        $serviceMiseEnPaiement = $this->getServiceLocator()->get('applicationMiseEnPaiement');
        /* @var $serviceMiseEnPaiement MiseEnPaiement */

        $serviceStructure = $this->getServiceLocator()->get('applicationStructure');
        /* @var $serviceStructure Structure */

        list($qb,$alias) = $this->initQuery($qb, $alias);

        $this               ->join( $serviceMIS             , $qb, 'miseEnPaiementIntervenantStructure', false, $alias );
        $serviceMIS         ->join( $serviceMiseEnPaiement  , $qb, 'miseEnPaiement'                                    );

        if ($structure){
            $serviceMIS->finderByStructure( $structure, $qb );
        }
        if ($periode){
            $serviceMIS->finderByPeriode( $periode, $qb );
        }

        return $qb;
    }

    /**
     * Importe un intervenant si besoin.
     * 
     * @param string $sourceCode Code source
     * @return IntervenantEntity
     * @throws RuntimeException Intervenant déjà importé ou introuvable après import
     */
    public function importer($sourceCode)
    {
        $repo = $this->getEntityManager()->getRepository($this->getEntityClass());

        if (($intervenant = $repo->findOneBySourceCode($sourceCode))) {
            return $intervenant;
        }
        
        $import = $this->getServiceLocator()->get('importProcessusImport'); /* @var $import Import */
        $import->intervenant($sourceCode);

        if (!($intervenant = $repo->findOneBySourceCode($sourceCode))) {
            throw new RuntimeException("Vous n'êtes pas autorisé à vous connecter à OSE avec ce compte. Vous vous prions de vous rapprocher de votre composante pour en obtenir un valide.");
        }
        
        return $intervenant;
    }

    /**
     * Retourne la liste des intervenants
     *
     * @param QueryBuilder|null $queryBuilder
     * @param string|null $alias
     * @return \Application\Entity\Db\Intervenant[]
     */
    public function getList( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.nomUsuel, $alias.prenom");
        return parent::getList($qb, $alias);
    }

}
