<?php

namespace Application\Service\Indicateur\Service\Validation;

use Application\Entity\Db\TypeIntervenant as TypeIntervenantEntity;
use Application\Entity\Db\TypeVolumeHoraire as TypeVolumeHoraireEntity;
use Application\Entity\Db\TypeValidation as TypeValidationEntity;
use Application\Entity\Db\WfEtape;
use Application\Service\Indicateur\AbstractIntervenantResultIndicateurImpl;
use Application\Service\Traits\IntervenantAwareTrait;
use Application\Service\Traits\ServiceReferentielAwareTrait;
use Application\Traits\TypeIntervenantAwareTrait;
use Application\Traits\TypeVolumeHoraireAwareTrait;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AttenteValidationRefRealisePermAutreCompIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    use IntervenantAwareTrait;
    use ServiceReferentielAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use TypeIntervenantAwareTrait;

    protected $singularTitlePattern = "%s permanent  a   clôturé la saisie de ses   services réalisés et est  en attente de validation de son  référentiel <em>%s</em> par d'autres composantes";
    protected $pluralTitlePattern   = "%s permanents ont clôturé la saisie de leurs services réalisés et sont en attente de validation de leur référentiel <em>%s</em> par d'autres composantes";
    
    /**
     * Témoin indiquant s'il faut que l'intervenant soit à l'étape concernée dans le WF pour être acceptable.
     * 
     * @var boolean
     */
    protected $findByWfEtapeCourante = true;
    
    /**
     * 
     * @param bool $appendStructure
     * @return string
     */
    public function getTitle($appendStructure = true)
    {
        $this->singularTitlePattern = sprintf(
                $this->singularTitlePattern,
                '%s', 
                $this->getTypeVolumeHoraire());
        $this->pluralTitlePattern   = sprintf(
                $this->pluralTitlePattern,   
                '%s', 
                $this->getTypeVolumeHoraire());
        
        return parent::getTitle($appendStructure);
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
                'intervenant/validation-referentiel-realise', 
                ['intervenant' => $result->getSourceCode()], 
                ['force_canonical' => true]);
    }
    
    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $qb = parent::getQueryBuilder()
                ->join("int.serviceReferentiel", "s")
                ->join("s.fonction", "f")
                ->join("s.volumeHoraireReferentiel", "vh")
                ->join("vh.typeVolumeHoraire", "tvh", Join::WITH, "tvh = :tvh")
                ->setParameter('tvh', $this->getTypeVolumeHoraire());
        
        /**
         * La saisie du réalisé de l'intervenant doit avoir été clôturée.
         */
        $qb
                ->join("int.validation", "clo")
                ->join("clo.typeValidation", "tvClo", Join::WITH, "tvClo.code = :tvCloCode")
                ->setParameter('tvCloCode', TypeValidationEntity::CODE_CLOTURE_REALISE);
        
        /**
         * L'intervenant doit être à l'étape concernée dans le WF.
         */
        if ($this->findByWfEtapeCourante) {
            $this->getServiceIntervenant()->finderByWfEtapeCourante($this->getWorkflowStepKey(), $qb);
        }
        
        /**
         * Filtrage par type d'intervenant.
         */
        $qb
                ->andWhere("ti = :type")
                ->setParameter('type', $this->getTypeIntervenant());
        
        /**
         * Filtrage par composante d'intervention.
         */
        if ($this->getStructure()) {
            $qb
                    ->andWhere("s.structure = :structure")
                    ->setParameter('structure', $this->getStructure());
            
            /**
             * Les volumes horaires effectués dans la composante d'intervention spécifiée doivent être validés.
             */
            $qb
                    ->join("vh.validation", "val")
                    ->andWhere("1 = pasHistorise(val)");
        }

        /**
         * Les autres composantes d'intervention que celle spécifiée ne doivent pas avoir validé.
         */
        $this->appendCriteriaValidationAutresComposantes($qb);

        /**
         * Eviction des données historisées.
         */
        $qb
                ->andWhere("1 = pasHistorise(s)")
                ->andWhere("1 = pasHistorise(f)")
                ->andWhere("1 = pasHistorise(vh)");

        $qb->orderBy("int.nomUsuel, int.prenom");

        return $qb;
    }
    
    private function appendCriteriaValidationAutresComposantes(QueryBuilder $qb)
    {
        $qbAutreComp = $this->getServiceServiceReferentiel()->getRepo()->createQueryBuilder("sAutreComp");
        $qbAutreComp
                ->join("sAutreComp.fonction", "fAutreComp")
                ->join("sAutreComp.volumeHoraireReferentiel", "vhAutreComp")
                ->join("vhAutreComp.typeVolumeHoraire", "tvhAutreComp", Join::WITH, "tvhAutreComp = :tvh")
                ->leftJoin("vhAutreComp.validation", "valAutreComp")
                ->andWhere("valAutreComp.id IS NULL")
                ->andWhere("sAutreComp.intervenant = int");
            
        if ($this->getStructure()) {
            $qbAutreComp->andWhere("sAutreComp.structure <> :structure");
        }
        else {
            // si aucune structure n'est spécifiée, on ne filtre pas par composante d'intervention
        }
        
        // Eviction des données historisées.
        $qbAutreComp
                ->andWhere("1 = pasHistorise(sAutreComp)")
                ->andWhere("1 = pasHistorise(fAutreComp)")
                ->andWhere("1 = pasHistorise(vhAutreComp)")
                ->andWhere("1 = pasHistorise(valAutreComp)");

        $dqlAutresComposantes = $qbAutreComp->getDQL();

        $qb->andWhere("EXISTS ($dqlAutresComposantes)");
        
        return $this;
    }

    /**
     * Retourne le type d'intervenant utile à cet indicateur.
     *
     * @return TypeIntervenantEntity
     */
    public function getTypeIntervenant()
    {
        if (null === $this->typeIntervenant) {
            $this->typeIntervenant =
                $this->getServiceLocator()->get('ApplicationTypeIntervenant')->getByCode(TypeIntervenantEntity::CODE_PERMANENT);
        }

        return $this->typeIntervenant;
    }

    /**
     * Retourne le type de volume horaire utile à cet indicateur.
     *
     * @return TypeVolumeHoraireEntity
     */
    public function getTypeVolumeHoraire()
    {
        if (null === $this->typeVolumeHoraire) {
            $this->typeVolumeHoraire = $this->getServiceLocator()->get('ApplicationTypeVolumeHoraire')->getRealise();
        }

        return $this->typeVolumeHoraire;
    }
    
    /**
     * 
     * @return string
     */
    protected function getWorkflowStepKey()
    {
        return WfEtape::CODE_REFERENTIEL_VALIDATION_REALISE;
    }
}
<?php

namespace Application\Service\Indicateur\Service\Validation;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AttenteValidationRefRealisePermAutreCompIndicateurImpl extends AttenteValidationRefRealisePermIndicateurImpl
{
    protected $singularTitlePattern = "%s %s a   clôturé la saisie de ses   services réalisés et est  en attente de validation de son  référentiel <em>%s</em> par d'autres composantes";
    protected $pluralTitlePattern   = "%s %s ont clôturé la saisie de leurs services réalisés et sont en attente de validation de leur référentiel <em>%s</em> par d'autres composantes";
    
    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $qb = parent::getQueryBuilder();
        
        /**
         * Toute autre composante que celle spécifiée.
         */
        if ($this->getStructure()) {
            $qb
                    ->andWhere("f.structure <> :structure")
                    ->setParameter('structure', $this->getStructure());
        }
        
        return $qb;
    }
}