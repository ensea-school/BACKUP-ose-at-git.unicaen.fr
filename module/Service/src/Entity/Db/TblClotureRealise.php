<?php

namespace Service\Entity\Db;

use Application\Entity\Db\Traits\AnneeAwareTrait;
use Application\Entity\Db\Traits\IntervenantAwareTrait;

class TblClotureRealise
{
    use AnneeAwareTrait;
    use IntervenantAwareTrait;

    private int  $id;

    private bool $actif   = false;

    private bool $cloture = false;



    public function getId(): int
    {
        return $this->id;
    }



    public function getActif(): bool
    {
        return $this->actif;
    }



    public function getCloture(): bool
    {
        return $this->cloture;
    }

}

