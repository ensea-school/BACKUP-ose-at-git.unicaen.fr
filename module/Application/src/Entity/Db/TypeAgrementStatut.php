<?php

namespace Application\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * TypeAgrementStatut
 */
class TypeAgrementStatut implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;
    use \Application\Traits\ObligatoireSelonSeuilHeuresAwareTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\TypeAgrement
     */
    private $type;

    /**
     * @var \Application\Entity\Db\Statut
     */
    private $statut;

    /**
     * @var integer
     */
    private $obligatoire;

    /**
     * @var dureeVie
     */
    private $dureeVie;



    /**
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf("Id=%s, Statut=%s, Type=%s, Oblig=%d, 1erRecrut=%d",
            $this->getId(),
            sprintf("%s (%s)", $this->getStatut(), $this->getStatut()->getId()),
            sprintf("%s (%s)", $this->getType(), $this->getType()->getId()),
        );
    }



    /**
     * Set obligatoire
     *
     * @param boolean $obligatoire
     *
     * @return TypeAgrementStatut
     */
    public function setObligatoire($obligatoire)
    {
        $this->obligatoire = $obligatoire;

        return $this;
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
     * @param \Application\Entity\Db\TypeAgrement $type
     *
     * @return TypeAgrementStatut
     */
    public function setType(\Application\Entity\Db\TypeAgrement $type = null)
    {
        $this->type = $type;

        return $this;
    }



    /**
     * Get type
     *
     * @return \Application\Entity\Db\TypeAgrement
     */
    public function getType()
    {
        return $this->type;
    }



    /**
     * Set statutIntervenant
     *
     * @param \Application\Entity\Db\Statut $statut
     *
     * @return TypeAgrementStatut
     */
    public function setStatut(\Application\Entity\Db\Statut $statut = null)
    {
        $this->statut = $statut;

        return $this;
    }



    /**
     * Get statutIntervenant
     *
     * @return \Application\Entity\Db\Statut
     */
    public function getStatut()
    {
        return $this->statut;
    }



    /**
     * Get dureeVie
     *
     * @return integer
     */
    public function getDureeVie()
    {
        return $this->dureeVie;
    }



    /**
     * Set dureeVie
     *
     * @param integer dureeVie
     *
     * @return TypeAgrementStatut
     */
    public function setDureeVie($dureeVie)
    {
        $this->dureeVie = $dureeVie;

        return $this;
    }
}
