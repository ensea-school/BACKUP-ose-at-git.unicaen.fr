<?php

namespace Application\Service\Indicateur\Service\Plafond;

use Application\Entity\Db\TypeIntervenant;
use Application\Entity\Db\VIndicDepassHcHorsRemuFc;
use Application\Interfaces\TypeVolumeHoraireAwareInterface;
use Application\Service\Indicateur\AbstractIntervenantResultIndicateurImpl;
use Common\Util;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Zend\Stdlib\Hydrator\Filter\FilterInterface;
use Zend\Validator\Callback;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class PlafondHcHorsRemuFcDepasseAbstractIndicateurImpl extends AbstractIntervenantResultIndicateurImpl implements TypeVolumeHoraireAwareInterface
{
    use \Application\Traits\TypeVolumeHoraireAwareTrait;
    
    protected $singularTitlePattern = "%s intervenant a    un total HC hors rémunération FC D713-60 <em>%s Saisi</em> qui dépasse le plafond correspondant à son statut";
    protected $pluralTitlePattern   = "%s intervenants ont un total HC hors rémunération FC D713-60 <em>%s Saisi</em> qui dépasse le plafond correspondant à leur statut";

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
     * Retourne le filtre permettant de formater comme il se doit chaque item de résultat.
     * 
     * @return FilterInterface
     */
    public function getResultFormatter()
    {
        if (null === $this->resultFormatter) {
            $this->resultFormatter = new Callback(function(VIndicDepassHcHorsRemuFc $resultItem) { 
                $out = sprintf("<strong>%s</strong> : %s <small>(n°%s, %s%s)</small>, total HC = %s (plafond = %s)", 
                        $resultItem->getStructure(), 
                        $i = $resultItem->getIntervenant(), 
                        $i->getSourceCode(),
                        $i->getStatut(),
                        $i->getStatut()->estPermanent() ? ", " . $i->getStructure() : null,
                        Util::formattedHeures($resultItem->getTotal()),
                        $resultItem->getPlafond());
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
        foreach ($this->getResult() as $r) { /* @var $r VIndicDepassHcHorsRemuFc */
            $intervenant = $r->getIntervenant();
            $resultEmails[$intervenant->getEmailPerso(true)] = $intervenant->getNomComplet();
        }
        
        return $resultEmails;
    }
    
    /**
     * 
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $this->initFilters();
        
        $annee = $this->getServiceContext()->getAnnee();
        
        // INDISPENSABLE si plusieurs requêtes successives sur VIndicDepassHcHorsRemuFc !
        $this->getEntityManager()->clear('Application\Entity\Db\VIndicDepassHcHorsRemuFc');
        
        $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\VIndicDepassHcHorsRemuFc')->createQueryBuilder("v");
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
                ->setParameter('codeTvh', $this->getTypeVolumeHoraire()->getCode());
        
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
    
    /**
     * Activation du filtrage Doctrine sur l'historique.
     */
    protected function initFilters()
    {
        $this->getEntityManager()->getFilters()->enable('historique')->init(
            [
                'Application\Entity\Db\Intervenant',
                'Application\Entity\Db\Structure',
            ],
            $this->getServiceContext()->getDateObservation()
        );
    }
}