<?php

namespace Mission\Entity\Db;

use Application\Entity\Db\Fichier;
use Doctrine\Common\Collections\Collection;
use Intervenant\Entity\Db\IntervenantAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use Workflow\Entity\Db\Validation;

class Prime implements HistoriqueAwareInterface, ResourceInterface
{
    use HistoriqueAwareTrait;
    use IntervenantAwareTrait;

    protected ?int        $id          = null;

    protected ?Fichier    $declaration = null;

    protected ?Validation $validation  = null;

    protected ?\DateTime  $dateRefus   = null;

    protected Collection  $missions;



    public function __construct ()
    {
    }



    public function getResourceId (): string
    {
        return self::class;
    }



    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString ()
    {
        return 'Prime ' . $this->getId();
    }



    public function getId (): ?int
    {
        return $this->id;
    }



    /**
     * @return Validation
     */
    public function getValidation (): ?Validation
    {
        return $this->validation;
    }



    /**
     * @param Validation $validation
     *
     * @return OffreEmploi
     */
    public function setValidation (?Validation $validation): Prime
    {
        $this->validation = $validation;

        return $this;
    }



    public function getDeclaration (): ?Fichier
    {
        return $this->declaration;
    }



    public function setDeclaration (?Fichier $declaration): Prime
    {
        $this->declaration = $declaration;

        return $this;
    }



    public function getDateRefus (): ?\DateTime
    {
        return $this->dateRefus;
    }



    public function setDateRefus (?\DateTime $dateRefus): Prime
    {
        $this->dateRefus = $dateRefus;

        return $this;
    }



    public function getMissions (): Collection
    {
        return $this->missions;
    }



    /**
     * Add Mission
     *
     * @param Mission $mission
     *
     * @return Prime
     */
    public function addMission (Mission $mission)
    {
        $this->missions[] = $mission;

        return $this;
    }



    /**
     * Remove Prime
     *
     * @param Mission $mission
     */
    public function removeMission (Mission $mission)
    {
        $this->missions->removeElement($mission);

        return $this;
    }

}
