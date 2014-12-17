<?php

namespace Application\Interfaces;

use Application\Entity\Db\Etablissement;

/**
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
interface EtablissementAwareInterface
{

    /**
     * Spécifie l'établissement concerné.
     *
     * @param Etablissement $etablissement l'établissement concerné
     * @return self
     */
    public function setEtablissement(Etablissement $etablissement = null);

    /**
     * Retourne l'établissement concerné.
     *
     * @return Etablissement
     */
    public function getEtablissement();
}