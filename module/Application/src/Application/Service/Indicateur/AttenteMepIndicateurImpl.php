<?php

namespace Application\Service\Indicateur;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\VIndicAttenteMep as VIndicAttenteMepEntity;
use Doctrine\ORM\QueryBuilder;
use Zend\Filter\Callback;
use Zend\Filter\FilterInterface;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AttenteMepIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    protected $singularTitlePattern = "%s intervenant peut faire l'objet d'une mise en paiement";
    protected $pluralTitlePattern   = "%s intervenants peuvent faire l'objet d'une mise en paiement";
    
    /**
     * Retourne l'URL de la page concernant une ligne de résultat de l'indicateur.
     * 
     * @param IntervenantEntity $result
     * @return string
     */
    public function getResultUrl($result)
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
        // INDISPENSABLE si plusieurs requêtes successives sur Intervenant !
        $this->getEntityManager()->clear('Application\Entity\Db\VIndicAttenteMep');
        
        $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\VIndicAttenteMep')->createQueryBuilder("v");
        $qb
                ->join("v.intervenant", "int")
                ->join("int.structure", "aff")
                ->join("int.statut", "si");
        /**
         * L'intervenant doit posséder des heures complémentaire pouvant faire l'objet d'une (demande de) mise en paiement.
         */
        $qb
                ->addSelect("int, aff, si, str")
                ->join("v.structure", "str");
        
        if ($this->getStructure()) {
            $qb
                    ->andWhere("v.structure = :structure")
                    ->setParameter('structure', $this->getStructure());
        }
        
        $qb->orderBy("str.libelleCourt, int.nomUsuel, int.prenom");
        
        return $qb;
    }
    
    /**
     * Retourne le filtre permettant de formater comme il se doit chaque item de résultat.
     * 
     * @return FilterInterface
     */
    public function getResultFormatter()
    {
        if (null === $this->resultFormatter) {
            $this->resultFormatter = new Callback(function(VIndicAttenteMepEntity $resultItem) { 
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
}