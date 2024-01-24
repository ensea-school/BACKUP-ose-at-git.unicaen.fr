<?php

namespace Formule\Entity\Db;

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
     * @var \Formule\Entity\Db\FormuleResultat
     */
    private $formuleResultat;

    /**
     * @var \Referentiel\Entity\Db\VolumeHoraireReferentiel
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
     * @return \Formule\Entity\Db\FormuleResultat
     */
    public function getFormuleResultat()
    {
        return $this->formuleResultat;
    }

    /**
     * Get volumeHoraireReferentiel
     *
     * @return \Referentiel\Entity\Db\VolumeHoraireReferentiel 
     */
    public function getVolumeHoraireReferentiel()
    {
        return $this->volumeHoraireReferentiel;
    }
}
