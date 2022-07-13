<?php

namespace Application\Entity\Db;

use Application\Entity\VolumeHoraireListe;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraire;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;

/**
 * Service
 */
class Service implements HistoriqueAwareInterface, ResourceInterface, ImportAwareInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;

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
     * @var Intervenant
     */
    protected $intervenant;

    /**
     * @var ElementPedagogique
     */
    protected $elementPedagogique;

    /**
     * @var Etablissement
     */
    protected $etablissement;

    /**
     * @var string
     */
    protected $description;

    /**
     * Type de volume horaire
     *
     * @var TypeVolumeHoraire
     */
    protected $typeVolumeHoraire;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $formuleResultatService;

    /**
     * @var bool
     */
    private $changed = false;



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
     * @param VolumeHoraire $volumeHoraire
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
     * @param VolumeHoraire $volumeHoraire
     */
    public function removeVolumeHoraire(\Application\Entity\Db\VolumeHoraire $volumeHoraire)
    {
        $this->volumeHoraire->removeElement($volumeHoraire);
    }



    /**
     * Get volumeHoraire
     *
     * @param Validation $validation
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



    public function getOrderedVolumeHoraire(\Application\Entity\Db\Validation $validation = null)
    {
        $vhs = $this->getVolumeHoraire($validation);
        $res = [];
        foreach ($vhs as $vh) {
            $res[] = $vh;
        }

        usort($res, function (VolumeHoraire $a, VolumeHoraire $b) {
            if ($a->getHoraireDebut() != $b->getHoraireDebut()) {
                $ahd = $a->getHoraireDebut() ? $a->getHoraireDebut()->getTimestamp() : 999999999999999999999999;
                $bhd = $b->getHoraireDebut() ? $b->getHoraireDebut()->getTimestamp() : 999999999999999999999999;

                return $ahd - $bhd;
            }

            if ($a->getHoraireFin() != $b->getHoraireFin()) {
                $ahf = $a->getHoraireFin() ? $a->getHoraireFin()->getTimestamp() : 999999999999999999999999;
                $bhf = $b->getHoraireFin() ? $b->getHoraireFin()->getTimestamp() : 999999999999999999999999;

                return $ahf - $bhf;
            }

            if ($a->getTypeIntervention() != $b->getTypeIntervention()) {
                $ati = $a->getTypeIntervention() ? $a->getTypeIntervention()->getOrdre() : 999999999999999999999999;
                $bti = $b->getTypeIntervention() ? $b->getTypeIntervention()->getOrdre() : 999999999999999999999999;

                return $ati - $bti;
            }
        });

        return array_values($res);
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
     * @param Intervenant $intervenant
     *
     * @return Service
     */
    public function setIntervenant(\Application\Entity\Db\Intervenant $intervenant = null)
    {
        if ($this->intervenant !== $intervenant) {
            $this->intervenant = $intervenant;
            $this->changed     = true;
        }

        return $this;
    }



    /**
     * Get intervenant
     *
     * @return Intervenant
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }



    /**
     * Set elementPedagogique
     *
     * @param ElementPedagogique $elementPedagogique
     *
     * @return Service
     */
    public function setElementPedagogique(\Application\Entity\Db\ElementPedagogique $elementPedagogique = null)
    {
        if ($this->elementPedagogique !== $elementPedagogique) {
            $this->elementPedagogique = $elementPedagogique;
            $this->changed            = true;

            if ($elementPedagogique) {
                $vhl               = $this->getVolumeHoraireListe()->getVolumeHoraires();
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
        }

        return $this;
    }



    /**
     * Get elementPedagogique
     *
     * @return ElementPedagogique
     */
    public function getElementPedagogique()
    {
        return $this->elementPedagogique;
    }



    /**
     * Set etablissement
     *
     * @param Etablissement $etablissement
     *
     * @return Service
     */
    public function setEtablissement(\Application\Entity\Db\Etablissement $etablissement = null)
    {
        if ($this->etablissement !== $etablissement) {
            $this->etablissement = $etablissement;
            $this->changed       = true;
        }

        return $this;
    }



    /**
     * Get etablissement
     *
     * @return Etablissement
     */
    public function getEtablissement()
    {
        return $this->etablissement;
    }



    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }



    /**
     * @param string $description
     *
     * @return Service
     */
    public function setDescription($description)
    {
        if ($this->description != $description) {
            $this->description = $description;
            $this->changed     = true;
        }

        return $this;
    }



    /**
     * @return Structure|null
     */
    public function getStructure()
    {
        if ($this->getElementPedagogique()) {
            return $this->getElementPedagogique()->getStructure();
        }
        if ($this->getIntervenant()) {
            return $this->getIntervenant()->getStructure();
        }

        return null;
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



    /**
     * @return boolean
     */
    public function hasChanged()
    {
        return $this->changed;
    }



    /**
     * @param boolean $changed
     *
     * @return Service
     */
    public function setChanged($changed)
    {
        $this->changed = $changed;

        return $this;
    }

}
