<?php

namespace Application\Service\Indicateur\Service;

use Application\Entity\Db\TypeIntervenant as TypeIntervenantEntity;
use Application\Entity\Db\TypeValidation as TypeValidationEntity;
use Application\Entity\Db\Structure as StructureEntity;
use Application\Entity\Db\TypeVolumeHoraire as TypeVolumeHoraireEntity;
use Application\Service\Indicateur\AbstractIntervenantResultIndicateurImpl;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of AttenteClotureRealisePerm
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class EnsRealisePermSaisieNonClotureeIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    use \Application\Service\Traits\StructureAwareTrait;
    
    protected $singularTitlePattern = "%s permanent  n'a   pas clôturé la saisie de ses   services <em>Réalisés</em>";
    protected $pluralTitlePattern   = "%s permanents n'ont pas clôturé la saisie de leurs services <em>Réalisés</em>";
    
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
        /**
         * Aucune validation de type "clôture du réalisé" ne doit exister.
         */
        $selectCloture = 
                "SELECT v FROM Application\Entity\Db\Validation v " .
                "JOIN v.typeValidation tv WITH tv.code = :tvCode " .
                "WHERE v.intervenant = int " .
                "AND 1 = pasHistorise(v)";
        $qb = parent::getQueryBuilder()
                ->andWhere("NOT EXISTS ( $selectCloture )")
                ->setParameter('tvCode', TypeValidationEntity::CODE_CLOTURE_REALISE);
        
        /**
         * Intervenants permanents.
         */
        $qb
                ->andWhere("ti.code = :tiCode")
                ->setParameter('tiCode', TypeIntervenantEntity::CODE_PERMANENT);
        
        /**
         * Qui possèdent des enseignements OU du référentiel réalisés.
         */
        $selectEns = 
                "SELECT s FROM Application\Entity\Db\Service s " .
                "JOIN s.elementPedagogique ep WITH 1 = pasHistorise(ep) " .
                "JOIN s.volumeHoraire vh WITH 1 = pasHistorise(vh) " .
                "JOIN vh.typeVolumeHoraire tvh WITH tvh.code = :tvhCode " .
                "WHERE s.intervenant = int " .
                "AND 1 = pasHistorise(s)";
        $selectRef = 
                "SELECT sr FROM Application\Entity\Db\ServiceReferentiel sr " .
                "JOIN sr.fonction f WITH 1 = pasHistorise(f) " .
                "JOIN sr.volumeHoraireReferentiel vhr WITH 1 = pasHistorise(vhr) " .
                "JOIN vhr.typeVolumeHoraire tvhr WITH tvhr.code = :tvhCode " .
                "WHERE sr.intervenant = int " .
                "AND 1 = pasHistorise(sr)";

        /**
         * Composante d'intervention éventuelle.
         */
        if ($this->getStructure()) {
            $selectEns .= "AND ep.structure = :structure ";
            $selectRef .= "AND (sr.structure = :structure OR (sr.structure = :etab AND int.structure = :structure)) ";
            $qb
                    ->setParameter('structure', $this->getStructure())
                    ->setParameter('etab', $this->getStructureEtablissement());
        }
        
        $qb
                ->andWhere("(EXISTS ( $selectEns ) OR EXISTS ( $selectRef ))")
                ->setParameter("tvhCode", TypeVolumeHoraireEntity::CODE_REALISE);
        
        $qb->orderBy("int.nomUsuel, int.prenom");
        
        return $qb;
    }
    
    /**
     * Retourne la structure correspondant à l'établissement (structure racine).
     * 
     * @return StructureEntity
     */
    private function getStructureEtablissement()
    {
        return $this->getServiceStructure()->getRacine();
    }
}