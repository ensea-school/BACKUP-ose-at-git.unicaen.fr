<?php

namespace Application\Service\Indicateur\Service;

use Application\Service\Indicateur\AbstractIntervenantResultIndicateurImpl;
use Application\Entity\Db\TypeIntervenant as TypeIntervenantEntity;
use Application\Entity\Db\TypeValidation as TypeValidationEntity;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of AttenteClotureRealisePerm
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class EnsRealisePermSaisieClotureeIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    protected $singularTitlePattern = "%s permanent  a   clôturé ses   enseignements <em>Réalisés</em>";
    protected $pluralTitlePattern   = "%s permanents ont clôturé leurs enseignements <em>Réalisés</em>";
    
    /**
     * Retourne l'URL de la page concernant une ligne de résultat de l'indicateur.
     * 
     * @param IntervenantEntity $result
     * @return string
     */
    public function getResultUrl($result)
    {
        return $this->getHelperUrl()->fromRoute(
                'intervenant/services-realises', 
                ['intervenant' => $result->getSourceCode()], 
                ['force_canonical' => true]);
    }
    
    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $this->initFilters();
        
        /**
         * Une validation de type "clôture du réalisé" doit exister.
         */
        $qb = parent::getQueryBuilder()
                ->join("int.validation", "v")
                ->join("v.typeValidation", "tv", Join::WITH, "tv.code = :tvCode")
                ->setParameter('tvCode', TypeValidationEntity::CODE_CLOTURE_REALISE);
        
        /**
         * Seuls les intervenants permanents nous intéressent.
         */
        $qb
                ->andWhere("ti.code = :tiCode")
                ->setParameter('tiCode', TypeIntervenantEntity::CODE_PERMANENT);
        
        /**
         * Composante d'intervenation.
         */
        if ($this->getStructure()) {
            $qb
                    ->join("int.service", "s")
                    ->join("s.elementPedagogique", "ep")
                    ->andWhere("ep.structure = :structure")
                    ->setParameter('structure', $this->getStructure());
        }
        
        $qb->orderBy("int.nomUsuel, int.prenom");
        
        return $qb;
    }
    
    /**
     * Activation du filtrage Doctrine sur l'historique.
     */
    protected function initFilters()
    {
        $this->getEntityManager()->getFilters()->enable('historique')->init(
            [
                'Application\Entity\Db\Validation',
                'Application\Entity\Db\Service',
                'Application\Entity\Db\ElementPedagogique',
            ],
            $this->getServiceContext()->getDateObservation()
        );
    }
}