<?php

namespace Mission\Entity\Db;

use Administration\Interfaces\ParametreEntityInterface;
use Administration\Traits\ParametreEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Paiement\Entity\Db\TauxRemu;
use Plafond\Interfaces\PlafondDataInterface;
use Plafond\Interfaces\PlafondPerimetreInterface;

class TypeMission implements ParametreEntityInterface, PlafondPerimetreInterface, PlafondDataInterface
{
    use ParametreEntityTrait;

    protected ?int      $id                      = null;

    protected ?string   $code                    = null;

    protected ?string   $libelle                 = null;

    protected ?TauxRemu $tauxRemu                = null;

    protected ?TauxRemu $tauxRemuMajore          = null;

    protected bool      $accompagnementEtudiants = false;

    protected bool      $besoinFormation         = false;

    protected           $centreCoutsTypeMission  = null;



    public function getId(): ?int
    {
        return $this->id;
    }



    public function getCode(): ?string
    {
        return $this->code;
    }



    /**
     *
     */
    public function __construct()
    {
        $this->centreCoutsTypeMission = new ArrayCollection();
    }



    /**
     * @return ArrayCollection
     */
    public function getCentreCoutsTypeMission(): ArrayCollection
    {
        return $this->centreCoutsTypeMission->filter(function ($centreCoutLinker) {
            return !$centreCoutLinker->estHistorise();
        });
    }



    /**
     * @param null $centreCoutTypeMission
     *
     * @return TypeMission
     */
    public function addCentreCoutTypeMission($centreCoutTypeMission): TypeMission
    {
        $this->centreCoutsTypeMission[] = $centreCoutTypeMission;

        return $this;
    }



    /**
     * @param $centreCoutTypeMission
     *
     * @return $this
     */
    public function removeCentreCoutTypeMission($centreCoutTypeMission): TypeMission
    {

        $this->centreCoutsTypeMission->removeElement($centreCoutTypeMission);

        return $this;
    }



    public function setCode(?string $code): TypeMission
    {
        $this->code = $code;

        return $this;
    }



    public function getLibelle(): ?string
    {
        return $this->libelle;
    }



    public function getAccompagnementEtudiants(): ?string
    {
        return $this->accompagnementEtudiants;
    }



    public function getBesoinFormation(): ?string
    {
        return $this->besoinFormation;
    }



    public function setLibelle(?string $libelle): TypeMission
    {
        $this->libelle = $libelle;

        return $this;
    }



    /**
     * @return TauxRemu|null
     */
    public function getTauxRemu(): ?TauxRemu
    {
        return $this->tauxRemu;
    }



    /**
     * @param TauxRemu|null $tauxRemu
     *
     * @return $this
     */
    public function setTauxRemu(?TauxRemu $tauxRemu): TypeMission
    {
        $this->tauxRemu = $tauxRemu;

        return $this;
    }



    /**
     * @return TauxRemu|null
     */
    public function getTauxRemuMajore(): ?TauxRemu
    {
        return $this->tauxRemuMajore;
    }



    /**
     * @param TauxRemu|null $tauxRemuMajore
     *
     * @return $this
     */
    public function setTauxRemuMajore(?TauxRemu $tauxRemuMajore): self
    {
        $this->tauxRemuMajore = $tauxRemuMajore;

        return $this;
    }



    public function isAccompagnementEtudiants(): bool
    {
        return $this->accompagnementEtudiants;
    }



    public function setAccompagnementEtudiants(bool $accompagnementEtudiants): TypeMission
    {
        $this->accompagnementEtudiants = $accompagnementEtudiants;

        return $this;
    }



    public function isBesoinFormation(): bool
    {
        return $this->besoinFormation;
    }



    public function setBesoinFormation(bool $besoinFormation): TypeMission
    {
        $this->besoinFormation = $besoinFormation;

        return $this;
    }



    public function __toString(): string
    {
        return $this->getLibelle();
    }



    /**
     * @return array
     */
    public function getCentreCoutsIds(): array
    {
        $centreCoutsIds = [];
        /**
         * @var CentreCoutTypeMission $centreCoutTypeMission
         */
        foreach ($this->centreCoutsTypeMission as $centreCoutTypeMission) {
            $centreCoutsIds[] = $centreCoutTypeMission->getCentreCouts()->getId();
        }

        return $centreCoutsIds;
    }



    public function setCentreCoutsTypeMission(?ArrayCollection $centreCoutsTypeMission): void
    {
        $this->centreCoutsTypeMission = $centreCoutsTypeMission;
    }
}
