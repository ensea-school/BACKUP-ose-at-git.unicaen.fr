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
     * @var boolean
     */
    private $premierRecrutement;

    /**
     * @var float
     */
    private $seuilHetd;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\TypeAgrement
     */
    private $type;

    /**
     * @var \Application\Entity\Db\StatutIntervenant
     */
    private $statut;



    /**
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf("Id=%s, Statut=%s, Type=%s, Oblig=%d, 1erRecrut=%d, Seuil=%s",
            $this->getId(),
            sprintf("%s (%s)", $this->getStatut(), $this->getStatut()->getId()),
            sprintf("%s (%s)", $this->getType(), $this->getType()->getId()),
            $this->getObligatoire(),
            $this->getPremierRecrutement(),
            $this->getSeuilHeures() ?: "Aucun");
    }



    /**
     * Get seuilHeures
     *
     * @return integer
     */
    public function getSeuilHeures()
    {
        return $this->seuilHetd;
    }



    /**
     * Set premierRecrutement
     *
     * @param boolean $premierRecrutement
     *
     * @return TypeAgrementStatut
     */
    public function setPremierRecrutement($premierRecrutement)
    {
        $this->premierRecrutement = $premierRecrutement;

        return $this;
    }



    /**
     * Get premierRecrutement
     *
     * @return boolean
     */
    public function getPremierRecrutement()
    {
        return $this->premierRecrutement;
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
     * Set seuilHetd
     *
     * @param integer $seuilHetd
     *
     * @return TypeAgrementStatut
     */
    public function setSeuilHetd($seuilHetd)
    {
        $this->seuilHetd = $seuilHetd;

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
     * @param \Application\Entity\Db\StatutIntervenant $statut
     *
     * @return TypeAgrementStatut
     */
    public function setStatut(\Application\Entity\Db\StatutIntervenant $statut = null)
    {
        $this->statut = $statut;

        return $this;
    }



    /**
     * Get statutIntervenant
     *
     * @return \Application\Entity\Db\StatutIntervenant
     */
    public function getStatut()
    {
        return $this->statut;
    }
}
