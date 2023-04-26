<?php

namespace Contrat\Entity\Db;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * TypeContrat
 */
class TypeContrat implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    const CODE_CONTRAT = 'CONTRAT';
    const CODE_AVENANT = 'AVENANT';

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var integer
     */
    private $id;



    /**
     * Set code
     *
     * @param string $code
     *
     * @return TypeContrat
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }



    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }



    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return TypeContrat
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }



    /**
     * Retourne <code>true</code> si ce contrat est en réalité un avenant.
     *
     * @return boolean
     */
    public function estUnAvenant()
    {
        return self::CODE_AVENANT === $this->getCode();
    }
}
