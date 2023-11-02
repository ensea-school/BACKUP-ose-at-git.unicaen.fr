<?php

namespace Paiement\Entity;

use Application\Entity\Collection;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Traits\AnneeAwareTrait;
use Application\Entity\Db\Traits\PeriodeAwareTrait;
use Intervenant\Entity\Db\TypeIntervenantAwareTrait;
use Paiement\Entity\Db\MiseEnPaiement;
use Lieu\Entity\Db\StructureAwareTrait;


/**
 * Description of MiseEnPaiementRecherche
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class MiseEnPaiementRecherche
{
    use AnneeAwareTrait;
    use StructureAwareTrait;
    use PeriodeAwareTrait;
    use TypeIntervenantAwareTrait;

    /**
     * etat
     *
     * @var string
     */
    protected $etat;

    /**
     * Intervenants
     *
     * @var Intervenant[]
     */
    protected $intervenants;



    /**
     *
     * @return string
     */
    function getEtat()
    {
        return $this->etat;
    }



    function setEtat($etat)
    {
        if ($etat === null || $etat === MiseEnPaiement::A_METTRE_EN_PAIEMENT || $etat === MiseEnPaiement::MIS_EN_PAIEMENT || $etat === MiseEnPaiement::IMPUTATION_BUDGETAIRE) {
            $this->etat = $etat;
        } else {
            throw new \LogicException('L\'état de mise en paiement "' . $etat . '" est invalide.');
        }

        return $this;
    }



    /**
     *
     * @return Intervenant[]
     */
    public function getIntervenants()
    {
        if (null === $this->intervenants) {
            $this->intervenants = new Collection;
            $this->intervenants->initEntityClass(Intervenant::class);
        }

        return $this->intervenants;
    }



    public function getFilters(): array
    {
        $filters = [];
        if ($e = $this->getEtat()) {
            $filters['ETAT'] = $e;
        }
        if ($a = $this->getAnnee()) {
            $filters['ANNEE_ID'] = $a->getId();
        }
        if ($s = $this->getStructure()) {
            $filters['STRUCTURE_IDS'] = $s->idsFilter();
        }
        if ($p = $this->getPeriode()) {
            $filters['PERIODE_ID'] = $p->getId();
        }
        if ($t = $this->getTypeIntervenant()) {
            $filters['TYPE_INTERVENANT_ID'] = $t->getId();
        }
        if ($this->getIntervenants()->count() > 0) {
            $iIdList = [];
            foreach ($this->getIntervenants() as $intervenant) {
                $filters['INTERVENANT_ID'] = $iIdList;
                $iIdList[]                 = $intervenant->getId();
            }
            $filters['INTERVENANT_ID'] = $iIdList;
        }

        return $filters;
    }
}