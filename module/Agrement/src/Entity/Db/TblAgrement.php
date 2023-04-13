<?php

namespace Agrement\Entity\Db;

use Agrement\Entity\Db\Traits\AgrementAwareTrait;
use Application\Entity\Db\Annee;
use Application\Entity\Db\Traits\AnneeAwareTrait;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Application\Entity\Db\Traits\StructureAwareTrait;
use Agrement\Entity\Db\Traits\TypeAgrementAwareTrait;
use Application\Resource\WorkflowResource;
use Laminas\Permissions\Acl\Resource\ResourceInterface;


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



    public function getResourceWorkflow(): WorkflowResource
    {
        $etape = $this->getTypeAgrement()->getCode();

        return WorkflowResource::create($etape, $this->getIntervenant(), $this->getStructure());
    }
}