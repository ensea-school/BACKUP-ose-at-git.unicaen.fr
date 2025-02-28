<?php

namespace Administration\Interfaces;

use Application\Entity\Db\Annee;
use UnicaenApp\Entity\HistoriqueAwareInterface;

interface ParametreEntityInterface extends HistoriqueAwareInterface
{
    /**
     * @return null|int
     */
    public function getId(): ?int;



    /**
     * @return Annee|null
     */
    public function getAnnee(): ?Annee;



    /**
     * @param Annee $annee
     *
     * @return ParametreEntityInterface
     */
    public function setAnnee(Annee $annee): ParametreEntityInterface;

}