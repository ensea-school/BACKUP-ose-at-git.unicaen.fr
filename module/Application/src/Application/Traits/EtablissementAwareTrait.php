<?php

namespace Application\Traits;

use Application\Entity\Db\Etablissement;

/**
 * Description of EtablissementAwareTrait
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
trait EtablissementAwareTrait
{
    /**
     * @var Etablissement
     */
    protected $etablissement;

    /**
     * Spécifie l'établissement concerné.
     *
     * @param Etablissement $etablissement l'établissement concerné
     */
    public function setEtablissement(Etablissement $etablissement = null)
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    /**
     * Retourne l'établissement concerné.
     *
     * @return Etablissement
     */
    public function getEtablissement()
    {
        return $this->etablissement;
    }
}