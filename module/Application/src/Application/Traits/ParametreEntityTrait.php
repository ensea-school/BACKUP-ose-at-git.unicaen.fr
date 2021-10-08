<?php

namespace Application\Traits;

use DateTime;
use Application\Entity\Db\Annee;
use UnicaenApp\Entity\UserInterface;
use Application\Interfaces\ParametreEntityInterface;

trait ParametreEntityTrait
{
    protected Annee          $annee;

    protected bool           $saveOnlyAnneeCourante = false;

    protected ?DateTime      $histoModification     = null;

    protected ?UserInterface $histoModificateur     = null;



    /**
     * @return Annee
     */
    public function getAnnee(): Annee
    {
        return $this->annee;
    }



    /**
     * @param Annee $annee
     *
     * @return ParametreEntityInterface
     */
    public function setAnnee(Annee $annee): ParametreEntityInterface
    {
        $this->annee = $annee;

        return $this;
    }



    /**
     * @return bool
     */
    public function isSaveOnlyAnneeCourante(): bool
    {
        return $this->saveOnlyAnneeCourante;
    }



    /**
     * @param bool $saveOnlyAnneeCourante
     *
     * @return ParametreEntityInterface
     */
    public function setSaveOnlyAnneeCourante(bool $saveOnlyAnneeCourante): ParametreEntityInterface
    {
        $this->saveOnlyAnneeCourante = $saveOnlyAnneeCourante;

        return $this;
    }



    /**
     * @return null|DateTime
     */
    public function getHistoModification(): ?DateTime
    {
        return $this->histoModification;
    }



    /**
     * @param DateTime $histoModification
     *
     * @return ParametreEntityInterface
     */
    public function setHistoModification(DateTime $histoModification): ParametreEntityInterface
    {
        $this->histoModification = $histoModification;

        return $this;
    }



    /**
     * @return null|UserInterface
     */
    public function getHistoModificateur(): ?UserInterface
    {
        return $this->histoModificateur;
    }



    /**
     * @param UserInterface $histoModificateur
     *
     * @return ParametreEntityInterface
     */
    public function setHistoModificateur(UserInterface $histoModificateur): ParametreEntityInterface
    {
        $this->histoModificateur = $histoModificateur;

        return $this;
    }

}