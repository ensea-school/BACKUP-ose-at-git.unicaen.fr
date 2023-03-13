<?php

namespace Dossier\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;

/**
 * Employeur
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
    protected ?string $raisonSociale = null;

    /**
     * @var string
     */
    protected ?string $nomCommercial = null;

    /**
     * @var string
     */
    protected ?string $identifiantAssociation = null;

    /**
     * @var string
     */
    protected ?string $siren = null;

    /**
     * @var string
     */
    protected ?string $siret = null;

    /**
     * @var string
     */
    protected ?string $critereRecherche = null;



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
    public function getRaisonSociale(): ?string
    {
        return $this->raisonSociale;
    }



    /**
     * @param string $raisonSociale
     */
    public function setRaisonSociale($raisonSociale): void
    {
        $this->raisonSociale = $raisonSociale;
    }



    /**
     * @return string
     */
    public function getSiren(): ?string
    {
        return $this->siren;
    }



    /**
     * @param string $siren
     */
    public function setSiren($siren): void
    {
        $this->siren = $siren;
    }



    /**
     * @return string
     */
    public function getSiret(): ?string
    {
        return $this->siret;
    }



    /**
     * @param string $siret
     */
    public function setSiret($siret): void
    {
        $this->siret = $siret;
    }



    /**
     * @return string
     */
    public function getNomCommercial(): ?string
    {
        return $this->nomCommercial;
    }



    /**
     * @param string $nomCommercial
     */
    public function setNomCommercial($nomCommercial): void
    {
        $this->nomCommercial = $nomCommercial;
    }



    /**
     * @return string
     */
    public function getIdentifiantAssociation(): ?string
    {
        return $this->identifiantAssociation;
    }



    /**
     * @param string $identifiantAssociation
     */
    public function setIdentifiantAssociation($identifiantAssociation): void
    {
        $this->identifiantAssociation = $identifiantAssociation;
    }



    /**
     * @return string
     */
    public function getCritereRecherche(): ?string
    {
        return $this->critereRecherche;
    }



    /**
     * @param string $critereRecherche
     */
    public function setCritereRecherche($critereRecherche): void
    {
        $this->critereRecherche = $critereRecherche;
    }



    public function __toString()
    {
        return $this->getLibelle();
    }

}
