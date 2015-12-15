<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\SourceAwareTrait;
use Application\Entity\Db\Traits\TypeRessourceAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * TypeDotation
 */
class TypeDotation implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;
    use SourceAwareTrait;
    use TypeRessourceAwareTrait;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var string
     */
    private $sourceCode;

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



    /**
     * Set sourceCode
     *
     * @param string $sourceCode
     *
     * @return TypeDotation
     */
    public function setSourceCode($sourceCode)
    {
        $this->sourceCode = $sourceCode;

        return $this;
    }



    /**
     * Get sourceCode
     *
     * @return string
     */
    public function getSourceCode()
    {
        return $this->sourceCode;
    }

}
