<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\TypeRessourceAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;

/**
 * TypeDotation
 */
class TypeDotation implements HistoriqueAwareInterface, ImportAwareInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;
    use TypeRessourceAwareTrait;


    const CODE_DOTATION_INITIALE = 'dotation-initiale';


    /**
     * @var string
     */
    private $libelle;

    /**
     * @var integer
     */
    private $id;



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
     *
     * @return TypeDotation
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



    public function isDotationInitiale()
    {
        return $this->getSourceCode() == self::CODE_DOTATION_INITIALE;
    }
}
