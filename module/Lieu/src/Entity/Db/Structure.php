<?php

namespace Lieu\Entity\Db;

use Administration\Interfaces\ChampsAutresInterface;
use Administration\Traits\ChampsAutresTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Intervenant\Entity\Db\Intervenant;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\AdresseInterface;
use Lieu\Entity\AdresseTrait;
use Paiement\Entity\Db\CentreCout;
use Paiement\Entity\Db\DomaineFonctionnel;
use Plafond\Interfaces\PlafondDataInterface;
use Plafond\Interfaces\PlafondPerimetreInterface;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;

/**
 * Structure
 */
class Structure implements HistoriqueAwareInterface, ResourceInterface, ImportAwareInterface, PlafondPerimetreInterface, PlafondDataInterface, AdresseInterface, ChampsAutresInterface
{
    use AdresseTrait;
    use ImportAwareTrait;
    use HistoriqueAwareTrait;
    use ChampsAutresTrait;

    protected ?int                $id                        = null;

    protected ?string             $code                      = null;

    protected ?string             $libelleCourt              = null;

    protected ?string             $libelleLong               = null;

    protected Collection          $elementPedagogique;

    protected Collection          $centreCout;

    protected ?CentreCout         $centreCoutDefault         = null;

    protected ?DomaineFonctionnel $domaineFonctionnelDefault = null;

    protected Collection          $miseEnPaiementIntervenantStructure;

    protected Collection          $tblPaiement;

    protected bool                $enseignement              = false;

    protected                     $affAdresseContrat         = true;

    protected ?Structure          $structure                 = null;

    protected ?string             $ids                       = null;

    protected ?string             $libellesCourts            = null;

    protected Collection          $structures;



    function __construct ()
    {
        $this->elementPedagogique                 = new ArrayCollection;
        $this->centreCout                         = new ArrayCollection;
        $this->miseEnPaiementIntervenantStructure = new ArrayCollection;
        $this->structures                         = new ArrayCollection;
    }



    public function axiosDefinition (): array
    {
        return ['libelleLong', 'libelleCourt', 'code', 'id'];
    }



    public function getId (): ?int
    {
        return $this->id;
    }



    public function getIds (): ?string
    {
        return $this->ids;
    }



    public function getIdsArray (): array
    {
        $res = [];

        if (empty($this->ids)) {
            return $res;
        }


        $ids = explode('-', substr($this->ids, 1, -1));
        foreach ($ids as $id) {
            $id = (int)$id;
            if ($id > 0) {
                $res[] = $id;
            }
        }

        return $res;
    }



    public function getLevel (): int
    {
        return substr_count($this->ids ?? '', '-') - 2;
    }



    public function getLibellesCourts (): ?string
    {
        return $this->libellesCourts;
    }



    /**
     * Permet de savoir si l'objet est une sous-structure de $structure
     */
    public function inStructure (Structure $structure): bool
    {
        $id = $structure->getId();

        if (empty($this->ids)) {
            return false;
        }

        return str_contains($this->ids, '-' . $id . '-');
    }



    public function idsFilter (): string
    {
        return '%-' . $this->getId() . '-%';
    }



    public function getCode (): ?string
    {
        return $this->code;
    }



    public function setCode (?string $code): Structure
    {
        $this->code = $code;

        return $this;
    }



    public function getLibelleLong (): ?string
    {
        return $this->libelleLong;
    }



    public function setLibelleLong (?string $libelleLong): Structure
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
    public function addElementPedagogique (\OffreFormation\Entity\Db\ElementPedagogique $elementPedagogique)
    {
        $this->elementPedagogique[] = $elementPedagogique;

        return $this;
    }



    /**
     * Remove elementPedagogique
     *
     * @param \OffreFormation\Entity\Db\ElementPedagogique $elementPedagogique
     */
    public function removeElementPedagogique (\OffreFormation\Entity\Db\ElementPedagogique $elementPedagogique)
    {
        $this->elementPedagogique->removeElement($elementPedagogique);
    }



    /**
     * Get elementPedagogique
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getElementPedagogique ()
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
    public function addCentreCout (\Paiement\Entity\Db\CentreCout $centreCout)
    {
        $this->centreCout[] = $centreCout;

        return $this;
    }



    /**
     * Remove centreCout
     *
     * @param \Paiement\Entity\Db\CentreCout $centreCout
     */
    public function removeCentreCout (\Paiement\Entity\Db\CentreCout $centreCout)
    {
        $this->service->removeElement($centreCout);
    }



    /**
     * Get centreCout
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCentreCout ()
    {
        return $this->centreCout;
    }



    /**
     * Get centreCoutDefault
     *
     * @return ?CentreCout
     */
    public function getCentreCoutDefault (): ?CentreCout
    {
        return $this->centreCoutDefault;
    }



    /**
     * Set centreCoutDefault
     *
     * @return Structure
     */
    public function setCentreCoutDefault (?CentreCout $centreCoutDefault): self
    {
        $this->centreCoutDefault = $centreCoutDefault;

        return $this;
    }



    /**
     * Get domaineFonctionnelDefault
     *
     * @return ?DomaineFonctionnel
     */
    public function getDomaineFonctionnelDefault (): ?DomaineFonctionnel
    {
        return $this->domaineFonctionnelDefault;
    }



    /**
     * Set domaineFonctionnelDefault
     *
     * @return Structure
     */
    public function setDomaineFonctionnelDefault (?DomaineFonctionnel $domaineFonctionnelDefault): self
    {
        $this->domaineFonctionnelDefault = $domaineFonctionnelDefault;

        return $this;
    }



    /**
     * Get tblPaiement
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTblPaiement ()
    {
        return $this->tblPaiement;
    }



    public function isEnseignement (): bool
    {
        return $this->enseignement;
    }



    public function setEnseignement (bool $enseignement): Structure
    {
        $this->enseignement = $enseignement;

        return $this;
    }



    public function isAffAdresseContrat (): bool
    {
        return $this->affAdresseContrat;
    }



    public function setAffAdresseContrat (bool $affAdresseContrat): Structure
    {
        $this->affAdresseContrat = $affAdresseContrat;

        return $this;
    }



    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString ()
    {
        return $this->getLibelleCourt();
    }



    public function getAdresseIdentite (): ?string
    {
        return $this->getLibelleLong();
    }



    public function getStructure (): ?Structure
    {
        return $this->structure;
    }



    public function setStructure (?Structure $structure): Structure
    {
        $this->structure = $structure;

        return $this;
    }



    /**
     * @return Collection|Structure[]
     */
    public function getStructures (): Collection
    {
        return $this->structures;
    }



    public function getLibelleCourt (): ?string
    {
        return $this->libelleCourt;
    }



    public function setLibelleCourt (?string $libelleCourt): Structure
    {
        $this->libelleCourt = $libelleCourt;

        return $this;
    }



    public function getLibelle (): string
    {
        return $this->getLibelleCourt();
    }



    public function getResourceId (): string
    {
        return self::class;
    }



    function __sleep ()
    {
        return [];
    }

}
