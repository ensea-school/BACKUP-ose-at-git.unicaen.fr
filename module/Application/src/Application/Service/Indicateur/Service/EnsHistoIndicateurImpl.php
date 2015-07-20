<?php

namespace Application\Service\Indicateur\Service;

use Application\Entity\Db\Service;
use Application\Entity\Db\TypeIntervenant;
use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Service\Indicateur\AbstractIntervenantResultIndicateurImpl;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Zend\Stdlib\Hydrator\Filter\FilterInterface;
use Zend\Filter\Callback;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class EnsHistoIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    use \Application\Traits\TypeVolumeHoraireAwareTrait,
        \Application\Service\Traits\TypeVolumeHoraireAwareTrait
    ;
    
    protected $singularTitlePattern = "%s intervenant  a   saisi des enseignements dont l'étape, l'élément pédagogique ou la période a disparu";
    protected $pluralTitlePattern   = "%s intervenants ont saisi des enseignements dont l'étape, l'élément pédagogique ou la période a disparu";
    
    public function getTypeVolumeHoraire()
    {
        if (null === $this->typeVolumeHoraire) {
            $this->typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getPrevu();
        }
        
        return $this->typeVolumeHoraire;
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
                'intervenant/services', 
                ['intervenant' => $result->getSourceCode()], 
                ['force_canonical' => true]);
    }
    
    /**
     * Retourne le filtre permettant de formater comme il se doit chaque item de résultat.
     * 
     * @return FilterInterface
     */
    public function getResultItemFormatter()
    {
        if (null === $this->resultItemFormatter) {
            $this->resultItemFormatter = new Callback(function(IntervenantEntity $resultItem) {
                $details = [];
                foreach ($resultItem->getService() as $service) { /* @var $service Service */
                    $ep      = $service->getElementPedagogique();
                    $etape   = $ep->getEtape();
                    $periode = $ep->getPeriode();
                    
                    $details[] = implode(' + ', array_filter([
                        $etape->getHistoDestruction()               ? "Étape &laquo; $etape &raquo;" : null,
                        $ep->getHistoDestruction()                  ? "Élément &laquo; $ep &raquo;"  : null,
                        $periode && $periode->getHistoDestruction() ? "Période &laquo; $periode &raquo;"  : null,
                    ]));
                }
                $out = sprintf("%s <small>(n°%s, %s%s)</small> %s", 
                        $i = $resultItem, 
                        $i->getSourceCode(),
                        $i->getStatut(),
                        $i->getStatut()->estPermanent() ? ", " . $i->getStructure() : null,
                        "<ul><li>" . implode("</li><li>", $details) . "</li></ul>");
                return $out;
            });
        }
        
        return $this->resultItemFormatter;
    }
    
    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $qb = parent::getQueryBuilder()
            ->addSelect("s, se, e, ep")
            ->join("int.service", "s")
            ->join("s.elementPedagogique", "ep")
            ->join("ep.structure", "se")
            ->join("ep.etape", "e")
            ->leftJoin("ep.periode", "p")
            ->join("s.volumeHoraire", "vh")
            ->join("vh.typeVolumeHoraire", "tvh", Join::WITH, "tvh = :tvh")
            ->setParameter('tvh', $this->getTypeVolumeHoraire())
            ->andWhere("1 = pasHistorise(s)")
            ->andWhere("1 = pasHistorise(vh)");

        /**
         * l'étape AINSI QUE tous ces éléments sont historisés
         * OU
         * l'élément pédagogique est historisé
         * OU
         * la période éventuelle est historisée
         */
        $whereHistos = <<<EOS
