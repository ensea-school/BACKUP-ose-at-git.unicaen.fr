<?php

namespace Application\Service\Indicateur\Service\Affectation;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\StatutIntervenant as StatutIntervenantEntity;
use Application\Service\Indicateur\AbstractIntervenantResultIndicateurImpl;
use Application\Service\StatutIntervenant as StatutIntervenantService;
use Application\Entity\Db\Traits\TypeVolumeHoraireAwareTrait;
use Doctrine\ORM\QueryBuilder;

/**
 *
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class IntervAffectAutreIntervMemeAbstractIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    use TypeVolumeHoraireAwareTrait;
    use \Application\Service\Traits\TypeVolumeHoraireAwareTrait;
    
    protected $singularTitlePattern = "%s intervenant  '%s' affecté  dans une autre structure a   des enseignements <em>%ss Validés</em> dans ma structure (%s)";
    protected $pluralTitlePattern   = "%s intervenants '%s' affectés dans une autre structure ont des enseignements <em>%ss Validés</em> dans ma structure (%s)";
    protected $statutIntervenant;
    
    /**
     * @return StatutIntervenantEntity
     */
    abstract protected function getStatutIntervenant();
    
    /**
     * 
     * @param bool $appendStructure
     * @return string
     */
    public function getTitle($appendStructure = true)
    {
        $this->singularTitlePattern = sprintf($this->singularTitlePattern, '%s', $this->getStatutIntervenant(), $this->getTypeVolumeHoraire(), $this->getStructure());
        $this->pluralTitlePattern   = sprintf($this->pluralTitlePattern,   '%s', $this->getStatutIntervenant(), $this->getTypeVolumeHoraire(), $this->getStructure());
        
        return parent::getTitle(false);
    }
    
    /**
     * Retourne l'URL de la page concernant une ligne de résultat de l'indicateur.
     * 
     * @param IntervenantEntity $result
     * @return string
     */
    public function getResultItemUrl($result)
    {
        return $this->getHelperUrl()->fromRoute(
                'intervenant/services', 
                ['intervenant' => $result->getSourceCode()], 
                ['force_canonical' => true]);
    }
    
    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $exists = "SELECT s FROM Application\Entity\Db\Service s "
                . "JOIN s.intervenant i WITH 1 = pasHistorise(i) "
                . "JOIN i.structure sa "
                . "JOIN s.elementPedagogique ep WITH 1 = pasHistorise(ep) " // NB: on ne s'intéresse pas aux enseignements fait dans un autre établissement (structureAff)
                . "JOIN ep.structure se "
                . "JOIN s.volumeHoraire vh WITH 1 = pasHistorise(vh) "
                . "JOIN vh.typeVolumeHoraire tvh WITH tvh = :tvh "
                . "JOIN vh.validation v WITH 1 = pasHistorise(v) " // les volumes horaires doivent être validés
                . "WHERE s.intervenant = int "
                . "AND ep.structure = :structure "
                . "AND 1 = pasHistorise(s) ";  // intervention dans la structure spécifiée.
        
        $qb = parent::getQueryBuilder()
                ->andWhere("EXISTS ($exists)")
                ->setParameter('tvh', $this->getTypeVolumeHoraire());
        
        if ($this->getStatutIntervenant()) {
            $qb
                    ->andWhere("si = :statut")
                    ->setParameter('statut', $this->getStatutIntervenant()); 
        }
        
        /**
         * Intervenants affectés dans une autre structure que celle spécifiée.
         */
        $qb
                ->andWhere("int.structure <> :structure")
                ->setParameter('structure', $this->getStructure());
        
        $qb->orderBy("int.nomUsuel, int.prenom");
        
        return $qb;
    }
    
    /**
     * @return StatutIntervenantService
     */
    protected function getServiceStatutIntervenant()
    {
        return $this->getServiceLocator()->get('ApplicationStatutIntervenant');
    }
}