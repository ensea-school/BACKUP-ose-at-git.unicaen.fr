<?php

namespace Application\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;

/**
 * Discipline
 */
class Employeur implements HistoriqueAwareInterface, ImportAwareInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $raisonSociale;

    /**
     * @var string
     */
    protected $nomCommercial;

    /**
     * @var string
     */
    protected $identifiantAssociation;

    /**
     * @var string
     */
    protected $siren;


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
     * @return string
     */
    public function getRaisonSociale()
    {
        return $this->raisonSociale;
    }

    /**
     * @param string $raisonSociale
     */
    public function setRaisonSociale($raisonSociale)
    {
        $this->raisonSociale = $raisonSociale;

        return $this;
    }

    /**
     * @return string
     */
    public function getSiren()
    {
        return $this->siren;
    }

    /**
     * @param string $siren
     */
    public function setSiren($siren)
    {
        $this->siren = $siren;

        return $this;
    }

    /**
     * @return string
     */
    public function getNomCommercial()
    {
        return $this->nomCommercial;
    }

    /**
     * @param string $nomCommercial
     */
    public function setNomCommercial($nomCommercial)
    {
        $this->nomCommercial = $nomCommercial;

        return $this;
    }

    /**
     * @return string
     */
    public function getIdentifiantAssociation()
    {
        return $this->identifiantAssociation;
    }

    /**
     * @param string $identifiantAssociation
     */
    public function setIdentifiantAssociation($identifiantAssociation)
    {
        $this->identifiantAssociation = $identifiantAssociation;

        return $this;
    }

    public function __toString()
    {
        return $this->getLibelle();
    }

}
