<?php

namespace Indicateur\Entity\Db\Indicateur;

use Application\Entity\Db\Traits\StatutIntervenantAwareTrait;

class Indicateur610 extends AbstractIndicateur
{
    use StatutIntervenantAwareTrait;

    /**
     * Retourne les détails concernant l'indicateur
     *
     * @return string|null
     */
    public function getDetails()
    {
        $out = 'Statut : '.$this->getStatutIntervenant()->getLibelle();

        return $out;
    }
}
