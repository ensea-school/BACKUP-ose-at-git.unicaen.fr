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
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelleLong();
    }



    /**
     * @var string
     */
    protected $libelleCourt;

    /**
     * @var string
     */
    protected $libelleLong;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\GroupeTypeFormation
     */
    protected $groupe;

    /**
     * @var bool
     */
    protected $serviceStatutaire = true;



    /**
     * Set libelleCourt
     *
     * @param string $libelleCourt
     *
     * @return TypeFormation
     */
    public function setLibelleCourt($libelleCourt)
    {
        $this->libelleCourt = $libelleCourt;

        return $this;
    }



    /**
     * Get libelleCourt
     *
     * @return string
     */
    public function getLibelleCourt()
    {
        return $this->libelleCourt;
    }



    /**
     * Set libelleLong
     *
     * @param string $libelleLong
     *
     * @return TypeFormation
     */
    public function setLibelleLong($libelleLong)
    {
        $this->libelleLong = $libelleLong;

        return $this;
    }



    /**
     * Get libelleLong
     *
     * @return string
     */
    public function getLibelleLong()
    {
        return $this->libelleLong;
    }



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * Set groupe
     *
     * @param \Application\Entity\Db\GroupeTypeFormation $groupe
     *
     * @return TypeFormation
     */
    public function setGroupe(\Application\Entity\Db\GroupeTypeFormation $groupe = null)
    {
        $this->groupe = $groupe;

        return $this;
    }



    /**
     * Get groupe
     *
     * @return \Application\Entity\Db\GroupeTypeFormation
     */
    public function getGroupe()
    {
        return $this->groupe;
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
     *
     * @return typeFormation
     */
    public function setServiceStatutaire(bool $serviceStatutaire)
    {
        $this->serviceStatutaire = $serviceStatutaire;

        return $this;
    }

}
