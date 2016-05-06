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
class DonneesPersoModifIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    protected $singularTitlePattern = "%s vacataire a modifié des informations importantes dans ses données personnelles";
    protected $pluralTitlePattern   = "%s vacataires ont modifié des informations importantes dans leurs données personnelles";
    
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
                ['action' => 'result-item-donnees-perso-modif', 'intervenant' => $result->getRouteParam()],
                ['force_canonical' => true]);
    }
    
    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $qb = parent::getQueryBuilder()
            ->join("int.indicModifDossier", "t", Join::WITH, "1 = pasHistorise(t)");
        
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
        
//        print_r($qb->getQuery()->getSQL());

        return $qb;
    }
}