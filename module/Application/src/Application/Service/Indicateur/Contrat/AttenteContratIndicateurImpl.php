<?php

namespace Application\Service\Indicateur\Contrat;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\TypeContrat;
use Application\Entity\Db\WfEtape;
use Application\Service\Indicateur\AbstractIntervenantResultIndicateurImpl;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AttenteContratIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    protected $singularTitlePattern = "%s vacataire est en attente de son contrat initial";
    protected $pluralTitlePattern   = "%s vacataires sont en attente de leur contrat initial";
    
    /**
     * Retourne l'URL de la page concernant une ligne de résultat de l'indicateur.
     * 
     * @param IntervenantEntity $result
     * @return string
     */
    public function getResultItemUrl($result)
    {
        return $this->getHelperUrl()->fromRoute(
                'intervenant/contrat', 
                ['intervenant' => $result->getRouteParam()], 
                ['force_canonical' => true]);
    }
    
    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        // INDISPENSABLE si plusieurs requêtes successives sur Intervenant !
        $this->getEntityManager()->clear('Application\Entity\Db\Intervenant');
        
        $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\Intervenant')->createQueryBuilder("int");
        $qb
            ->join("int.statut", "st", Join::WITH, "st.peutAvoirContrat = 1")
            ->join("int.service", "s")
            ->join("s.elementPedagogique", "ep")
            ->join("s.volumeHoraire", "vh")
            ->join("vh.typeVolumeHoraire", "tvh", Join::WITH, "tvh = :tvh")->setParameter('tvh', $this->getTypeVolumeHoraire())
            ->join("vh.validation", "v")
            ->andWhere("int.annee = :annee")
            ->setParameter("annee", $this->getServiceContext()->getAnnee())
            ->andWhere("1 = pasHistorise(s)")
            ->andWhere("1 = pasHistorise(ep)")
            ->andWhere("1 = pasHistorise(vh)")
            ->andWhere("1 = pasHistorise(v)");

        /**
         * Dans la progression de l'intervenant dans le WF, toutes les étapes précédant l'étape Contrat doivent avoir été franchies
         */
        $qb
                ->join("int.wfIntervenantEtape", "p", Join::WITH, "p.courante = 1")
                ->join("p.etape", "e", Join::WITH, "e.code = :codeEtape")
                ->setParameter('codeEtape', WfEtape::CODE_CONTRAT);
        
        /**
         * Aucun contrat validé ne doit exister
         */
        $notExists = 
                "SELECT c FROM Application\Entity\Db\Contrat c " .
                "JOIN c.typeContrat tc WITH tc.code = :codeTypeContrat " .
                "WHERE c.intervenant = int AND c.validation IS NOT NULL " .
                "AND 1 = pasHistorise(c)";
        $qb
                ->andWhere("NOT EXISTS ( $notExists )")
                ->setParameter('codeTypeContrat', TypeContrat::CODE_CONTRAT);
        
        /**
         * L'intervenant doit intervenir dans la structure spécifiée.
         */
        if ($this->getStructure()) {
            $qb
                    ->andWhere("ep.structure = :structure")
                    ->setParameter('structure', $this->getStructure());
        }

        $qb->orderBy("int.nomUsuel, int.prenom");
         
        return $qb;
    }
    
    public function getTypeVolumeHoraire()
    {
        return $this->getServiceLocator()->get('ApplicationTypeVolumeHoraire')->getPrevu();
    }
}