<?php

namespace Application\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;

/**
 * Corps
 */
class Corps implements HistoriqueAwareInterface, ImportAwareInterface
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
    public function getId(): ?int
    {
        return $this->id;
    }


    /**************************************************************************************************
     *                                        Début ajout
     **************************************************************************************************/

    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelleLong();
    }
}
