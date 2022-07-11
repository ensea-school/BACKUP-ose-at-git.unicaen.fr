<?php

namespace Service\Entity\Db;

use Intervenant\Entity\Db\TypeIntervenant;
use Intervenant\Entity\Db\TypeIntervenantAwareTrait;
use Application\Entity\Db\Traits\TypeVolumeHoraireAwareTrait;

class RegleStructureValidation
{
    use TypeVolumeHoraireAwareTrait;
    use TypeIntervenantAwareTrait;

    protected ?int    $id;

    protected ?string $priorite;

    protected ?string $message;



    public function getId(): ?int
    {
        return $this->id;
    }



    public function getPriorite(): ?string
    {
        return $this->priorite;
    }



    public function setPriorite(?string $priorite): RegleStructureValidation
    {
        $this->priorite = $priorite;

        return $this;
    }



    public function getMessage(): ?string
    {
        return $this->message;
    }



    public function setMessage(?string $message): RegleStructureValidation
    {
        $this->message = $message;

        return $this;
    }

}
