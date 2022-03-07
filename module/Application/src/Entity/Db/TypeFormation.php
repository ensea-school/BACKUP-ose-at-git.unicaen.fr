<?php

namespace Application\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;

/**
 * TypeFormation
 */
class TypeFormation implements HistoriqueAwareInterface, ImportAwareInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;

    /**
     * @var string
     */
    protected ?string $libelleCourt = null;

    /**
     * @var string
     */
    protected ?string $libelleLong = null;

    /**
     * @var integer
     */
    protected ?int $id = null;

    /**
     * @var \Application\Entity\Db\GroupeTypeFormation
     */
    protected ?GroupeTypeFormation $groupe = null;

    /**
     * @var bool
     */
    protected bool $serviceStatutaire = true;



    /**
     * @return string
     */
    public function getLibelleCourt(): ?string
    {
        return $this->libelleCourt;
    }



    /**
     * @param string $libelleCourt
     */
    public function setLibelleCourt(?string $libelleCourt): void
    {
        $this->libelleCourt = $libelleCourt;
    }



    /**
     * @return string
     */
    public function getLibelleLong(): ?string
    {
        return $this->libelleLong;
    }



    /**
     * @param string $libelleLong
     */
    public function setLibelleLong(?string $libelleLong): void
    {
        $this->libelleLong = $libelleLong;
    }



    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }



    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }



    /**
     * @return GroupeTypeFormation
     */
    public function getGroupe(): ?GroupeTypeFormation
    {
        return $this->groupe;
    }



    /**
     * @param GroupeTypeFormation $groupe
     */
    public function setGroupe(?GroupeTypeFormation $groupe): void
    {
        $this->groupe = $groupe;
    }



    /**
     * @return bool
     */
    public function isServiceStatutaire(): bool
    {
        return $this->serviceStatutaire;
    }



    /**
     * @param bool $serviceStatutaire
     */
    public function setServiceStatutaire(bool $serviceStatutaire): void
    {
        $this->serviceStatutaire = $serviceStatutaire;
    }





}