(
    1 <> compriseEntre(e.histoCreation, e.histoDestruction)
    AND NOT EXISTS(
      SELECT
        cp.id
      FROM
        Application\Entity\Db\CheminPedagogique cp
        JOIN Application\Entity\Db\ElementPedagogique ep2 WITH ep2 = cp.elementPedagogique
      WHERE
        1 = compriseEntre(cp.histoCreation, cp.histoDestruction)
        AND 1 = compriseEntre(ep2.histoCreation, ep2.histoDestruction)
        AND cp.etape = e
        AND ep2.annee = :fbh_annee
    )
)
OR
(
    1 <> compriseEntre(ep.histoCreation, ep.histoDestruction)
)
OR 
(
    p.id IS NOT NULL AND 1 <> compriseEntre(p.histoCreation, p.histoDestruction)
) 
EOS;
        $qb
                ->andWhere($whereHistos)
                ->setParameter('fbh_annee', $this->getServiceContext()->getAnnee());
        
        if ($this->getStructure()) {
            /**
             * Permanents : ceux intervenant ou affectés dans la structure spécifiée.
             * Vacataires : ceux intervenant dans la structure spécifiée.
             */
            $where = "  ti.code = :codeTiPerm AND (se = :structure OR str = :structure) OR "
                    . " ti.code = :codeTiVac  AND  se = :structure";
            $qb
                    ->andWhere($where)
                    ->setParameter('codeTiPerm', TypeIntervenant::CODE_PERMANENT)
                    ->setParameter('codeTiVac',  TypeIntervenant::CODE_EXTERIEUR)
                    ->setParameter('structure', $this->getStructure());
        }
        
        $qb->orderBy("int.nomUsuel, int.prenom");

        return $qb;

        /**
         * @todo Créer et exploiter la vue suivante équivalente :
         *
            SELECT to_char(i.id)||to_char(s.id)||to_char(ep.id)||to_char(e.id)||to_char(p.id)||to_char(tvh.id) id,
              i.id intervenant_id, s.id service_id, ep.id element_pedagogique_id, e.id etape_id, p.id periode_id, tvh.id type_volume_horaire_id
            FROM  INTERVENANT i
            INNER JOIN SERVICE s ON i.ID = s.INTERVENANT_ID AND 1 = OSE_DIVERS.COMPRISE_ENTRE(s.HISTO_CREATION,s.HISTO_DESTRUCTION)
            INNER JOIN ELEMENT_PEDAGOGIQUE ep ON   s.ELEMENT_PEDAGOGIQUE_ID = ep.ID AND 1 = OSE_DIVERS.COMPRISE_ENTRE(ep.HISTO_CREATION,ep.HISTO_DESTRUCTION)
            INNER JOIN ETAPE e ON  ep.ETAPE_ID = e.ID
            INNER JOIN VOLUME_HORAIRE vh ON s.ID = vh.SERVICE_ID AND 1 = OSE_DIVERS.COMPRISE_ENTRE(vh.HISTO_CREATION, vh.HISTO_DESTRUCTION)
            INNER JOIN TYPE_VOLUME_HORAIRE tvh ON vh.TYPE_VOLUME_HORAIRE_ID = tvh.ID
            LEFT JOIN PERIODE p ON ep.PERIODE_ID = p.ID
            WHERE
              1 = OSE_DIVERS.COMPRISE_ENTRE(i.HISTO_CREATION, i.HISTO_DESTRUCTION, NULL)
              AND
              (
                -- l'étape AINSI QUE tous ces éléments sont historisés
                (
                  1 <> OSE_DIVERS.COMPRISE_ENTRE(e.HISTO_CREATION, e.HISTO_DESTRUCTION)
                  AND NOT EXISTS (
                    SELECT * FROM CHEMIN_PEDAGOGIQUE cp
                    INNER JOIN ELEMENT_PEDAGOGIQUE ep2 ON ep2.ID = cp.ELEMENT_PEDAGOGIQUE_ID  AND 1 = OSE_DIVERS.COMPRISE_ENTRE(ep2.HISTO_CREATION, ep2.HISTO_DESTRUCTION)
                    WHERE 1 = OSE_DIVERS.COMPRISE_ENTRE(cp.HISTO_CREATION, cp.HISTO_DESTRUCTION)
                    AND cp.ETAPE_ID = e.ID
                    AND ep2.ANNEE_ID = i.annee_id
                  )
                )
                OR
                -- l'élément pédagogique est historisé
                (
                  1 <> OSE_DIVERS.COMPRISE_ENTRE(ep.HISTO_CREATION, ep.HISTO_DESTRUCTION)
                )
                OR
                -- la période éventuelle est historisée
                (
                  p.ID IS NOT NULL AND 1 <> OSE_DIVERS.COMPRISE_ENTRE(p.HISTO_CREATION, p.HISTO_DESTRUCTION)
                )
              )
            ORDER BY i.NOM_USUEL ASC, i.PRENOM ASC
            ;
         *
         */
    }
}
