<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeIntervenantExterieur
 */
class TypeIntervenantExterieur
{
    /**
     * @var string
     */
    private $libelle;

    /**
     * @var float
     */
    private $limiteHeuresComplementaires;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Historique
     */
    private $historique;


    /**
     * Set libelle
     *
     * @param string $libelle
     * @return TypeIntervenantExterieur
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
     * Set limiteHeuresComplementaires
     *
     * @param float $limiteHeuresComplementaires
     * @return TypeIntervenantExterieur
     */
    public function setLimiteHeuresComplementaires($limiteHeuresComplementaires)
    {
        $this->limiteHeuresComplementaires = $limiteHeuresComplementaires;

        return $this;
    }

    /**
     * Get limiteHeuresComplementaires
     *
     * @return float 
     */
    public function getLimiteHeuresComplementaires()
    {
        return $this->limiteHeuresComplementaires;
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
     * Set historique
     *
     * @param \Application\Entity\Db\Historique $historique
     * @return TypeIntervenantExterieur
     */
    public function setHistorique(\Application\Entity\Db\Historique $historique = null)
    {
        $this->historique = $historique;

        return $this;
    }

    /**
     * Get historique
     *
     * @return \Application\Entity\Db\Historique 
     */
    public function getHistorique()
    {
        return $this->historique;
    }
}
