<?php

namespace Agrement\Entity\Db;

use Agrement\Entity\Db\Traits\AgrementAwareTrait;
use Agrement\Entity\Db\Traits\TypeAgrementAwareTrait;
use Application\Entity\Db\Annee;
use Application\Entity\Db\Traits\AnneeAwareTrait;
use Intervenant\Entity\Db\IntervenantAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\StructureAwareTrait;


class TblAgrement implements ResourceInterface
{
    use AnneeAwareTrait;
    use TypeAgrementAwareTrait;
    use IntervenantAwareTrait;
    use StructureAwareTrait;
    use AgrementAwareTrait;

    private int    $id            = 0;

    private int    $dureeVie      = 1;

    private ?Annee $anneeAgrement = null;

    private string $codeIntervenant;



    public function getId(): int
    {
        return $this->id;
    }



    public function getDureeVie(): int
    {
        return $this->dureeVie;
    }



    public function getAnneeAgrement(): ?Annee
    {
        return $this->anneeAgrement;
    }



    /**
     * Utile pour le finderByCodeIntervenant
     *
     * @return string
     */
    public function getCodeIntervenant(): string
    {
        return $this->codeIntervenant;
    }



    public function getResourceId(): string
    {
        return 'TblAgrement';
    }
}