<?php

namespace Application\Service\Indicateur\Agrement;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\TypeAgrement;
use Application\Entity\Db\TypeIntervenant;
use Application\Entity\Db\WfEtape;
use Application\Service\Indicateur\AbstractIntervenantResultIndicateurImpl;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class AttenteAgrementAbstractIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    protected $singularTitlePattern = "%s vacataire est en attente d'agrément du %s";
    protected $pluralTitlePattern   = "%s vacataires sont en attente d'agrément du %s";
    protected $codeTypeAgrement     = TypeAgrement::CODE_CONSEIL_RESTREINT;
    protected $codeEtape            = WfEtape::CODE_CONSEIL_RESTREINT;

    /**
     * 
     * @param bool $appendStructure
     * @return string
     */
    public function getTitle($appendStructure = true)
    {
        $this->singularTitlePattern = sprintf($this->singularTitlePattern, '%s', $this->getTypeAgrement());
        $this->pluralTitlePattern   = sprintf($this->pluralTitlePattern,   '%s', $this->getTypeAgrement());
        
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
                'intervenant/agrement/liste', 
                ['intervenant'  => $result->getSourceCode(), 'typeAgrement' => $this->getTypeAgrement()->getId()], 
                ['force_canonical' => true]);
    }
    
    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $this->initFilters();
        
        $qb = parent::getQueryBuilder()
                ->andWhere("ti.code = :type")->setParameter('type', TypeIntervenant::CODE_EXTERIEUR);
        
        /**
         * Dans la progression de l'intervenant dans le WF, toutes les étapes précédant l'étape 
         * "Agrément Conseil Restreint" doivent avoir été franchies
         */
        $qb
                ->join("int.wfIntervenantEtape", "p", Join::WITH, "p.courante = 1")
                ->join("p.etape", "e", Join::WITH, "e.code = :codeEtape")
                ->setParameter('codeEtape', $this->codeEtape);
        
        /**
         * L'intervenant doit intervenir dans la structure spécifiée éventuelle.
         */
        if ($this->getStructure()) {
            $qb
                    ->join("int.service", "s")
                    ->join("s.elementPedagogique", "ep")
                    ->join("s.volumeHoraire", "vh")
                    ->join("vh.typeVolumeHoraire", "tvh", Join::WITH, "tvh = :tvh")
                    ->andWhere( "ep.structure = :structure")
                    ->setParameter('tvh', $this->getServiceLocator()->get('ApplicationTypeVolumeHoraire')->getPrevu())
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
                'Application\Entity\Db\Service',
                'Application\Entity\Db\VolumeHoraire',
            ],
            $this->getServiceContext()->getDateObservation()
        );
    }
    
    protected $typeAgrement;
    
    /**
     * Retourne le type d'agrément concerné.
     * 
     * @return TypeAgrement
     */
    public function getTypeAgrement()
    {
        if (null === $this->typeAgrement) {
            $service            = $this->getServiceLocator()->get('ApplicationTypeAgrement');
            $qb                 = $service->finderByCode($this->codeTypeAgrement);
            $this->typeAgrement = $qb->getQuery()->getOneOrNullResult();
        }
        
        return $this->typeAgrement;
    }
}