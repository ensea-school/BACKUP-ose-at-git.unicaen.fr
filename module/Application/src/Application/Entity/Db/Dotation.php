<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Interfaces\AnneeAwareInterface;
use Application\Entity\Db\Traits\AnneeAwareTrait;
use Application\Entity\Db\Traits\StructureAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * Dotation
 */
class Dotation implements HistoriqueAwareInterface, AnneeAwareInterface
{
    use AnneeAwareTrait;
    use StructureAwareTrait;
    use HistoriqueAwareTrait;

    /**
     * @var \DateTime
     */
    private $dateEffet;

    /**
     * @var float
     */
    private $heures;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\TypeDotation
     */
    private $type;




    /**
     * Set dateEffet
     *
     * @param \DateTime $dateEffet
     *
     * @return Dotation
     */
    public function setDateEffet($dateEffet)
    {
        $this->dateEffet = $dateEffet;

        return $this;
    }



    /**
     * Get dateEffet
     *
     * @return \DateTime
     */
    public function getDateEffet()
    {
        return $this->dateEffet;
    }



    /**
     * Set heures
     *
     * @param float $heures
     *
     * @return Dotation
     */
    public function setHeures($heures)
    {
        $this->heures = $heures;

        return $this;
    }



    /**
     * Get heures
     *
     * @return float
     */
    public function getHeures()
    {
        return $this->heures;
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
     * Set type
     *
     * @param \Application\Entity\Db\TypeDotation $type
     *
     * @return Dotation
     */
    public function setType(\Application\Entity\Db\TypeDotation $type = null)
    {
        $this->type = $type;

        return $this;
    }



    /**
     * Get type
     *
     * @return \Application\Entity\Db\TypeDotation
     */
    public function getType()
    {
        return $this->type;
    }
}
