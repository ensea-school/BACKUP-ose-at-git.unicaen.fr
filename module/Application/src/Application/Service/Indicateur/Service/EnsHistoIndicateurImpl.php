<?php

namespace Application\Service\Indicateur\Service;

use Application\Entity\Db\Service;
use Application\Entity\Db\TypeIntervenant;
use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Service\Indicateur\AbstractIntervenantResultIndicateurImpl;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Zend\Stdlib\Hydrator\Filter\FilterInterface;
use Zend\Filter\Callback;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class EnsHistoIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    use \Application\Traits\TypeVolumeHoraireAwareTrait,
        \Application\Service\Traits\TypeVolumeHoraireAwareTrait
    ;
    
    protected $singularTitlePattern = "%s intervenant  a   saisi des enseignements dont l'étape, l'élément pédagogique ou la période a disparu";
    protected $pluralTitlePattern   = "%s intervenants ont saisi des enseignements dont l'étape, l'élément pédagogique ou la période a disparu";
    
    public function getTypeVolumeHoraire()
    {
        if (null === $this->typeVolumeHoraire) {
            $this->typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getPrevu();
        }
        
        return $this->typeVolumeHoraire;
    }
    
    /**
     * Initialisation des filtres Doctrine pour les historique.
     * Objectif : laisser passer les enregistrements passés en historique pour mettre en évidence ensuite les erreurs éventuelles
     * (services sur des enseignements fermés, etc.)
     */
    protected function initFilters()
    {
        $this->getEntityManager()->getFilters()->enable('historique')->init(
            [
                'Application\Entity\Db\Service',
                'Application\Entity\Db\VolumeHoraire',
            ],
            $this->getServiceContext()->getDateObservation()
        );
    }
    
    /**
     * Retourne l'URL de la page concernant une ligne de résultat de l'indicateur.
     * 
     * @param IntervenantEntity $result
     * @return string
     */
    public function getResultUrl($result)
    {
        return $this->getHelperUrl()->fromRoute(
                'intervenant/services', 
                ['intervenant' => $result->getSourceCode()], 
                ['force_canonical' => true]);
    }
    
    /**
     * Retourne le filtre permettant de formater comme il se doit chaque item de résultat.
     * 
     * @return FilterInterface
     */
    public function getResultFormatter()
    {
        if (null === $this->resultFormatter) {
            $this->resultFormatter = new Callback(function(IntervenantEntity $resultItem) { 
                $details = [];
                foreach ($resultItem->getService() as $service) { /* @var $service Service */
                    $ep      = $service->getElementPedagogique();
                    $etape   = $ep->getEtape();
                    $periode = $ep->getPeriode();
                    
                    $details[] = implode(' + ', array_filter([
                        $etape->getHistoDestruction()               ? "Étape &laquo; $etape &raquo;" : null,
                        $ep->getHistoDestruction()                  ? "Élément &laquo; $ep &raquo;"  : null,
                        $periode && $periode->getHistoDestruction() ? "Période &laquo; $periode &raquo;"  : null,
                    ]));
                }
                $out = sprintf("%s <small>(n°%s, %s%s)</small> %s", 
                        $i = $resultItem, 
                        $i->getSourceCode(),
                        $i->getStatut(),
                        $i->getStatut()->estPermanent() ? ", " . $i->getStructure() : null,
                        "<ul><li>" . implode("</li><li>", $details) . "</li></ul>");
                return $out;
            });
        }
        
        return $this->resultFormatter;
    }
    
    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $this->initFilters();
        
        $whereHistos = 
                "(e.histoDestructeur  IS NOT NULL OR e.histoDestruction  IS NOT NULL) "
           . "OR (ep.histoDestructeur IS NOT NULL OR ep.histoDestruction IS NOT NULL) "
           . "OR (p.id IS NOT NULL AND (p.histoDestructeur  IS NOT NULL OR p.histoDestruction  IS NOT NULL)) ";
        
        $qb = parent::getQueryBuilder()
                ->addSelect("s, se, e, ep")
                ->join("int.service", "s")
                ->join("s.elementPedagogique", "ep")
                ->join("ep.structure", "se")
                ->join("ep.etape", "e")
                ->leftJoin("ep.periode", "p")
                ->join("s.volumeHoraire", "vh")
                ->join("vh.typeVolumeHoraire", "tvh", Join::WITH, "tvh = :tvh")
                ->andWhere($whereHistos)
                ->setParameter('tvh', $this->getTypeVolumeHoraire());
        
        if ($this->getStructure()) {
            /**
             * Permanents : ceux intervenant ou affectés dans la structure spécifiée.
             * Vacataires : ceux intervenant dans la structure spécifiée.
             */
            $where = "  ti.code = :codeTiPerm AND (se = :structure OR str = :structure) OR "
                    . " ti.code = :codeTiVac  AND  se = :structure";
            $qb
                    ->andWhere($where)
                    ->setParameter('codeTiPerm', TypeIntervenant::CODE_PERMANENT)
                    ->setParameter('codeTiVac',  TypeIntervenant::CODE_EXTERIEUR)
                    ->setParameter('structure', $this->getStructure());
        }
        
        $qb->orderBy("int.nomUsuel, int.prenom");
//        print_r($qb->getQuery()->getSQL());
        
        return $qb;
    }
}
