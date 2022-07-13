<?php

namespace Application\Entity\Db;

/**
 * FormuleResultatVolumeHoraire
 */
class FormuleResultatVolumeHoraire
{
    use FormuleResultatTypesHeuresTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\FormuleResultat
     */
    private $formuleResultat;

    /**
     * @var \Application\Entity\Db\VolumeHoraire
     */
    private $volumeHoraire;



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
     * Get formuleResultat
     *
     * @return \Application\Entity\Db\FormuleResultat 
     */
    public function getFormuleResultat()
    {
        return $this->formuleResultat;
    }

    /**
     * Get volumeHoraire
     *
     * @return \Application\Entity\Db\VolumeHoraire 
     */
    public function getVolumeHoraire()
    {
        return $this->volumeHoraire;
    }
}
