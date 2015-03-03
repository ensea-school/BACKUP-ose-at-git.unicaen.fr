<?php

namespace Application\Service\Indicateur;

use Application\Entity\Db\EtatVolumeHoraire;
use Application\Interfaces\TypeVolumeHoraireAwareInterface;
use Application\Traits\TypeVolumeHoraireAwareTrait;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class PlafondHetdDepasseAbstractIndicateurImpl extends AbstractIntervenantResultIndicateurImpl implements TypeVolumeHoraireAwareInterface
{
    use TypeVolumeHoraireAwareTrait;
    
    protected $singularTitlePattern = "%s intervenant a    un total HETD <em>%s saisi</em> qui dépasse le plafond correspondant à son statut";
    protected $pluralTitlePattern   = "%s intervenants ont un total HETD <em>%s saisi</em> qui dépasse le plafond correspondant à leur statut";

    /**
     * 
     * @param bool $appendStructure
     * @return string
     */
    public function getTitle($appendStructure = true)
    {
        $this->singularTitlePattern = sprintf($this->singularTitlePattern, '%s', $this->getTypeVolumeHoraire());
        $this->pluralTitlePattern   = sprintf($this->pluralTitlePattern,   '%s', $this->getTypeVolumeHoraire());
        
        return parent::getTitle($appendStructure);
    }
    
    /**
     * 
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $annee = $this->getContextProvider()->getGlobalContext()->getAnnee();
        
        /**
         * L'intervenant doit avoir un plafond HETD.
         */
        $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\Intervenant')->createQueryBuilder("int");
        $qb
                ->join("int.structure", "aff")
                ->join("int.statut", "si", Join::WITH, "si.maximumHETD IS NOT NULL AND si.maximumHETD > 0");
     
        /**
         * Le total HETD doit dépasser le plafond HETD.
         */
        $qb
                ->join("int.formuleResultat", "fr", Join::WITH, "fr.annee = :annee")
                ->join("fr.typeVolumeHoraire", "tvh", Join::WITH, "tvh.code = :codeTvh")
                ->join("fr.etatVolumeHoraire", "evh", Join::WITH, "evh.code = :codeEvh")
                ->setParameter("annee", $annee)
                ->setParameter('codeTvh', $this->getTypeVolumeHoraire()->getCode())
                ->setParameter("codeEvh", EtatVolumeHoraire::CODE_SAISI)
                ->andWhere("fr.enseignements > si.maximumHETD");
        
        if ($this->getStructure()) {
            $qb
                    ->join("int.service", "s", Join::WITH, "s.annee = :annee")
                    ->andWhere("s.structureEns = :structure")
                    ->setParameter('annee', $annee)
                    ->setParameter('structure', $this->getStructure());
        }
        
        $qb->orderBy("int.nomUsuel, int.prenom");
//        print_r($qb->getQuery()->getSQL());
        
        return $qb;
    }
}