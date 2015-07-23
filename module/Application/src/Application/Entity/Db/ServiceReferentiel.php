<?php

namespace Application\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Application\Entity\VolumeHoraireReferentielListe;

/**
 * ServiceReferentiel
 */
class ServiceReferentiel implements HistoriqueAwareInterface, ResourceInterface
{
    use HistoriqueAwareTrait;

    /**
     * @var string
     */
    protected $commentaires;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\IntervenantPermanent
     */
    protected $intervenant;

    /**
     * @var \Application\Entity\Db\Structure
     */
    protected $structure;

    /**
     * @var \Application\Entity\Db\FonctionReferentiel
     */
    protected $fonction;

    /**
     * Type de volume horaire
     *
     * @var TypeVolumeHoraire
     */
    protected $typeVolumeHoraire;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $volumeHoraireReferentiel;

    /**
     * @var FormuleServiceReferentiel
     */
    private $formuleServiceReferentiel;

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
     * @param \Application\Entity\Db\IntervenantPermanent $intervenant
     *
     * @return ServiceReferentiel
     */
    public function setIntervenant(\Application\Entity\Db\IntervenantPermanent $intervenant = null)
    {
        $this->intervenant = $intervenant;

        return $this;
    }



    /**
     * Get intervenant
     *
     * @return \Application\Entity\Db\IntervenantPermanent
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
     * @return ServiceReferentiel
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
     * Get formuleServiceReferentiel
     *
     * @return FormuleServiceReferentiel
     */
    public function getFormuleServiceReferentiel()
    {
        return $this->formuleServiceReferentiel;
    }



    /**
     *
     * @return TypeVolumeHoraire
     */
    public function getTypeVolumeHoraire()
    {
        return $this->typeVolumeHoraire;
    }



    /**
     *
     * @param TypeVolumeHoraire $typeVolumeHoraire
     *
     * @return self
     */
    public function setTypeVolumeHoraire(TypeVolumeHoraire $typeVolumeHoraire)
    {
        $this->typeVolumeHoraire = $typeVolumeHoraire;

        return $this;
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
