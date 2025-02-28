<?php


namespace Workflow\Entity\Db;

use Laminas\Permissions\Acl\Resource\ResourceInterface;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenVue\Axios\AxiosExtractorInterface;

/**
 * Validation
 */
class Validation implements HistoriqueAwareInterface, ResourceInterface, AxiosExtractorInterface
{
    use HistoriqueAwareTrait;

    const RESOURCE_ID_VALIDATION_ENSEIGNEMENT = 'VALIDATION_ENSEIGNEMENT';
    const RESOURCE_ID_VALIDATION_REFERENTIEL  = 'VALIDATION_REFERENTIEL';
    const RESOURCE_ID_CLOTURE_REALISE         = 'CLOTURE_REALISE';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Intervenant\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Lieu\Entity\Db\Structure
     */
    private $structure;

    /**
     * @var \Workflow\Entity\Db\TypeValidation
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



    public function axiosDefinition(): array
    {
        return ['histoCreation', 'histoCreateur'];
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
     * @param \Intervenant\Entity\Db\Intervenant $intervenant
     *
     * @return Validation
     */
    public function setIntervenant(\Intervenant\Entity\Db\Intervenant $intervenant = null)
    {
        $this->intervenant = $intervenant;

        return $this;
    }



    /**
     * Get intervenant
     *
     * @return \Intervenant\Entity\Db\Intervenant
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }



    /**
     * Set structure
     *
     * @param \Lieu\Entity\Db\Structure $structure
     *
     * @return Validation
     */
    public function setStructure(\Lieu\Entity\Db\Structure $structure = null)
    {
        $this->structure = $structure;

        return $this;
    }



    /**
     * Get structure
     *
     * @return \Lieu\Entity\Db\Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }



    /**
     * Set typeValidation
     *
     * @param \Workflow\Entity\Db\TypeValidation $typeValidation
     *
     * @return Validation
     */
    public function setTypeValidation(\Workflow\Entity\Db\TypeValidation $typeValidation = null)
    {
        $this->typeValidation = $typeValidation;

        return $this;
    }



    /**
     * Get typeValidation
     *
     * @return \Workflow\Entity\Db\TypeValidation
     */
    public function getTypeValidation()
    {
        return $this->typeValidation;
    }



    /**
     * Add volumeHoraire
     *
     * @param \Enseignement\Entity\Db\VolumeHoraire $volumeHoraire
     *
     * @return self
     */
    public function addVolumeHoraire(\Enseignement\Entity\Db\VolumeHoraire $volumeHoraire)
    {
        $this->volumeHoraire[] = $volumeHoraire;

        return $this;
    }



    /**
     * Remove volumeHoraire
     *
     * @param \Enseignement\Entity\Db\VolumeHoraire $volumeHoraire
     */
    public function removeVolumeHoraire(\Enseignement\Entity\Db\VolumeHoraire $volumeHoraire)
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
     * @param \Referentiel\Entity\Db\VolumeHoraireReferentiel $volumeHoraireReferentiel
     *
     * @return self
     */
    public function addVolumeHoraireReferentiel(\Referentiel\Entity\Db\VolumeHoraireReferentiel $volumeHoraireReferentiel)
    {
        $this->volumeHoraireReferentiel[] = $volumeHoraireReferentiel;

        return $this;
    }



    /**
     * Remove volumeHoraireReferentiel
     *
     * @param \Referentiel\Entity\Db\VolumeHoraireReferentiel $volumeHoraireReferentiel
     */
    public function removeVolumeHoraireReferentiel(\Referentiel\Entity\Db\VolumeHoraireReferentiel $volumeHoraireReferentiel)
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
        return 'Validation';
    }
}
