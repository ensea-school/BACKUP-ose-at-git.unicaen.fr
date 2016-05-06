<?php

namespace Application\Service\Indicateur\Service\Plafond;

use Application\Entity\Db\TypeIntervenant;
use Application\Entity\Db\VIndicDepassRef;
use Application\Entity\Db\Interfaces\TypeVolumeHoraireAwareInterface;
use Application\Service\Indicateur\AbstractIntervenantResultIndicateurImpl;
use Application\Entity\Db\Traits\TypeVolumeHoraireAwareTrait;
use UnicaenApp\Util;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Zend\Stdlib\Hydrator\Filter\FilterInterface;
use Zend\Filter\Callback;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class PlafondRefDepasseAbstractIndicateurImpl extends AbstractIntervenantResultIndicateurImpl implements TypeVolumeHoraireAwareInterface
{
    use TypeVolumeHoraireAwareTrait;
    use \Application\Service\Traits\TypeVolumeHoraireAwareTrait;
    
    protected $singularTitlePattern = "%s intervenant a    un total Référentiel <em>%s</em> qui dépasse le plafond correspondant à son statut";
    protected $pluralTitlePattern   = "%s intervenants ont un total Référentiel <em>%s</em> qui dépasse le plafond correspondant à leur statut";

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
     * Retourne le filtre retournant l'intervenant correspondant à chaque item de résultat.
     *
     * @return FilterInterface
     */
    public function getResultItemIntervenantExtractor()
    {
        if (null === $this->resultItemIntervenantExtractor) {
            $this->resultItemIntervenantExtractor = new Callback(function(VIndicDepassRef $resultItem) {
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
            $this->resultItemFormatter = new Callback(function(VIndicDepassRef $resultItem) {
                $intervenant = $this->getResultItemIntervenantExtractor()->filter($resultItem);
                $out = sprintf("<strong>%s</strong> : %s <small>(n°%s, %s%s)</small>, total Référentiel = %s (plafond = %s)", 
                    $resultItem->getStructure(),
                    $intervenant,
                    $intervenant->getRouteParam(),
                    $intervenant->getStatut(),
                    $intervenant->getStatut()->estPermanent() ? ", " . $intervenant->getStructure() : null,
                    Util::formattedNumber($resultItem->getTotal()),
                    $resultItem->getPlafond());
                return $out;
            });
        }
        
        return $this->resultItemFormatter;
    }
    
    /**
     * 
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $annee = $this->getServiceContext()->getAnnee();
        
        // INDISPENSABLE si plusieurs requêtes successives sur VIndicDepassRef !
        $this->getEntityManager()->clear('Application\Entity\Db\VIndicDepassRef');
        
        $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\VIndicDepassRef')->createQueryBuilder("v");
        $qb
                ->addSelect("int, aff, si, str")
                ->join("v.structure", "str")
                ->join("v.intervenant", "int")
                ->join("int.structure", "aff")
                ->join("int.statut", "si")
                ->join("si.typeIntervenant", "ti")
                ->join("v.typeVolumeHoraire", "tvh", Join::WITH, "tvh.code = :codeTvh")
                ->andWhere("int.annee = :annee")
                ->setParameter("annee", $annee)
                ->setParameter('codeTvh', $this->getTypeVolumeHoraire()->getCode())
                ->andWhere("1 = pasHistorise(int)");
        
        if ($this->getStructure()) {
            /**
             * Permanents : ceux intervenant ou affectés dans la structure spécifiée.
             * Vacataires : ceux intervenant dans la structure spécifiée.
             */
            $where = "  ti.code = :codeTiPerm AND (str = :structure OR aff = :structure) OR "
                    . " ti.code = :codeTiVac  AND  str = :structure";
            $qb
                    ->andWhere($where)
                    ->setParameter('codeTiPerm', TypeIntervenant::CODE_PERMANENT)
                    ->setParameter('codeTiVac',  TypeIntervenant::CODE_EXTERIEUR)
                    ->setParameter('structure', $this->getStructure());
        }
        
        $qb->orderBy("str.libelleCourt, int.nomUsuel, int.prenom");
        
        return $qb;
    }
}