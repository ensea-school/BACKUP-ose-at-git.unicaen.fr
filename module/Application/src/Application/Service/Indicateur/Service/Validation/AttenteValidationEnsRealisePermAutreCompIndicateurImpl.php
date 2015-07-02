<?php

namespace Application\Service\Indicateur\Service\Validation;

use Application\Entity\Db\TypeIntervenant as TypeIntervenantEntity;
use Application\Entity\Db\TypeVolumeHoraire as TypeVolumeHoraireEntity;
use Application\Entity\Db\TypeValidation as TypeValidationEntity;
use Application\Entity\Db\WfEtape;
use Application\Service\Indicateur\AbstractIntervenantResultIndicateurImpl;
use Application\Service\Traits\IntervenantAwareTrait;
use Application\Service\Traits\ServiceAwareTrait;
use Application\Traits\TypeIntervenantAwareTrait;
use Application\Traits\TypeVolumeHoraireAwareTrait;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

/**
 *
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AttenteValidationEnsRealisePermAutreCompIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    use IntervenantAwareTrait;
    use ServiceAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use TypeIntervenantAwareTrait;

    protected $singularTitlePattern = "%s permanent  a   clôturé la saisie de ses   services réalisés et est  en attente de validation de ses   enseignements <em>%s</em> par d'autres composantes";
    protected $pluralTitlePattern   = "%s permanents ont clôturé la saisie de leurs services réalisés et sont en attente de validation de leurs enseignements <em>%s</em> par d'autres composantes";

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
            $this->getTypeVolumeHoraire());
        $this->pluralTitlePattern   = sprintf(
            $this->pluralTitlePattern,
            '%s',
            $this->getTypeVolumeHoraire());

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
            'intervenant/validation-service-realise',
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
            ->join("s.elementPedagogique", "ep")
            ->join("s.volumeHoraire", "vh")
            ->join("vh.typeVolumeHoraire", "tvh", Join::WITH, "tvh = :tvh")
            ->setParameter('tvh', $this->getTypeVolumeHoraire());

        /**
         * La saisie du réalisé de l'intervenant doit avoir été clôturée.
         */
        $qb
            ->join("int.validation", "clo")
            ->join("clo.typeValidation", "tvClo", Join::WITH, "tvClo.code = :tvCloCode")
            ->setParameter('tvCloCode', TypeValidationEntity::CODE_CLOTURE_REALISE);

        /**
         * Filtrage par type d'intervenant.
         */
        $qb
            ->andWhere("ti = :type")
            ->setParameter('type', $this->getTypeIntervenant());

        /**
         * Filtrage par composante d'intervention.
         */
        if ($this->getStructure()) {
            $qb
                ->andWhere("ep.structure = :structure")
                ->setParameter('structure', $this->getStructure());

            /**
             * Les volumes horaires effectués dans la composante d'intervention spécifiée doivent être validés.
             */
            $qb
                ->join("vh.validation", "val")
                ->andWhere("1 = pasHistorise(val)");
        }

        /**
         * Les autres composantes d'intervention que celle spécifiée ne doivent pas avoir validé.
         */
        $this->appendCriteriaValidationAutresComposantes($qb);

        /**
         * Eviction des données historisées.
         */
        $qb
            ->andWhere("1 = pasHistorise(s)")
            ->andWhere("1 = pasHistorise(ep)")
            ->andWhere("1 = pasHistorise(vh)");

        $qb->orderBy("int.nomUsuel, int.prenom");

        return $qb;
    }

    private function appendCriteriaValidationAutresComposantes(QueryBuilder $qb)
    {
        $qbAutreComp = $this->getServiceService()->getRepo()->createQueryBuilder("sAutreComp");

        if ($this->getStructure()) {
            $qbAutreComp->join("sAutreComp.elementPedagogique", "epAutreComp", Join::WITH, "epAutreComp.structure <> :structure");
        }
        else {
            // si aucune structure n'est spécifiée, on ne filtre pas par composante d'intervention
            $qbAutreComp->join("sAutreComp.elementPedagogique", "epAutreComp");
        }
        $qbAutreComp
            ->join("sAutreComp.volumeHoraire", "vhAutreComp")
            ->join("vhAutreComp.typeVolumeHoraire", "tvhAutreComp", Join::WITH, "tvhAutreComp = :tvh")
            ->leftJoin("vhAutreComp.validation", "valAutreComp")
            ->andWhere("valAutreComp.id IS NULL")
            ->andWhere("sAutreComp.intervenant = int");

        // Eviction des données historisées.
        $qbAutreComp
            ->andWhere("1 = pasHistorise(sAutreComp)")
            ->andWhere("1 = pasHistorise(epAutreComp)")
            ->andWhere("1 = pasHistorise(vhAutreComp)")
            ->andWhere("1 = pasHistorise(valAutreComp)");

        $dqlAutresComposantes = $qbAutreComp->getDQL();

        $qb->andWhere("EXISTS ( $dqlAutresComposantes )");

        return $this;
    }

    /**
     * Retourne le type d'intervenant utile à cet indicateur.
     *
     * @return TypeIntervenantEntity
     */
    public function getTypeIntervenant()
    {
        if (null === $this->typeIntervenant) {
            $this->typeIntervenant =
                $this->getServiceLocator()->get('ApplicationTypeIntervenant')->getByCode(TypeIntervenantEntity::CODE_PERMANENT);
        }

        return $this->typeIntervenant;
    }

    /**
     * Retourne le type de volume horaire utile à cet indicateur.
     *
     * @return TypeVolumeHoraireEntity
     */
    public function getTypeVolumeHoraire()
    {
        if (null === $this->typeVolumeHoraire) {
            $this->typeVolumeHoraire = $this->getServiceLocator()->get('ApplicationTypeVolumeHoraire')->getRealise();
        }

        return $this->typeVolumeHoraire;
    }
}