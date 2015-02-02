<?php

namespace Application\Service\Indicateur;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Doctrine\ORM\QueryBuilder;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AttenteValidationEnsIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    protected $singularTitlePattern = "%s vacataire est en attente de validation de ses enseignements";
    protected $pluralTitlePattern   = "%s vacataires sont en attente de validation de leurs enseignements";
    
    /**
     * Retourne l'URL de la page concernant une ligne de résultat de l'indicateur.
     * 
     * @param IntervenantEntity $result
     * @return string
     */
    public function getResultUrl($result)
    {
        return $this->getHelperUrl()->fromRoute(
                'intervenant/validation-service', 
                ['intervenant' => $result->getSourceCode()], 
                ['force_canonical' => true]);
    }
    
    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $qb = parent::getQueryBuilder()
                ->join("int.service", "s")
                ->join("s.volumeHoraire", "vh");
        
        /**
         * Les vacataires.
         */
        $qb->andWhere("ti.code = :type")->setParameter('type', \Application\Entity\Db\TypeIntervenant::CODE_EXTERIEUR);
        
        if ($this->getStructure()) {
            $qb
                    ->andWhere("s.structureEns = :structure")
                    ->setParameter('structure', $this->getStructure());
        }
        
        /**
         * Les volumes horaires ne doivent pas être validés.
         */
        $qb->andWhere("vh.validation IS EMPTY");
        
        $qb->orderBy("int.nomUsuel, int.prenom");
        
        return $qb;
    }
}