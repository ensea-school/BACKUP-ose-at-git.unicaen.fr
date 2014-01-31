<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * RegimeSecu
 *
 * @ORM\Table(name="REGIME_SECU")
 * @ORM\Entity
 */
class RegimeSecu
{
    /**
     * @var string
     *
     * @ORM\Column(name="ID", type="string", length=2, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="REGIME_SECU_ID_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="LIBELLE", type="string", length=60, nullable=false)
     */
    private $libelle;

    /**
     * @var float
     *
     * @ORM\Column(name="TAUX_TAXE", type="float", precision=126, scale=0, nullable=false)
     */
    private $tauxTaxe;



    /**
     * Get id
     *
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return RegimeSecu
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set tauxTaxe
     *
     * @param float $tauxTaxe
     * @return RegimeSecu
     */
    public function setTauxTaxe($tauxTaxe)
    {
        $this->tauxTaxe = $tauxTaxe;

        return $this;
    }

    /**
     * Get tauxTaxe
     *
     * @return float 
     */
    public function getTauxTaxe()
    {
        return $this->tauxTaxe;
    }
}
