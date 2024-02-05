<?php

namespace Formule\Entity\Db;

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
     * @var \Formule\Entity\Db\FormuleResultatIntervenant
     */
    private $formuleResultat;

    /**
     * @var \Enseignement\Entity\Db\VolumeHoraire
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
     * @return \Formule\Entity\Db\FormuleResultatIntervenant
     */
    public function getFormuleResultat()
    {
        return $this->formuleResultat;
    }



    /**
     * Get volumeHoraire
     *
     * @return \Enseignement\Entity\Db\VolumeHoraire
     */
    public function getVolumeHoraire()
    {
        return $this->volumeHoraire;
    }
}
