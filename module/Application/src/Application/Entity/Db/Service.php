<?php

namespace Application\Entity\Db;

use Application\Entity\VolumeHoraireListe;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Service
 */
class Service implements HistoriqueAwareInterface, ResourceInterface
{
    use HistoriqueAwareTrait;

    const HORS_ETABLISSEMENT = "hors Établissement";

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $volumeHoraire;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    protected $intervenant;

    /**
     * @var \Application\Entity\Db\ElementPedagogique
     */
    protected $elementPedagogique;

    /**
     * @var \Application\Entity\Db\Etablissement
     */
    protected $etablissement;

    /**
     * Type de volume horaire
     *
     * @var TypeVolumeHoraire
     */
    protected $typeVolumeHoraire;

    /**
     * @var FormuleService
     */
    private $formuleService;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $formuleResultatService;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->volumeHoraire          = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formuleResultatService = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add volumeHoraire
     *
     * @param \Application\Entity\Db\VolumeHoraire $volumeHoraire
     *
     * @return Service
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
     * @param \Application\Entity\Db\Validation $validation
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVolumeHoraire(\Application\Entity\Db\Validation $validation = null)
    {
        if ($validation) {
            $closure = function (VolumeHoraire $vh) use ($validation) {
                return $vh->getValidation()->contains($validation);
            };

            return $this->volumeHoraire->filter($closure);
        }

        return $this->volumeHoraire;
    }



    /**
     *
     * @param Periode          $periode
     * @param TypeIntervention $typeIntervention
     *
     * @return \Application\Entity\VolumeHoraireListe
     */
    public function getVolumeHoraireListe(Periode $periode = null, TypeIntervention $typeIntervention = null)
    {
        $volumeHoraireListe = new VolumeHoraireListe($this);
        if ($this->getTypeVolumeHoraire()) $volumeHoraireListe->setTypeVolumeHoraire($this->getTypeVolumeHoraire());
        if ($periode) $volumeHoraireListe->setPeriode($periode);
        if ($typeIntervention) $volumeHoraireListe->setTypeIntervention($typeIntervention);

        return $volumeHoraireListe;
    }



    /**
     * Set intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     *
     * @return Service
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
     * Set elementPedagogique
     *
     * @param \Application\Entity\Db\ElementPedagogique $elementPedagogique
     *
     * @return Service
     */
    public function setElementPedagogique(\Application\Entity\Db\ElementPedagogique $elementPedagogique = null)
    {
        $this->elementPedagogique = $elementPedagogique;

        if ($elementPedagogique) {
            $vhl               = $this->getVolumeHoraireListe()->get();
            $typesIntervention = $elementPedagogique->getTypeIntervention();       // liste des types d'intervention de l'EP
            $periode           = $elementPedagogique->getPeriode();
            foreach ($vhl as $vh) {
                if (
                    (!$typesIntervention->contains($vh->getTypeIntervention())) // types d'intervention devenus obsolètes
                    || ($periode && $vh->getPeriode() != $periode)               // périodes devenues obsolètes
                ) {
                    $vh->setRemove(true); // Flag de demande de suppression du volume horaire lors de l'enregistrement de l'entité Service par son service Service
                }
            }
        }

        return $this;
    }



    /**
     * Get elementPedagogique
     *
     * @return \Application\Entity\Db\ElementPedagogique
     */
    public function getElementPedagogique()
    {
        return $this->elementPedagogique;
    }



    /**
     * Set etablissement
     *
     * @param \Application\Entity\Db\Etablissement $etablissement
     *
     * @return Service
     */
    public function setEtablissement(\Application\Entity\Db\Etablissement $etablissement = null)
    {
        $this->etablissement = $etablissement;

        return $this;
    }



    /**
     * Get etablissement
     *
     * @return \Application\Entity\Db\Etablissement
     */
    public function getEtablissement()
    {
        return $this->etablissement;
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
     * Get formuleService
     *
     * @return FormuleService
     */
    public function getFormuleService()
    {
        return $this->formuleService;
    }



    /**
     * Get formuleResultatService
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormuleResultatService(TypeVolumeHoraire $typeVolumeHoraire = null, EtatVolumeHoraire $etatVolumeHoraire = null)
    {
        $filter = function (FormuleResultatService $formuleResultatService) use ($typeVolumeHoraire, $etatVolumeHoraire) {
            if (isset($typeVolumeHoraire) && $typeVolumeHoraire !== $formuleResultatService->getFormuleResultat()->getTypeVolumeHoraire()) {
                return false;
            }
            if (isset($etatVolumeHoraire) && $etatVolumeHoraire !== $formuleResultatService->getFormuleResultat()->getEtatVolumeHoraire()) {
                return false;
            }

            return true;
        };

        return $this->formuleResultatService->filter($filter);
    }



    /**
     * Get formuleResultatService
     *
     * @return FormuleResultatService
     */
    public function getUniqueFormuleResultatService(TypeVolumeHoraire $typeVolumeHoraire, EtatVolumeHoraire $etatVolumeHoraire)
    {
        return $this->getFormuleResultatService($typeVolumeHoraire, $etatVolumeHoraire)->first();
    }



    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     * @see ResourceInterface
     */
    public function getResourceId()
    {
        return 'Service';
    }

}
