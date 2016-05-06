<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;


/**
 * AdresseStructure
 */
class AdresseStructure implements HistoriqueAwareInterface, ImportAwareInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;

    /**
     * @var string
     */
    protected $codePostal;

    /**
     * @var string
     */
    protected $localite;

    /**
     * @var string
     */
    protected $nomVoie;

    /**
     * @var string
     */
    protected $noVoie;

    /**
     * @var string
     */
    protected $paysCodeInsee;

    /**
     * @var string
     */
    protected $paysLibelle;

    /**
     * @var boolean
     */
    protected $principale;

    /**
     * @var string
     */
    protected $telephone;

    /**
     * @var string
     */
    protected $ville;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\Structure
     */
    protected $structure;




    /**
     * Set codePostal
     *
     * @param string $codePostal
     *
     * @return AdresseStructure
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }



    /**
     * Get codePostal
     *
     * @return string
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }



    /**
     * Set localite
     *
     * @param string $localite
     *
     * @return AdresseStructure
     */
    public function setLocalite($localite)
    {
        $this->localite = $localite;

        return $this;
    }



    /**
     * Get localite
     *
     * @return string
     */
    public function getLocalite()
    {
        return $this->localite;
    }



    /**
     * Set nomVoie
     *
     * @param string $nomVoie
     *
     * @return AdresseStructure
     */
    public function setNomVoie($nomVoie)
    {
        $this->nomVoie = $nomVoie;

        return $this;
    }



    /**
     * Get nomVoie
     *
     * @return string
     */
    public function getNomVoie()
    {
        return $this->nomVoie;
    }



    /**
     * Set noVoie
     *
     * @param string $noVoie
     *
     * @return AdresseStructure
     */
    public function setNoVoie($noVoie)
    {
        $this->noVoie = $noVoie;

        return $this;
    }



    /**
     * Get noVoie
     *
     * @return string
     */
    public function getNoVoie()
    {
        return $this->noVoie;
    }



    /**
     * Set paysCodeInsee
     *
     * @param string $paysCodeInsee
     *
     * @return AdresseStructure
     */
    public function setPaysCodeInsee($paysCodeInsee)
    {
        $this->paysCodeInsee = $paysCodeInsee;

        return $this;
    }



    /**
     * Get paysCodeInsee
     *
     * @return string
     */
    public function getPaysCodeInsee()
    {
        return $this->paysCodeInsee;
    }



    /**
     * Set paysLibelle
     *
     * @param string $paysLibelle
     *
     * @return AdresseStructure
     */
    public function setPaysLibelle($paysLibelle)
    {
        $this->paysLibelle = $paysLibelle;

        return $this;
    }



    /**
     * Get paysLibelle
     *
     * @return string
     */
    public function getPaysLibelle()
    {
        return $this->paysLibelle;
    }



    /**
     * Set principale
     *
     * @param boolean $principale
     *
     * @return AdresseStructure
     */
    public function setPrincipale($principale)
    {
        $this->principale = $principale;

        return $this;
    }



    /**
     * Get principale
     *
     * @return boolean
     */
    public function getPrincipale()
    {
        return $this->principale;
    }



    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return AdresseStructure
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }



    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }



    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return AdresseStructure
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }



    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
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
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     *
     * @return AdresseStructure
     */
    public function setStructure(\Application\Entity\Db\Structure $structure = null)
    {
        $this->structure = $structure;

        return $this;
    }



    /**
     * Get structure
     *
     * @return \Application\Entity\Db\Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }

}
