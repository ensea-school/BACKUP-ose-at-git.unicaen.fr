<?php

namespace Application\Service\Indicateur;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\WfEtape;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AgrementCAMaisPasContratIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    protected $singularTitlePattern = "%s vacataire a reçu l'agrément du Conseil Académique et n'a pas encore de contrat/avenant";
    protected $pluralTitlePattern   = "%s vacataires ont reçu l'agrément du Conseil Académique et n'ont pas encore de contrat/avenant";
    
    /**
     * Retourne l'URL de la page concernant une ligne de résultat de l'indicateur.
     * 
     * @param IntervenantEntity $result
     * @return string
     */
    public function getResultUrl($result)
    {
        return $this->getHelperUrl()->fromRoute(
                'intervenant/contrat', 
                ['intervenant' => $result->getSourceCode()], 
                ['force_canonical' => true]);
    }
    
    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        // INDISPENSABLE si plusieurs requêtes successives sur Intervenant !
        $this->getEntityManager()->clear('Application\Entity\Db\IntervenantExterieur');
        
        $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\IntervenantExterieur')->createQueryBuilder("int");
        $qb
                ->join("int.statut", "st", Join::WITH, "st.peutAvoirContrat = 1")
                ->join("int.agrement", "a")
                ->join("a.type", "ta", Join::WITH, "ta.code = :cta")
                ->setParameter('cta', \Application\Entity\Db\TypeAgrement::CODE_CONSEIL_ACADEMIQUE)
                // l'étape Contrat doit être courante
                ->join("int.wfIntervenantEtape", "p", Join::WITH, "p.courante = 1")
                ->join("p.etape", "e", Join::WITH, "e.code = :ce")
                ->setParameter('ce', WfEtape::CODE_CONTRAT);
        
        if ($this->getStructure()) {
            $qb
                    ->leftJoin("int.contrat", "c", Join::WITH, "c.validation IS NOT NULL AND c.structure = :structure")
                    ->setParameter('structure', $this->getStructure());
        }
        else {
            $qb->leftJoin("int.contrat", "c", Join::WITH, "c.validation IS NOT NULL");
        }
        
        $qb->andWhere("c.id IS NULL");
        
        /**
         * L'intervenant doit intervenir dans la structure spécifiée.
         */
        if ($this->getStructure()) {
            $qb
                    ->join("int.service", "s")
                    ->join("s.elementPedagogique", "ep")
                    ->setParameter('structure', $this->getStructure())
                    ->join("s.volumeHoraire", "vh")
                    ->join("vh.typeVolumeHoraire", "tvh", Join::WITH, "tvh = :tvh")
                    ->andWhere("ep.structure = :structure")
                    ->setParameter('tvh', $this->getServiceLocator()->get('ApplicationTypeVolumeHoraire')->getPrevu());
        }
        
        $qb->orderBy("int.nomUsuel, int.prenom");
         
        return $qb;
    }
}