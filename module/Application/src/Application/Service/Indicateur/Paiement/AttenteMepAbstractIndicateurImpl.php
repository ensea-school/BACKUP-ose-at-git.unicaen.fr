<?php

namespace Application\Service\Indicateur\Paiement;

use Application\Entity\Db\TypeIntervenant as TypeIntervenantEntity;
use Application\Entity\Db\VIndicAttenteMep as VIndicAttenteMepEntity;
use Application\Service\Indicateur\AbstractIntervenantResultIndicateurImpl;
use Doctrine\ORM\QueryBuilder;
use Zend\Stdlib\Hydrator\Filter\FilterInterface;
use Zend\Filter\Callback;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class AttenteMepAbstractIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    use \Application\Entity\Db\Traits\TypeIntervenantAwareTrait;
    
    protected $singularTitlePattern = "%s %s  peut   faire l'objet d'une mise en paiement";
    protected $pluralTitlePattern   = "%s %s peuvent faire l'objet d'une mise en paiement";
    
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
                TypeIntervenantEntity::CODE_EXTERIEUR === $this->getTypeIntervenant()->getCode() ? "vacataire" : "permanent");
        $this->pluralTitlePattern   = sprintf(
                $this->pluralTitlePattern,   
                '%s', 
                TypeIntervenantEntity::CODE_EXTERIEUR === $this->getTypeIntervenant()->getCode() ? "vacataires" : "permanents");
        
        return parent::getTitle($appendStructure);
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
                'paiement/etat-demande-paiement', 
                ['force_canonical' => true]);
    }
    
    /**
     * 
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $this->initFilters();
        
        // INDISPENSABLE si plusieurs requêtes successives sur Intervenant !
        $this->getEntityManager()->clear('Application\Entity\Db\VIndicAttenteMep');
        
        $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\VIndicAttenteMep')->createQueryBuilder("v");
        $qb
            ->join("v.intervenant", "int")
            ->join("int.structure", "aff")
            ->join("int.statut", "si")
            ->andWhere("int.annee = :annee")
            ->setParameter("annee", $this->getServiceContext()->getAnnee());
        
        /**
         * L'intervenant doit posséder des heures complémentaire pouvant faire l'objet d'une (demande de) mise en paiement.
         */
        $qb
                ->addSelect("int, aff, si, str")
                ->join("v.structure", "str");
        
        /**
         * Type intervenant.
         */
        $qb
                ->andWhere("si.typeIntervenant = :type")
                ->setParameter('type', $this->getTypeIntervenant());
        
        /**
         * Composante d'intervention.
         */
        if ($this->getStructure()) {
            $qb
                    ->andWhere("v.structure = :structure")
                    ->setParameter('structure', $this->getStructure());
        }
        
        $qb->orderBy("str.libelleCourt, int.nomUsuel, int.prenom");
        
        return $qb;
    }
    
    /**
     * Activation du filtrage Doctrine sur l'historique.
     */
    protected function initFilters()
    {
        $this->getEntityManager()->getFilters()->enable('historique')->init([
            //'Application\Entity\Db\Structure',
            'Application\Entity\Db\Intervenant',
        ]);
    }

    /**
     * Retourne le filtre retournant l'intervenant correspondant à chaque item de résultat.
     *
     * @return FilterInterface
     */
    public function getResultItemIntervenantExtractor()
    {
        if (null === $this->resultItemIntervenantExtractor) {
            $this->resultItemIntervenantExtractor = new Callback(function(VIndicAttenteMepEntity $resultItem) {
                $intervenant = $resultItem->getIntervenant();
                return $intervenant;
            });
        }

        return $this->resultItemIntervenantExtractor;
    }
    
    /**
     * Retourne le filtre permettant de formater comme il se doit chaque item de résultat.
     * 
     * @return FilterInterface
     */
    public function getResultItemFormatter()
    {
        if (null === $this->resultItemFormatter) {
            $this->resultItemFormatter = new Callback(function(VIndicAttenteMepEntity $resultItem) {
                $intervenant = $this->getResultItemIntervenantExtractor()->filter($resultItem);
                $out = sprintf("<strong>%s</strong> : %s <small>(n°%s%s)</small>", 
                    $resultItem->getStructure(),
                    $intervenant,
                    $intervenant->getSourceCode(),
                    $intervenant->getStatut()->estPermanent() ? ", Affectation: " . $intervenant->getStructure() : null);
                return $out;
            });
        }
        
        return $this->resultItemFormatter;
    }
    
    /**
     * Retourne le type d'intervenant utile à cet indicateur.
     * 
     * @return TypeIntervenantEntity
     */
    abstract public function getTypeIntervenant();
}