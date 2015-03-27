<?php

namespace Application\Service;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\Structure as StructureEntity;
use Application\Entity\Db\WfEtape as WfEtapeEntity;
use Application\Entity\Db\WfIntervenantEtape as WfIntervenantEtapeEntity;
use Application\Service\AbstractEntityService;
use Common\Exception\RuntimeException;
use Doctrine\ORM\QueryBuilder;

/**
 * Service de gestion de la progression d'un intervenant dans le workflow.
 *
 * @author Bertrand
 */
class WfIntervenantEtape extends AbstractEntityService
{        
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\WfIntervenantEtape';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'ie';
    }
    
    /**
     * Efface la progression d'un intervenant.
     * 
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @return self
     */
    public function removeAllIntervenantEtapes(IntervenantEntity $intervenant)
    {
        $this->getEntityManager()->beginTransaction();

        $qb = $this->finderByIntervenant($intervenant); /* @var $qb QueryBuilder */
        $qb->delete()->getQuery()->execute();

        try {
            $this->getEntityManager()->flush();
            $this->getEntityManager()->commit();
        }
        catch (\Exception $e) {
            $this->getEntityManager()->rollback();
            throw $e;
        }
        
        return $this;
    }

    /**
     * Remet à jour complètement la progression dans le worflow d'un intervenant.
     * 
     * @param IntervenantEntity $intervenant Intervenant concerné
     * @return WfIntervenantEtapeEntity[]
     */
    public function createIntervenantEtapes(IntervenantEntity $intervenant)
    {
        $sql = "BEGIN ose_workflow.update_intervenant_etapes(:intervenant); END;";
        
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->bindValue('intervenant', $intervenant->getId());
        $stmt->execute();
        
        return $this;
    }
    
    public function createIntervenantsEtapes()
    {   
        set_time_limit(60);
        
        $sql = "BEGIN ose_workflow.update_intervenants_etapes(); END;";
        
        \UnicaenApp\Util::topChrono();
        
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute();
        
        \UnicaenApp\Util::topChrono();
        
        return $this;
    }
    
    /**
     * Recherche dans la progression d'un intervenant.
     * 
     * @param IntervenantEntity $intervenant Intervenant concerné
     * @param null|StructureEntity $structure Structure concernée : 
     * null = aucune (valeur par défaut), 
     * StructureEntity = structure précise
     * @param string|WfEtapeEntity $etape Etape précise éventuelle
     * @return WfIntervenantEtapeEntity|null|array
     * @throws RuntimeException
     */
    public function findIntervenantEtape(IntervenantEntity $intervenant, StructureEntity $structure = null, $etape = null)
    {
        if (is_string($etape)) {
            $etape = $this->getServiceWfEtape()->getByCode($etape);
        }
        
        $qb = $this->finderByIntervenant($intervenant); /* @var $qb QueryBuilder */
        
        $qb->join("ie.etape", "e", \Doctrine\ORM\Query\Expr\Join::WITH, "e.visible = 1");
        
        if (null === $structure) {
            $qb->andWhere("ie.structure is null"); // i.e. "peu importe la structure"
        }
        else {
            $qb
                    ->andWhere("e.structureDependant = 0 OR e.structureDependant = 1 AND ie.structure = :struct")
                    ->setParameter('struct', $structure);
        }
        if ($etape) {
            $this->finderByEtape($etape, $qb);
        }
        
        $qb->orderBy("ie.ordre");
        
        $result = $qb->getQuery()->getResult();
        
        return $result;
    }
    
    /**
     * Retourne le service WfEtape.
     * 
     * @return WfEtape
     */
    private function getServiceWfEtape()
    {
        return $this->getServiceLocator()->get('WfEtapeService');;
    }
}