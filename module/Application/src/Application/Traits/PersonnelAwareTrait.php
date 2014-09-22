<?php

namespace Application\Traits;

use Application\Entity\Db\Personnel;

/**
 * Description of PersonnelAwareTrait
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
trait PersonnelAwareTrait
{
    /**
     * @var Personnel
     */
    protected $personnel;

    /**
     * Spécifie le personnel concerné.
     *
     * @param Personnel $personnel Personnel concerné
     */
    public function setPersonnel(Personnel $personnel = null)
    {
        $this->personnel = $personnel;

        return $this;
    }

    /**
     * Retourne le personnel concerné.
     *
     * @return Personnel
     */
    public function getPersonnel()
    {
        return $this->personnel;
    }
}