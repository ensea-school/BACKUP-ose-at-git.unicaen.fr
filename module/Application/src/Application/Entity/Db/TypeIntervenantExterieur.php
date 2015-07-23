<?php

namespace Application\Entity\Db;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * TypeIntervenantExterieur
 */
class TypeIntervenantExterieur implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    /**
     * @var string
     */
    protected $libelle;

    /**
     * @var float
     */
    protected $limiteHeuresComplementaires;

    /**
     * @var integer
     */
    protected $id;



    /**
     * Set libelle
     *
     * @param string $libelle
     *
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
     *
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

}
