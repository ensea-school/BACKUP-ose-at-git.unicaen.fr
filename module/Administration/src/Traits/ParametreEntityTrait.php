<?php

namespace Administration\Traits;

use Administration\Interfaces\ParametreEntityInterface;
use Application\Entity\Db\Annee;
use UnicaenApp\Entity\HistoriqueAwareTrait;

trait ParametreEntityTrait
{
    use HistoriqueAwareTrait;

    protected ?int   $id    = null;

    protected ?Annee $annee = null;



    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }



    /**
     * @return Annee|null
     */
    public function getAnnee(): ?Annee
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

}