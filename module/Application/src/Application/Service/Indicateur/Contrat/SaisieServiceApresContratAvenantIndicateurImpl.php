<?php

namespace Application\Service\Indicateur\Contrat;

use Application\Entity\Db\TypeVolumeHoraire;
use Application\Service\Indicateur\AbstractIntervenantResultIndicateurImpl;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class SaisieServiceApresContratAvenantIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    protected $singularTitlePattern = "%s vacataire a saisi des heures d'enseignements <em>Prévisionnels</em> supplémentaires depuis l'édition de son contrat ou avenant";
    protected $pluralTitlePattern   = "%s vacataires ont saisi des heures d'enseignements <em>Prévisionnels</em> supplémentaires depuis l'édition de leur contrat ou avenant";
    
    /**
     * Retourne l'URL de la page concernant une ligne de résultat de l'indicateur.
     * 
     * @param IntervenantEntity $result
     * @return string
     */
    public function getResultItemUrl($result)
    {
        return $this->getHelperUrl()->fromRoute(
                'intervenant/validation-service', 
                ['intervenant' => $result->getSourceCode()], 
                ['force_canonical' => true]);
    }
    
    /**
     * 
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        // INDISPENSABLE si plusieurs requêtes successives sur IntervenantExterieur !
        $this->getEntityManager()->clear('Application\Entity\Db\IntervenantExterieur');
        
        $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\IntervenantExterieur')->createQueryBuilder("int");
        $qb
            ->join("int.contrat", "c")
            ->join("int.service", "s")
            ->join("s.elementPedagogique", "ep")
            ->join("s.volumeHoraire", "vh", Join::WITH, "vh.contrat IS NULL")
            ->join("vh.typeVolumeHoraire", "tvh", Join::WITH, "tvh.code = :codeTvh")
            ->setParameter('codeTvh', TypeVolumeHoraire::CODE_PREVU)
            ->andWhere("int.annee = :annee")
            ->setParameter("annee", $this->getServiceContext()->getAnnee())
            ->andWhere("1 = pasHistorise(s)")
            ->andWhere("1 = pasHistorise(ep)")
            ->andWhere("1 = pasHistorise(vh)")
            ->andWhere("1 = pasHistorise(c)");
     
        /**
         * NB: pas besoin de consulter la progression dans le workflow car si l'intervenant a déjà un contrat/avenant,
         * c'est qu'il a bien atteint l'étape "contrat".
         */
        
        if ($this->getStructure()) {
            $qb
                    ->andWhere("ep.structure = :structure")
                    ->andWhere("c.structure = :structure")
                    ->setParameter('structure', $this->getStructure());
        }
        
        $qb->orderBy("int.nomUsuel, int.prenom");
        
        return $qb;
    }
}