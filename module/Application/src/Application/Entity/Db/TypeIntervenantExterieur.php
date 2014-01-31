<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeIntervenantExterieur
 *
 * @ORM\Table(name="TYPE_INTERVENANT_EXTERIEUR")
 * @ORM\Entity
 */
class TypeIntervenantExterieur
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="TYPE_INTERVENANT_EXTERIEUR_ID_", allocationSize=1, initialValue=1)
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
     * @ORM\Column(name="LIMITE_HEURES_COMPLEMENTAIRES", type="float", precision=126, scale=0, nullable=false)
     */
    private $limiteHeuresComplementaires;



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
}
