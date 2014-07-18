<?php

namespace Application\Entity\Db;

/**
 * Interface des entités possédant une période de validité.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
interface ValiditeAwareInterface
{
    /**
     * Set validiteDebut
     *
     * @param \DateTime $validiteDebut
     * @return IntervenantPermanent
     */
    public function setValiditeDebut($validiteDebut);

    /**
     * Get validiteDebut
     *
     * @return \DateTime 
     */
    public function getValiditeDebut();

    /**
     * Set validiteFin
     *
     * @param \DateTime $validiteFin
     * @return IntervenantPermanent
     */
    public function setValiditeFin($validiteFin);

    /**
     * Get validiteFin
     *
     * @return \DateTime 
     */
    public function getValiditeFin();
}