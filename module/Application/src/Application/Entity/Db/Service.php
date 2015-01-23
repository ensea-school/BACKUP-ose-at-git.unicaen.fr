<?php

namespace Application\Entity\Db;

use Application\Entity\VolumeHoraireListe;
use Application\Entity\Db\Periode;
use Application\Entity\Db\TypeIntervention;
use Application\Entity\Db\TypeVolumeHoraire;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Service
 */
class Service implements HistoriqueAwareInterface, ResourceInterface
{
    /**
     * @var \DateTime
     */
    protected $histoCreation;

    /**
     * @var \DateTime
     */
    protected $histoDestruction;

    /**
     * @var \DateTime
     */
    protected $histoModification;

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
     * @var \Application\Entity\Db\Structure
     */
    protected $structureAff;

    /**
     * @var \Application\Entity\Db\Structure
     */
    protected $structureEns;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoModificateur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoDestructeur;

    /**
     * @var \Application\Entity\Db\ElementPedagogique
     */
    protected $elementPedagogique;

    /**
     * @var \Application\Entity\Db\Etablissement
     */
    protected $etablissement;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoCreateur;

    /**
     * @var \Application\Entity\Db\Annee
     */
    protected $annee;

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
        $this->volumeHoraire = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formuleResultatService = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set histoCreation
     *
     * @param \DateTime $histoCreation
     * @return Service
     */
    public function setHistoCreation($histoCreation)
    {
        $this->histoCreation = $histoCreation;

        return $this;
    }

    /**
     * Get histoCreation
     *
     * @return \DateTime 
     */
    public function getHistoCreation()
    {
        return $this->histoCreation;
    }

    /**
     * Set histoDestruction
     *
     * @param \DateTime $histoDestruction
     * @return Service
     */
    public function setHistoDestruction($histoDestruction)
    {
        $this->histoDestruction = $histoDestruction;

        return $this;
    }

    /**
     * Get histoDestruction
     *
     * @return \DateTime 
     */
    public function getHistoDestruction()
    {
        return $this->histoDestruction;
    }

    /**
     * Set histoModification
     *
     * @param \DateTime $histoModification
     * @return Service
     */
    public function setHistoModification($histoModification)
    {
        $this->histoModification = $histoModification;

        return $this;
    }

    /**
     * Get histoModification
     *
     * @return \DateTime 
     */
    public function getHistoModification()
    {
        return $this->histoModification;
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
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVolumeHoraire(\Application\Entity\Db\Validation $validation = null)
    {
        if ($validation) {
            $closure = function (VolumeHoraire $vh) use ($validation) { return $vh->getValidation()->contains($validation); };
            return $this->volumeHoraire->filter($closure);
        }
        return $this->volumeHoraire;
    }

    /**
     *
     * @param \Application\Entity\Db\Periode $periode
     * @param TypeIntervention $typeIntervention
     * @return VolumeHoraireListe
     */
    public function getVolumeHoraireListe( Periode $periode=null, TypeIntervention $typeIntervention=null )
    {
        $volumeHoraireListe = new VolumeHoraireListe( $this );
        if ($this->getTypeVolumeHoraire()) $volumeHoraireListe->setTypeVolumeHoraire($this->getTypeVolumeHoraire());
        if ($periode)           $volumeHoraireListe->setPeriode($periode);
        if ($typeIntervention)  $volumeHoraireListe->setTypeIntervention($typeIntervention);
        return $volumeHoraireListe;
    }

    /**
     * Set intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @return Service
     */
    public function setIntervenant(\Application\Entity\Db\Intervenant $intervenant = null)
    {
        $this->intervenant = $intervenant;
        if ($intervenant && ! $this->getStructureAff()){
            $this->setStructureAff( $intervenant->getStructure() );
        }
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
     * Set structureAff
     *
     * @param \Application\Entity\Db\Structure $structureAff
     * @return Service
     */
    public function setStructureAff(\Application\Entity\Db\Structure $structureAff = null)
    {
        $this->structureAff = $structureAff;

        return $this;
    }

    /**
     * Get structureAff
     *
     * @return \Application\Entity\Db\Structure 
     */
    public function getStructureAff()
    {
        return $this->structureAff;
    }

    /**
     * Set structureEns
     *
     * @param \Application\Entity\Db\Structure $structureEns
     * @return Service
     */
    public function setStructureEns(\Application\Entity\Db\Structure $structureEns = null)
    {
        $this->structureEns = $structureEns;

        return $this;
    }

    /**
     * Get structureEns
     *
     * @return \Application\Entity\Db\Structure 
     */
    public function getStructureEns()
    {
        return $this->structureEns;
    }

    /**
     * Set histoModificateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoModificateur
     * @return Service
     */
    public function setHistoModificateur(\Application\Entity\Db\Utilisateur $histoModificateur = null)
    {
        $this->histoModificateur = $histoModificateur;

        return $this;
    }

    /**
     * Get histoModificateur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getHistoModificateur()
    {
        return $this->histoModificateur;
    }

    /**
     * Set histoDestructeur
     *
     * @param \Application\Entity\Db\Utilisateur $histoDestructeur
     * @return Service
     */
    public function setHistoDestructeur(\Application\Entity\Db\Utilisateur $histoDestructeur = null)
    {
        $this->histoDestructeur = $histoDestructeur;

        return $this;
    }

    /**
     * Get histoDestructeur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getHistoDestructeur()
    {
        return $this->histoDestructeur;
    }

    /**
     * Set elementPedagogique
     *
     * @param \Application\Entity\Db\ElementPedagogique $elementPedagogique
     * @return Service
     */
    public function setElementPedagogique(\Application\Entity\Db\ElementPedagogique $elementPedagogique = null)
    {
        $this->elementPedagogique = $elementPedagogique;

        if( $elementPedagogique){
            $vhl = $this->getVolumeHoraireListe()->get();
            $typesIntervention = $elementPedagogique->getTypeIntervention();       // liste des types d'intervention de l'EP
            $periode = $elementPedagogique->getPeriode();
            foreach( $vhl as $vh ){
                if (
                    ( ! $typesIntervention->contains($vh->getTypeIntervention()) ) // types d'intervention devenus obsolètes
                    || ( $periode && $vh->getPeriode() != $periode )               // périodes devenues obsolètes
                ){
                    $vh->setRemove(true); // Flag de demande de suppression du volume horaire lors de l'enregistrement de l'entité Service par son service Service
                }
            }

            if (! $this->getStructureEns()) $this->setStructureEns( $elementPedagogique->getStructure() );
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
     * Set histoCreateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoCreateur
     * @return Service
     */
    public function setHistoCreateur(\Application\Entity\Db\Utilisateur $histoCreateur = null)
    {
        $this->histoCreateur = $histoCreateur;

        return $this;
    }

    /**
     * Get histoCreateur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getHistoCreateur()
    {
        return $this->histoCreateur;
    }

    /**
     * Set annee
     *
     * @param \Application\Entity\Db\Annee $annee
     * @return Service
     */
    public function setAnnee(\Application\Entity\Db\Annee $annee = null)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return \Application\Entity\Db\Annee 
     */
    public function getAnnee()
    {
        return $this->annee;
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
    public function getFormuleResultatService(TypeVolumeHoraire $typeVolumeHoraire=null, EtatVolumeHoraire $etatVolumeHoraire=null )
    {
        $filter = function( FormuleResultatService $formuleResultatService ) use ($typeVolumeHoraire, $etatVolumeHoraire) {
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
    public function getUniqueFormuleResultatService(TypeVolumeHoraire $typeVolumeHoraire, EtatVolumeHoraire $etatVolumeHoraire )
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
