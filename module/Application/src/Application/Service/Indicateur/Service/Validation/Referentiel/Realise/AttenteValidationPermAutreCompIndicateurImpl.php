<?php

namespace Application\Service\Indicateur\Service\Validation\Referentiel\Realise;

use Application\Entity\Db\TypeIntervenant as TypeIntervenantEntity;
use Application\Entity\Db\TypeVolumeHoraire as TypeVolumeHoraireEntity;
use Application\Entity\Db\VIndicAttenteValidRefAutre;
use Application\Service\Indicateur\AbstractIntervenantResultIndicateurImpl;
use Application\Service\Traits\IntervenantAwareTrait;
use Application\Service\Traits\ServiceReferentielAwareTrait;
use Application\Entity\Db\Traits\TypeIntervenantAwareTrait;
use Application\Entity\Db\Traits\TypeVolumeHoraireAwareTrait;
use Doctrine\ORM\QueryBuilder;
use Zend\Filter\Callback;

/**
 *
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AttenteValidationPermAutreCompIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    use IntervenantAwareTrait;
    use ServiceReferentielAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use TypeIntervenantAwareTrait;

    protected $singularTitlePattern = "%s permanent  a   clôturé la saisie de ses   services réalisés et est  en attente de validation de son  référentiel <em>%s</em> par d'autres composantes";
    protected $pluralTitlePattern   = "%s permanents ont clôturé la saisie de leurs services réalisés et sont en attente de validation de leur référentiel <em>%s</em> par d'autres composantes";

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
     * @param VIndicAttenteValidRefAutre $result
     * @return string
     */
    public function getResultItemUrl($result)
    {
        return $this->getHelperUrl()->fromRoute(
            'intervenant/validation-referentiel-realise',
            ['intervenant' => $result->getIntervenant()->getSourceCode()],
            ['force_canonical' => true]);
    }

    /**
     * Retourne le filtre retournant l'intervenant correspondant à chaque item de résultat.
     *
     * @return FilterInterface
     */
    public function getResultItemIntervenantExtractor()
    {
        if (null === $this->resultItemIntervenantExtractor) {
            $this->resultItemIntervenantExtractor = new Callback(function(VIndicAttenteValidRefAutre $resultItem) {
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
            $this->resultItemFormatter = new Callback(function(VIndicAttenteValidRefAutre $resultItem) {
                $intervenant = $this->getResultItemIntervenantExtractor()->filter($resultItem);
                $out = sprintf("%s <small>(n°%s%s)</small>",
                    $intervenant,
                    $intervenant->getSourceCode(),
                    $intervenant->getStatut()->estPermanent() ? ", Affectation: " . $intervenant->getStructure() : null);
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
        // INDISPENSABLE si plusieurs requêtes successives avec des critères différents sur la même entité !
        $this->getEntityManager()->clear('Application\Entity\Db\VIndicAttenteValidRefAutre');

        $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\VIndicAttenteValidRefAutre')->createQueryBuilder("v");
        $qb
            ->addSelect("int, aff, si, str")
            ->join("v.structure", "str")
            ->join("v.intervenant", "int")
            ->join("int.structure", "aff")
            ->join("int.statut", "si")
            ->andWhere("int.annee = :annee")
            ->setParameter("annee", $this->getServiceContext()->getAnnee());

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
     * Retourne le type d'intervenant utile à cet indicateur.
     *
     * @return TypeIntervenantEntity
     */
    public function getTypeIntervenant()
    {
        if (! $this->typeIntervenant) {
            $sTi = $this->getServiceLocator()->get('ApplicationTypeIntervenant');
            /* @var $sTi \Application\Service\TypeIntervenant */
            $this->setTypeIntervenant( $sTi->getPermanent() );
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