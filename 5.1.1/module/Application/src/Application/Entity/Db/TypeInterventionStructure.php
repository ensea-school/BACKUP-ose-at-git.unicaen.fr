<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\StructureAwareTrait;
use Application\Entity\Db\Traits\TypeInterventionAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * TypeInterventionStructure
 */
class TypeInterventionStructure implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;
    use TypeInterventionAwareTrait;
    use StructureAwareTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $visible;

    /**
     * @var Annee
     */
    private $anneeDebut;

    /**
     * @var Annee
     */
    private $anneeFin;



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
     * @return bool
     */
    public function isVisible()
    {
        return $this->visible;
    }



    /**
     * @param bool $visible
     *
     * @return TypeInterventionStructure
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }



    /**
     * @return Annee
     */
    public function getAnneeDebut()
    {
        return $this->anneeDebut;
    }



    /**
     * @param Annee $anneeDebut
     *
     * @return TypeInterventionStructure
     */
    public function setAnneeDebut(Annee $anneeDebut = null)
    {
        $this->anneeDebut = $anneeDebut;

        return $this;
    }



    /**
     * @return Annee
     */
    public function getAnneeFin()
    {
        return $this->anneeFin;
    }



    /**
     * @param Annee $anneeFin
     *
     * @return TypeInterventionStructure
     */
    public function setAnneeFin(Annee $anneeFin = null)
    {
        $this->anneeFin = $anneeFin;

        return $this;
    }

}
