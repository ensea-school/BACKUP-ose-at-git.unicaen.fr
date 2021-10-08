<?php

namespace Application\Interfaces;

use Application\Entity\Db\Annee;
use UnicaenApp\Entity\UserInterface;
use DateTime;
use Application\Entity\Db\Utilisateur;

interface ParametreEntityInterface
{

    /**
     * @return Annee
     */
    public function getAnnee(): Annee;



    /**
     * @param Annee $annee
     *
     * @return ParametreEntityInterface
     */
    public function setAnnee(Annee $annee): ParametreEntityInterface;



    /**
     * @return bool
     */
    public function isSaveOnlyAnneeCourante(): bool;



    /**
     * @param bool $saveOnlyAnneeCourante
     *
     * @return ParametreEntityInterface
     */
    public function setSaveOnlyAnneeCourante(bool $saveOnlyAnneeCourante): ParametreEntityInterface;



    /**
     * @return DateTime
     */
    public function getHistoModification(): ?DateTime;



    /**
     * @param DateTime $histoModification
     *
     * @return ParametreEntityInterface
     */
    public function setHistoModification(DateTime $histoModification): ParametreEntityInterface;



    /**
     * @return null|UserInterface
     */
    public function getHistoModificateur(): ?UserInterface;



    /**
     * @param UserInterface $histoModificateur
     *
     * @return ParametreEntityInterface
     */
    public function setHistoModificateur(UserInterface $histoModificateur): ParametreEntityInterface;
}