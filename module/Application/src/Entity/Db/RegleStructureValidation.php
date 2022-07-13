<?php

namespace Application\Entity\Db;

use Intervenant\Entity\Db\TypeIntervenant;
use Intervenant\Entity\Db\TypeIntervenantAwareTrait;
use Application\Entity\Db\Traits\TypeVolumeHoraireAwareTrait;
use Service\Entity\Db\TypeVolumeHoraire;

/**
 * RegleStructureValidation
 */
class RegleStructureValidation
{

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $priorite;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var TypeVolumeHoraire
     */

    protected $typeVolumeHoraire;

    /**
     * @var TypeIntervenant
     */
    protected $typeIntervenant;



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * @param int $id
     *
     * @return RegleStructureValidation
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }



    /**
     * @return string
     */
    public function getPriorite()
    {
        return $this->priorite;
    }



    /**
     * @param string $priorite
     *
     * @return RegleStructureValidation
     */
    public function setPriorite($priorite)
    {
        $this->priorite = $priorite;

        return $this;
    }



    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }



    /**
     * @param string $message
     *
     * @return RegleStructureValidation
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }



    /**
     * @return TypeVolumeHoraire
     */
    public function getTypeVolumeHoraire()
    {
        return $this->typeVolumeHoraire;
    }



    /**
     * @param TypeVolumeHoraire $typeVolumeHoraire
     */
    public function setTypeVolumeHoraire(TypeVolumeHoraire $typeVolumeHoraire)
    {
        $this->typeVolumeHoraire = $typeVolumeHoraire;

        return $this;
    }



    /**
     * @return TypeIntervenant
     */
    public function getTypeIntervenant()
    {
        return $this->typeIntervenant;
    }



    /**
     * @param TypeIntervenant $typeIntervenant
     */
    public function setTypeIntervenant(TypeIntervenant $typeIntervenant)
    {
        $this->typeIntervenant = $typeIntervenant;

        return $this;
    }

}
