<?php

namespace Application\Service\Indicateur\Paiement;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\TypeIntervenant as TypeIntervenantEntity;
use Application\Entity\Db\VIndicAttenteDemandeMep as VIndicAttenteDemandeMepEntity;
use Application\Service\Indicateur\AbstractIntervenantResultIndicateurImpl;
use Doctrine\ORM\QueryBuilder;
use Zend\Filter\Callback;
use Zend\Filter\FilterInterface;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class AttenteDemandeMepAbstractIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    use \Application\Traits\TypeIntervenantAwareTrait;
    
    protected $singularTitlePattern = "%s %s peut    faire l'objet d'une demande de mise en paiement";
    protected $pluralTitlePattern   = "%s %s peuvent faire l'objet d'une demande de mise en paiement";
    
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
    public function getResultUrl($result)
    {
        return $this->getHelperUrl()->fromRoute(
                'intervenant/demande-mise-en-paiement', 
                ['intervenant' => $result->getIntervenant()->getSourceCode()], 
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
        $this->getEntityManager()->clear('Application\Entity\Db\VIndicAttenteDemandeMep');
        
        $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\VIndicAttenteDemandeMep')->createQueryBuilder("v");
        $qb
                ->addSelect("int, aff, si, str")
                ->join("v.structure", "str")
                ->join("v.intervenant", "int")
                ->join("int.structure", "aff")
                ->join("int.statut", "si");
        
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
        $this->getEntityManager()->getFilters()->enable('historique')->init(
            [
                'Application\Entity\Db\Structure',
                'Application\Entity\Db\Intervenant',
            ],
            $this->getServiceContext()->getDateObservation()
        );
    }
    
    /**
     * Retourne le filtre permettant de formater comme il se doit chaque item de résultat.
     * 
     * @return FilterInterface
     */
    public function getResultFormatter()
    {
        if (null === $this->resultFormatter) {
            $this->resultFormatter = new Callback(function(VIndicAttenteDemandeMepEntity $resultItem) { 
                $out = sprintf("<strong>%s</strong> : %s <small>(n°%s%s)</small>", 
                        $resultItem->getStructure(), 
                        $i = $resultItem->getIntervenant(), 
                        $i->getSourceCode(),
                        $i->getStatut()->estPermanent() ? ", Affectation: " . $i->getStructure() : null);
                return $out;
            });
        }
        
        return $this->resultFormatter;
    }
    
    /**
     * Collecte et retourne les adresses mails de tous les intervenants retournés par cet indicateur.
     * 
     * @return array
     */
    public function getResultEmails()
    {
        $resultEmails = [];
        foreach ($this->getResult() as $r) { /* @var $r VIndicAttenteDemandeMepEntity */
            $intervenant = $r->getIntervenant();
            $resultEmails[$intervenant->getEmailPerso(true)] = $intervenant->getNomComplet();
        }
        
        return $resultEmails;
    }
    
    /**
     * Retourne le type d'intervenant utile à cet indicateur.
     * 
     * @return TypeIntervenantEntity
     */
    abstract public function getTypeIntervenant();
}