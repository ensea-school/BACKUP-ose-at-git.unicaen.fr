<?php

namespace Application\Service;

use Application\Entity\Db\Finder\FinderIntervenantPermanentWithServiceReferentiel;
use Application\Entity\Db\Finder\FinderIntervenantPermanentWithModificationServiceDu;
use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\Annee as AnneeEntity;
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
    public function finderByNomPrenomId($term)
    {
        $term = str_replace(' ', '', $term);
        
        $qb = $this->getRepo()->createQueryBuilder($this->getAlias());
        
        $concatNomUsuelPrenom = new \Doctrine\ORM\Query\Expr\Func('CONVERT', 
                array($qb->expr()->concat($this->getAlias().'.nomUsuel', $this->getAlias().'.prenom'),
                '?3'));
        $concatNomPatroPrenom = new \Doctrine\ORM\Query\Expr\Func('CONVERT', 
                array($qb->expr()->concat($this->getAlias().'.nomPatronymique', $this->getAlias().'.prenom'),
                '?3'));
        $concatPrenomNomUsuel = new \Doctrine\ORM\Query\Expr\Func('CONVERT', 
                array($qb->expr()->concat($this->getAlias().'.prenom', $this->getAlias().'.nomUsuel'),
                '?3'));
        $concatPrenomNomPatro = new \Doctrine\ORM\Query\Expr\Func('CONVERT', 
                array($qb->expr()->concat($this->getAlias().'.prenom', $this->getAlias().'.nomPatronymique'),
                '?3'));
        
        $qb
//                ->select('i.')
                ->where($this->getAlias().'.sourceCode = ?1')
                ->orWhere($qb->expr()->like($qb->expr()->upper($concatNomUsuelPrenom), $qb->expr()->upper('CONVERT(?2, ?3)')))
                ->orWhere($qb->expr()->like($qb->expr()->upper($concatNomPatroPrenom), $qb->expr()->upper('CONVERT(?2, ?3)')))
                ->orWhere($qb->expr()->like($qb->expr()->upper($concatPrenomNomUsuel), $qb->expr()->upper('CONVERT(?2, ?3)')))
                ->orWhere($qb->expr()->like($qb->expr()->upper($concatPrenomNomPatro), $qb->expr()->upper('CONVERT(?2, ?3)')))
                ->orderBy($this->getAlias().'.nomUsuel, '.$this->getAlias().'.prenom');
        
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
     * Finder par étape courante dans le workflow de l'intervenant.
     * 
     * @param string $codeEtape Ex: \Application\Entity\Db\WfEtape::CODE_PIECES_JOINTES
     * @param QueryBuilder $qb
     */
    public function finderByWfEtapeCourante($codeEtape, QueryBuilder $qb = null)
    {
        list($qb, $alias) = $this->initQuery($qb);
        $qb
                ->join("$alias.wfIntervenantEtape", "p", \Doctrine\ORM\Query\Expr\Join::WITH, "p.courante = 1")
                ->join("p.etape", "e", \Doctrine\ORM\Query\Expr\Join::WITH, "e.code = :codeEtape")
                ->setParameter('codeEtape', $codeEtape);

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

    public function calculFormule( IntervenantEntity $intervenant, AnneeEntity $annee=null )
    {
        if (empty($annee)) $annee = $this->getContextProvider()->getGlobalContext()->getAnnee();

        $intervenantId = $intervenant->getId();
        $anneeId = $annee->getId();
        $sql = "BEGIN OSE_FORMULE.CALCULER( $intervenantId, $anneeId ); END;";
        $this->getEntityManager()->getConnection()->executeQuery($sql);
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
