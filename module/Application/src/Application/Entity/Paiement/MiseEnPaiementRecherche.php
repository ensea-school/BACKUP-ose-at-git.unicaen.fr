<?php

namespace Application\Entity\Paiement;

use Application\Entity\Collection;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\MiseEnPaiement;


/**
 * Description of MiseEnPaiementRecherche
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class MiseEnPaiementRecherche
{

    use \Application\Entity\Db\Traits\StructureAwareTrait;
    use \Application\Entity\Db\Traits\PeriodeAwareTrait;
    use \Application\Entity\Db\Traits\TypeIntervenantAwareTrait;

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
        if ($etat === null || $etat === MiseEnPaiement::A_METTRE_EN_PAIEMENT || $etat === MiseEnPaiement::MIS_EN_PAIEMENT){
            $this->etat = $etat;
        }else{
            throw new \LogicException('L\'état de mise en paiement "'.$etat.'" est invalide.');
        }
        return $this;
    }

    /**
     * 
     * @return Intervenant[]
     */
    public function getIntervenants()
    {
        if (null === $this->intervenants){
            $this->intervenants = new Collection;
            $this->intervenants->initEntityClass( Intervenant::class );
        }
        return $this->intervenants;
    }

}