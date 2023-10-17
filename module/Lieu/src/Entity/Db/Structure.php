<?php

namespace Lieu\Entity\Db;

use Application\Entity\Db\Intervenant;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\AdresseInterface;
use Lieu\Entity\AdresseTrait;
use Plafond\Interfaces\PlafondDataInterface;
use Plafond\Interfaces\PlafondPerimetreInterface;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;

/**
 * Structure
 */
class Structure implements HistoriqueAwareInterface, ResourceInterface, ImportAwareInterface, PlafondPerimetreInterface, PlafondDataInterface, AdresseInterface
{
    use AdresseTrait;
    use ImportAwareTrait;
    use HistoriqueAwareTrait;

    protected ?int $id = null;

    protected ?string $code = null;

    protected ?string $libelleCourt = null;

    protected ?string $libelleLong = null;

    protected Collection $elementPedagogique;

    protected Collection $centreCout;

    protected Collection $miseEnPaiementIntervenantStructure;

    protected bool $enseignement = false;

    protected $affAdresseContrat = true;

    protected ?Structure $structure = null;

    protected ?string $ids = null;

    protected Collection $structures;



    function __construct()
    {
        $this->elementPedagogique = new ArrayCollection;
        $this->centreCout = new ArrayCollection;
        $this->miseEnPaiementIntervenantStructure = new ArrayCollection;
        $this->structures = new ArrayCollection;
    }



    public function axiosDefinition(): array
    {
        return ['libelleLong', 'libelleCourt', 'code', 'id'];
    }



    public function getId(): ?int
    {
        return $this->id;
    }



    public function getIds(): ?string
    {
        return $this->ids;
    }



    /**
     * Permet de savoir si l'objet est une sous-structure de $structure
     */
    public function inStructure(Structure $structure): bool
    {
        $id = $structure->getId();

        if (empty($this->ids)){
            return false;
        }

        return str_contains($this->ids, '-'.$id.'-');
    }



    public function getCode(): ?string
    {
        return $this->code;
    }



    public function setCode(?string $code): Structure
    {
        $this->code = $code;

        return $this;
    }



    public function getLibelleLong(): ?string
    {
        return $this->libelleLong;
    }



    public function setLibelleLong(?string $libelleLong): Structure
    {
        $this->libelleLong = $libelleLong;

        return $this;
    }



    /**
     * Add elementPedagogique
     *
     * @param \OffreFormation\Entity\Db\ElementPedagogique $elementPedagogique
     *
     * @return Intervenant
     */
    public function addElementPedagogique(\OffreFormation\Entity\Db\ElementPedagogique $elementPedagogique)
    {
        $this->elementPedagogique[] = $elementPedagogique;

        return $this;
    }



    /**
     * Remove elementPedagogique
     *
     * @param \OffreFormation\Entity\Db\ElementPedagogique $elementPedagogique
     */
    public function removeElementPedagogique(\OffreFormation\Entity\Db\ElementPedagogique $elementPedagogique)
    {
        $this->elementPedagogique->removeElement($elementPedagogique);
    }



    /**
     * Get elementPedagogique
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getElementPedagogique()
    {
        return $this->elementPedagogique;
    }



    /**
     * Add centreCout
     *
     * @param \Paiement\Entity\Db\CentreCout $centreCout
     *
     * @return Intervenant
     */
    public function addCentreCout(\Paiement\Entity\Db\CentreCout $centreCout)
    {
        $this->centreCout[] = $centreCout;

        return $this;
    }



    /**
     * Remove centreCout
     *
     * @param \Paiement\Entity\Db\CentreCout $centreCout
     */
    public function removeCentreCout(\Paiement\Entity\Db\CentreCout $centreCout)
    {
        $this->service->removeElement($centreCout);
    }



    /**
     * Get centreCout
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCentreCout()
    {
        return $this->centreCout;
    }



    /**
     * Get miseEnPaiementIntervenantStructure
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMiseEnPaiementIntervenantStructure()
    {
        return $this->miseEnPaiementIntervenantStructure;
    }



    public function isEnseignement(): bool
    {
        return $this->enseignement;
    }



    public function setEnseignement(bool $enseignement): Structure
    {
        $this->enseignement = $enseignement;

        return $this;
    }



    public function isAffAdresseContrat(): bool
    {
        return $this->affAdresseContrat;
    }



    public function setAffAdresseContrat(bool $affAdresseContrat): Structure
    {
        $this->affAdresseContrat = $affAdresseContrat;

        return $this;
    }



    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelleCourt();
    }



    public function getAdresseIdentite(): ?string
    {
        return $this->getLibelleLong();
    }



    public function getStructure(): ?Structure
    {
        return $this->structure;
    }



    public function setStructure(?Structure $structure): Structure
    {
        $this->structure = $structure;

        return $this;
    }



    /**
     * @return Collection|Structure[]
     */
    public function getStructures(): Collection
    {
        return $this->structures;
    }



    public function getLibelleCourt(): ?string
    {
        return $this->libelleCourt;
    }



    public function setLibelleCourt(?string $libelleCourt): Structure
    {
        $this->libelleCourt = $libelleCourt;

        return $this;
    }



    public function getLibelle(): string
    {
        return $this->getLibelleCourt();
    }



    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     */
    public function getResourceId()
    {
        return 'Structure';
    }



    function __sleep()
    {
        return [];
    }

}