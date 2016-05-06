<?php

namespace Application\Service\Indicateur\Dossier;

use Application\Service\Indicateur\AbstractIntervenantResultIndicateurImpl;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class DonneesPersoDiffImportIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    protected $singularTitlePattern = "%s vacataire a saisi des données personnelles qui diffèrent de celles importées";
    protected $pluralTitlePattern   = "%s vacataires ont saisi des données personnelles qui diffèrent de celles importées";
    
    /**
     * Retourne l'URL de la page concernant une ligne de résultat de l'indicateur.
     * 
     * @param IntervenantEntity $result
     * @return string
     */
    public function getResultItemUrl($result)
    {
        return $this->getHelperUrl()->fromRoute(
                'indicateur/result-item', 
                ['action' => 'result-item-donnees-perso-diff-import', 'intervenant' => $result->getRouteParam()], 
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
            ->join("int.statut", "st", Join::WITH, "st.peutSaisirDossier = 1")
            ->join("int.vIndicDiffDossier", "vidd")
            ->andWhere(
                    "vidd.adresseDossier IS NOT NULL OR " .
                    "vidd.ribDossier IS NOT NULL OR " .
                    "vidd.nomUsuelDossier IS NOT NULL OR " .
                    "vidd.prenomDossier IS NOT NULL")
            ->andWhere("int.annee = :annee")
            ->setParameter("annee", $this->getServiceContext()->getAnnee());
        
        /**
         * L'intervenant doit intervenir dans la structure spécifiée éventuelle.
         */
        if ($this->getStructure()) {
            $qb
                ->join("int.service", "s")
                ->join("s.elementPedagogique", "ep", Join::WITH, "ep.structure = :structure")
                ->join("s.volumeHoraire", "vh")
                ->join("vh.typeVolumeHoraire", "tvh", Join::WITH, "tvh = :tvh")
                ->setParameter('tvh', $this->getServiceLocator()->get('ApplicationTypeVolumeHoraire')->getPrevu())
                ->setParameter('structure', $this->getStructure())
                ->andWhere("1 = pasHistorise(s)")
                ->andWhere("1 = pasHistorise(ep)")
                ->andWhere("1 = pasHistorise(vh)");
        }
        
        $qb->orderBy("int.nomUsuel, int.prenom");
        
        return $qb;
    }
}