<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Application\Entity\Db\Traits\StructureAwareTrait;
use Application\Entity\Db\Traits\TypeVolumeHoraireAwareTrait;
use Service\Entity\Db\TypeVolumeHoraire;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Application\Entity\VolumeHoraireReferentielListe;

/**
 * ServiceReferentiel
 */
class ServiceReferentiel implements HistoriqueAwareInterface, ResourceInterface, ImportAwareInterface
{
    use HistoriqueAwareTrait;
    use IntervenantAwareTrait;
    use StructureAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use ImportAwareTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $commentaires;

    /**
     * @var string
     */
    protected $formation;

    /**
     * @var \Application\Entity\Db\FonctionReferentiel
     */
    protected $fonction;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $volumeHoraireReferentiel;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $formuleResultatServiceReferentiel;



    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        $heures = \UnicaenApp\Util::formattedFloat($this->getHeures(), \NumberFormatter::DECIMAL, -1);

        return sprintf("%s%s : %s (%sh)",
            $this->getStructure() ? " - " . $this->getStructure() : null,
            $this->getFonction(),
            $heures);
    }



    /**
     *
     */
    public function __construct()
    {
        $this->volumeHoraireReferentiel          = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formuleResultatServiceReferentiel = new \Doctrine\Common\Collections\ArrayCollection();
    }



    /**
     * Set commentaires
     *
     * @param string $commentaires
     *
     * @return ServiceReferentiel
     */
    public function setCommentaires($commentaires)
    {
        $this->commentaires = $commentaires;

        return $this;
    }



    /**
     * Get commentaires
     *
     * @return string
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }



    /**
     * @return string
     */
    public function getFormation()
    {
        return $this->formation;
    }



    /**
     * @param string $formation
     *
     * @return ServiceReferentiel
     */
    public function setFormation($formation): ServiceReferentiel
    {
        $this->formation = $formation;

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
     * Set fonction
     *
     * @param \Application\Entity\Db\FonctionReferentiel $fonction
     *
     * @return ServiceReferentiel
     */
    public function setFonction(\Application\Entity\Db\FonctionReferentiel $fonction = null)
    {
        $this->fonction = $fonction;

        return $this;
    }



    /**
     * Get fonction
     *
     * @return \Application\Entity\Db\FonctionReferentiel
     */
    public function getFonction()
    {
        return $this->fonction;
    }



    /**
     * Add volumeHoraireReferentiel
     *
     * @param VolumeHoraireReferentiel $volumeHoraireReferentiel
     *
     * @return Service
     */
    public function addVolumeHoraireReferentiel(VolumeHoraireReferentiel $volumeHoraireReferentiel)
    {
        $this->volumeHoraireReferentiel[] = $volumeHoraireReferentiel;

        return $this;
    }



    /**
     * Remove volumeHoraireReferentiel
     *
     * @param VolumeHoraireReferentiel $volumeHoraireReferentiel
     */
    public function removeVolumeHoraireReferentiel(VolumeHoraireReferentiel $volumeHoraireReferentiel)
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
     * Détermine si le VHR a une validation ou non
     *
     * @return boolean
     */
    public function hasValidation()
    {
        $volumesHoraires = $this->getVolumeHoraireReferentiel();
        foreach ($volumesHoraires as $volumeHoraire) {
            /* @var $volumeHoraire VolumeHoraireReferentiel */
            if ($volumeHoraire->hasValidation()) {
                return true;
            }
        }

        return false;
    }



    /**
     *
     * @return VolumeHoraireReferentielListe
     */
    public function getVolumeHoraireReferentielListe()
    {
        $volumeHoraireListe = new VolumeHoraireReferentielListe($this);

        if ($this->getTypeVolumeHoraire()) {
            $volumeHoraireListe->setTypeVolumeHoraire($this->getTypeVolumeHoraire());
        }

        return $volumeHoraireListe;
    }



    /**
     * Get formuleResultatServiceReferentiel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormuleResultatServiceReferentiel(TypeVolumeHoraire $typeVolumeHoraire = null, EtatVolumeHoraire $etatVolumeHoraire = null)
    {
        $filter = function (FormuleResultatServiceReferentiel $formuleResultatServiceReferentiel) use ($typeVolumeHoraire, $etatVolumeHoraire) {
            if (isset($typeVolumeHoraire) && $typeVolumeHoraire !== $formuleResultatServiceReferentiel->getFormuleResultat()->getTypeVolumeHoraire()) {
                return false;
            }
            if (isset($etatVolumeHoraire) && $etatVolumeHoraire !== $formuleResultatServiceReferentiel->getFormuleResultat()->getEtatVolumeHoraire()) {
                return false;
            }

            return true;
        };

        return $this->formuleResultatServiceReferentiel->filter($filter);
    }



    /**
     * Get formuleResultatReferentiel
     *
     * @return FormuleResultatServiceReferentiel
     */
    public function getUniqueFormuleResultatServiceReferentiel(TypeVolumeHoraire $typeVolumeHoraire, EtatVolumeHoraire $etatVolumeHoraire)
    {
        return $this->getFormuleResultatServiceReferentiel($typeVolumeHoraire, $etatVolumeHoraire)->first();
    }



    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     * @see ResourceInterface
     */
    public function getResourceId()
    {
        return 'ServiceReferentiel';
    }
}
