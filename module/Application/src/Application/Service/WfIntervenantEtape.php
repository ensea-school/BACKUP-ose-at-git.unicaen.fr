<?php

namespace Application\Service;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\WfEtape as WfEtapeEntity;
use Application\Entity\Db\WfIntervenantEtape as WfIntervenantEtapeEntity;
use Application\Service\AbstractEntityService;
use Application\Service\Workflow\Workflow;
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
//    
//    /**
//     * Remet à jour complètement la progression dans le worflow d'un intervenant.
//     * 
//     * @param Workflow $wf Worflow 
//     * @param IntervenantEntity $intervenant Intervenant concerné
//     * @return WfIntervenantEtapeEntity[]
//     */
//    public function createIntervenantEtapes(Workflow $wf, IntervenantEntity $intervenant)
//    {
//        $ordre = 1;
//        $ies   = [];
//        
//        $wf->setIntervenant($intervenant);
//        
//        /**
//         * NB: la progression stockée en bdd ne prend pas en compte la structure d'enseignement
//         */
//        $wf->setStructure(null);
//
//        /**
//         * Calcul de l'état de chaque étape du workflow.
//         */
//        $wf->getCurrentStep();
//        
//        /**
//         * RAZ progression !
//         */
//        $this->removeAllIntervenantEtapes($intervenant);
//        
//        /**
//         * Début de transaction.
//         */
//        $this->getEntityManager()->beginTransaction();
//        
//        /**
//         * Fetch table des étapes.
//         */
//        $etapes = $this->getServiceWfEtape()->findAll(); /* @var WfEtape[] $etapes  code => WfEtape */
//        
//        /**
//         * Parcours des étapes du workflow.
//         * NB: chaque étape présente dans le workflow est forcément pertinente.
//         */
//        foreach ($wf->getSteps() as $codeEtape => $step) {
//            
//            $etape = $etapes[$codeEtape];
//            
//            /**
//             * Ajout de l'étape dans la progression.
//             */
//            $ie = new WfIntervenantEtapeEntity();
//            $ie
//                    ->setIntervenant($intervenant)
//                    ->setEtape($etape);
//            
//            /**
//             * Marquage de l'étape comme "franchie" et/ou "courante".
//             */
//            $ie
//                    ->setFranchie($step->getDone())
//                    ->setCourante($step->getIsCurrent());
//            
//            /**
//             * Numérotation pour pouvoir trier.
//             */
//            $ie->setOrdre($ordre++);
//            
//            $ies[$codeEtape] = $ie;
//            
//            $this->getEntityManager()->persist($ie);
//        }
//        
//        /**
//         * Fin de transaction
//         */
//        try {
//            $this->getEntityManager()->flush();
//            $this->getEntityManager()->commit();
//        }
//        catch (\Exception $e) {
//            $this->getEntityManager()->rollback();
//            throw $e;
//        }
//        
//        return $ies;
//    }
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
     * Recherche d'une seule étape ou de toutes les étapes dans la progression d'un intervenant.
     * 
     * @param IntervenantEntity $intervenant Intervenant concerné
     * @param string|WfEtapeEntity $etape Etape précise éventuelle
     * @return WfIntervenantEtapeEntity|null|array
     * @throws RuntimeException
     */
    public function findIntervenantEtape(IntervenantEntity $intervenant, $etape = null)
    {
        if (is_string($etape)) {
            $etape = $this->getServiceWfEtape()->getByCode($etape);
        }
        
        $qb = $this->finderByIntervenant($intervenant); /* @var $qb QueryBuilder */
        if ($etape) {
            $this->finderByEtape($etape, $qb);
        }
        $result = $qb->getQuery()->getResult();

        if ($etape) {
            $nb = count($result);
            if ($nb > 1) {
                throw new RuntimeException("Anomalie rencontrée: l'étape '$etape' figure $nb fois dans la progression!");
            }
            
            return $nb ? reset($result) : null;
        }
        
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
