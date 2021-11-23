<?php

namespace Application\Entity\Db;

/**
 * FormuleResultatVolumeHoraireReferentiel
 */
class FormuleResultatVolumeHoraireReferentiel
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
     * @var \Application\Entity\Db\VolumeHoraireReferentiel
     */
    private $volumeHoraireReferentiel;


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
     * Get volumeHoraireReferentiel
     *
     * @return \Application\Entity\Db\VolumeHoraireReferentiel 
     */
    public function getVolumeHoraireReferentiel()
    {
        return $this->volumeHoraireReferentiel;
    }
}
