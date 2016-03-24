<?php

namespace Application\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Validation
 */
class Validation implements HistoriqueAwareInterface, ResourceInterface
{
    use HistoriqueAwareTrait;

    const RESOURCE_ID_VALIDATION_ENSEIGNEMENT  = 'VALIDATION_ENSEIGNEMENT';
    const RESOURCE_ID_VALIDATION_REFERENTIEL   = 'VALIDATION_REFERENTIEL';
    const RESOURCE_ID_CLOTURE_REALISE          = 'CLOTURE_REALISE';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Application\Entity\Db\Structure
     */
    private $structure;

    /**
     * @var \Application\Entity\Db\TypeValidation
     */
    private $typeValidation;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $volumeHoraire;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $volumeHoraireReferentiel;



    /**
     * Représentation littérale de cvet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf("Validation du %s par %s",
            $this->getHistoCreation()->format(\Application\Constants::DATETIME_FORMAT),
            $this->getHistoCreateur());
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
     * Set intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     *
     * @return Validation
     */
    public function setIntervenant(\Application\Entity\Db\Intervenant $intervenant = null)
    {
        $this->intervenant = $intervenant;

        return $this;
    }



    /**
     * Get intervenant
     *
     * @return \Application\Entity\Db\Intervenant
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }



    /**
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     *
     * @return Validation
     */
    public function setStructure(\Application\Entity\Db\Structure $structure = null)
    {
        $this->structure = $structure;

        return $this;
    }



    /**
     * Get structure
     *
     * @return \Application\Entity\Db\Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }



    /**
     * Set typeValidation
     *
     * @param \Application\Entity\Db\TypeValidation $typeValidation
     *
     * @return Validation
     */
    public function setTypeValidation(\Application\Entity\Db\TypeValidation $typeValidation = null)
    {
        $this->typeValidation = $typeValidation;

        return $this;
    }



    /**
     * Get typeValidation
     *
     * @return \Application\Entity\Db\TypeValidation
     */
    public function getTypeValidation()
    {
        return $this->typeValidation;
    }



    /**
     * Add volumeHoraire
     *
     * @param \Application\Entity\Db\VolumeHoraire $volumeHoraire
     *
     * @return self
     */
    public function addVolumeHoraire(\Application\Entity\Db\VolumeHoraire $volumeHoraire)
    {
        $this->volumeHoraire[] = $volumeHoraire;

        return $this;
    }



    /**
     * Remove volumeHoraire
     *
     * @param \Application\Entity\Db\VolumeHoraire $volumeHoraire
     */
    public function removeVolumeHoraire(\Application\Entity\Db\VolumeHoraire $volumeHoraire)
    {
        $this->volumeHoraire->removeElement($volumeHoraire);
    }



    /**
     * Get volumeHoraire
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVolumeHoraire()
    {
        return $this->volumeHoraire;
    }



    /**
     * Add volumeHoraireReferentiel
     *
     * @param \Application\Entity\Db\VolumeHoraireReferentiel $volumeHoraireReferentiel
     *
     * @return self
     */
    public function addVolumeHoraireReferentiel(\Application\Entity\Db\VolumeHoraireReferentiel $volumeHoraireReferentiel)
    {
        $this->volumeHoraireReferentiel[] = $volumeHoraireReferentiel;

        return $this;
    }



    /**
     * Remove volumeHoraireReferentiel
     *
     * @param \Application\Entity\Db\VolumeHoraireReferentiel $volumeHoraireReferentiel
     */
    public function removeVolumeHoraireReferentiel(\Application\Entity\Db\VolumeHoraireReferentiel $volumeHoraireReferentiel)
    {
        $this->volumeHoraireReferentiel->removeElement($volumeHoraireReferentiel);
    }



    /**
     * Get volumeHoraireReferentiel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVolumeHoraireReferentiel()
    {
        return $this->volumeHoraireReferentiel;
    }



    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     * @see ResourceInterface
     */
    public function getResourceId()
    {
        switch ($this->getTypeValidation()->getCode()) {
            case TypeValidation::CODE_ENSEIGNEMENT:
                return self::RESOURCE_ID_VALIDATION_ENSEIGNEMENT;

            case TypeValidation::CODE_REFERENTIEL:
                return self::RESOURCE_ID_VALIDATION_REFERENTIEL;

            case TypeValidation::CODE_CLOTURE_REALISE:
                return self::RESOURCE_ID_CLOTURE_REALISE;

            default:
                break;
        }

        return 'Validation';
    }
}
