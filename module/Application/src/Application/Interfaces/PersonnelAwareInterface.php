<?php

namespace Application\Interfaces;

use Application\Entity\Db\Personnel;

/**
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
interface PersonnelAwareInterface
{

    /**
     * Spécifie le personnel concerné.
     *
     * @param Personnel $personnel Personnel concerné
     */
    public function setPersonnel(Personnel $personnel = null);

    /**
     * Retourne le personnel concerné.
     *
     * @return Personnel
     */
    public function getPersonnel();
}