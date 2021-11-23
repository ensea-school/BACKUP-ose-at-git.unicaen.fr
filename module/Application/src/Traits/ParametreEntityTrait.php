<?php

namespace Application\Traits;

use Application\Entity\Db\Annee;
use Application\Interfaces\ParametreEntityInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

trait ParametreEntityTrait
{
    use HistoriqueAwareTrait;

    protected ?int  $id = null;

    protected Annee $annee;



    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }



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

}