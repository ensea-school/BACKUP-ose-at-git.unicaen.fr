<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\DisciplineAwareTrait;
use Application\Entity\Db\Traits\DossierAwareTrait;
use Application\Entity\Db\Traits\GradeAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;
use Zend\Form\Annotation;
use Application\Constants;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Application\Entity\Db\Interfaces\AnneeAwareInterface;

/**
 * Intervenant
 *
 * @Annotation\Name("intervenant")
 * @Annotation\Type("Application\Form\Intervenant\AjouterModifier")
 * @Annotation\Hydrator("Application\Entity\Db\Hydrator\Intervenant")
 */
class Intervenant implements IntervenantInterface, HistoriqueAwareInterface, ResourceInterface, AnneeAwareInterface, ImportAwareInterface
{
    use HistoriqueAwareTrait;
    use GradeAwareTrait;
    use DisciplineAwareTrait;
    use DossierAwareTrait;
    use ImportAwareTrait;

    /**
     * @var \DateTime
     * @Annotation\Type("UnicaenApp\Form\Element\DateInfSup")
     * @Annotation\Options({"date_inf_label":"Date de naissance :"})
     */
    protected $dateNaissance;

    /**
     * @var string
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Département de naissance (code INSEE) :"})
     */
    protected $depNaissanceCodeInsee;

    /**
     * @var string
     */
    protected $depNaissanceLibelle;

    /**
     * @var string
     * @Annotation\Type("Zend\Form\Element\Email")
     * @Annotation\Validator({"name":"EmailAddress"})
     * @Annotation\Options({"label":"Adresse mail :"})
     */
    protected $email;

    /**
     * @var string
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Nom patronymique :"})
     */
    protected $nomPatronymique;

    /**
     * @var string
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Nom usuel :"})
     */
    protected $nomUsuel;

    /**
     * @var string
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Numéro INSEE :"})
     */
    protected $numeroInsee;

    /**
     * @var string
     */
    protected $numeroInseeCle;

    /**
     * @var boolean
     */
    protected $numeroInseeProvisoire;

    /**
     * @var string
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Pays de naissance (code Insee) :"})
     */
    protected $paysNaissanceCodeInsee;

    /**
     * @var string
     */
    protected $paysNaissanceLibelle;

    /**
     * @var string
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Pays de nationalité (code Insee) :"})
     */
    protected $paysNationaliteCodeInsee;

    /**
     * @var string
     */
    protected $paysNationaliteLibelle;

    /**
     * @var string
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":1, "max":25}})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Prénom :"})
     */
    protected $prenom;

    /**
     * @var string
     */
    protected $telMobile;

    /**
     * @var string
     */
    protected $telPro;

    /**
     * @var string
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"VIlle de naissance (code Insee) :"})
     */
    protected $villeNaissanceCodeInsee;

    /**
     * @var string
     */
    protected $villeNaissanceLibelle;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\Annee
     */
    protected $annee;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $affectation;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $adresse;

    /**
     * @var \Application\Entity\Db\Adresse
     */
    protected $adressePrinc;

    /**
     * @var \Application\Entity\Db\StatutIntervenant
     */
    protected $statut;

    /**
     * @var \Application\Entity\Db\Structure
     */
    protected $structure;

    /**
     * @var \Application\Entity\Db\Civilite
     * @Annotation\Type("Zend\Form\Element\Select")
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Civilité :"})
     */
    protected $civilite;

    /**
     * @var string
     */
    protected $BIC;

    /**
     * @var string
     */
    protected $IBAN;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $service;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $histoService;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $serviceReferentiel;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $validation;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $agrement;

    /**
     * @var boolean
     */
    protected $premierRecrutement;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $formuleReferentiel;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $formuleResultat;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $formuleIntervenant;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $vIndicDiffDossier;

    /**
     * @var \Application\Entity\Db\IndicModifDossier
     */
    private $indicModifDossier;

    /**
     * miseEnPaiementIntervenantStructure
     *
     * @var MiseEnPaiementIntervenantStructure
     */
    protected $miseEnPaiementIntervenantStructure;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $vIndicAttenteDemandeMep;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $vIndicAttenteMep;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $vIndicDepassHcHorsRemuFc;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $vIndicDepassRef;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $modificationServiceDu;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $contrat;

    /**
     * @var float
     */
    protected $montantIndemniteFc;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->affectation                        = new \Doctrine\Common\Collections\ArrayCollection();
        $this->adresse                            = new \Doctrine\Common\Collections\ArrayCollection();
        $this->validation                         = new \Doctrine\Common\Collections\ArrayCollection();
        $this->agrement                           = new \Doctrine\Common\Collections\ArrayCollection();
        $this->service                            = new \Doctrine\Common\Collections\ArrayCollection();
        $this->histoService                       = new \Doctrine\Common\Collections\ArrayCollection();
        $this->serviceReferentiel                 = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formuleResultat                    = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formuleIntervenant                 = new \Doctrine\Common\Collections\ArrayCollection();
        $this->miseEnPaiementIntervenantStructure = new \Doctrine\Common\Collections\ArrayCollection();
        $this->vIndicAttenteDemandeMep            = new \Doctrine\Common\Collections\ArrayCollection();
        $this->vIndicAttenteMep                   = new \Doctrine\Common\Collections\ArrayCollection();
        $this->modificationServiceDu              = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contrat                            = new \Doctrine\Common\Collections\ArrayCollection();
        $this->vIndicDiffDossier                  = new \Doctrine\Common\Collections\ArrayCollection();
    }



    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     *
     * @return Intervenant
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }



    /**
     * Get dateNaissance
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }



    /**
     * Set depNaissanceCodeInsee
     *
     * @param string $depNaissanceCodeInsee
     *
     * @return Intervenant
     */
    public function setDepNaissanceCodeInsee($depNaissanceCodeInsee)
    {
        $this->depNaissanceCodeInsee = $depNaissanceCodeInsee;

        return $this;
    }



    /**
     * Get depNaissanceCodeInsee
     *
     * @return string
     */
    public function getDepNaissanceCodeInsee()
    {
        return $this->depNaissanceCodeInsee;
    }



    /**
     * Set depNaissanceLibelle
     *
     * @param string $depNaissanceLibelle
     *
     * @return Intervenant
     */
    public function setDepNaissanceLibelle($depNaissanceLibelle)
    {
        $this->depNaissanceLibelle = $depNaissanceLibelle;

        return $this;
    }



    /**
     * Get depNaissanceLibelle
     *
     * @return string
     */
    public function getDepNaissanceLibelle()
    {
        return $this->depNaissanceLibelle;
    }



    /**
     * Set email
     *
     * @param string $email
     *
     * @return Intervenant
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }



    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }



    /**
     * Set nomPatronymique
     *
     * @param string $nomPatronymique
     *
     * @return Intervenant
     */
    public function setNomPatronymique($nomPatronymique)
    {
        $this->nomPatronymique = $nomPatronymique;

        return $this;
    }



    /**
     * Get nomPatronymique
     *
     * @return string
     */
    public function getNomPatronymique()
    {
        return $this->nomPatronymique;
    }



    /**
     * Set nomUsuel
     *
     * @param string $nomUsuel
     *
     * @return Intervenant
     */
    public function setNomUsuel($nomUsuel)
    {
        $this->nomUsuel = $nomUsuel;

        return $this;
    }



    /**
     * Get nomUsuel
     *
     * @return string
     */
    public function getNomUsuel()
    {
        return $this->nomUsuel;
    }



    /**
     * Set numeroInsee
     *
     * @param string $numeroInsee
     *
     * @return Intervenant
     */
    public function setNumeroInsee($numeroInsee)
    {
        $this->numeroInsee = $numeroInsee;

        return $this;
    }



    /**
     * Get numeroInsee
     *
     * @return string
     */
    public function getNumeroInsee()
    {
        return $this->numeroInsee;
    }



    /**
     * Set numeroInseeCle
     *
     * @param string $numeroInseeCle
     *
     * @return Intervenant
     */
    public function setNumeroInseeCle($numeroInseeCle)
    {
        $this->numeroInseeCle = $numeroInseeCle;

        return $this;
    }



    /**
     * Get numeroInseeCle
     *
     * @return string
     */
    public function getNumeroInseeCle()
    {
        return $this->numeroInseeCle ? sprintf('%02d', $this->numeroInseeCle) : $this->numeroInseeCle;
    }



    /**
     * Set numeroInseeProvisoire
     *
     * @param boolean $numeroInseeProvisoire
     *
     * @return Intervenant
     */
    public function setNumeroInseeProvisoire($numeroInseeProvisoire)
    {
        $this->numeroInseeProvisoire = $numeroInseeProvisoire;

        return $this;
    }



    /**
     * Get numeroInseeProvisoire
     *
     * @return boolean
     */
    public function getNumeroInseeProvisoire()
    {
        return $this->numeroInseeProvisoire;
    }



    /**
     * Set paysNaissanceCodeInsee
     *
     * @param string $paysNaissanceCodeInsee
     *
     * @return Intervenant
     */
    public function setPaysNaissanceCodeInsee($paysNaissanceCodeInsee)
    {
        $this->paysNaissanceCodeInsee = $paysNaissanceCodeInsee;

        return $this;
    }



    /**
     * Get paysNaissanceCodeInsee
     *
     * @return string
     */
    public function getPaysNaissanceCodeInsee()
    {
        return $this->paysNaissanceCodeInsee;
    }



    /**
     * Set paysNaissanceLibelle
     *
     * @param string $paysNaissanceLibelle
     *
     * @return Intervenant
     */
    public function setPaysNaissanceLibelle($paysNaissanceLibelle)
    {
        $this->paysNaissanceLibelle = $paysNaissanceLibelle;

        return $this;
    }



    /**
     * Get paysNaissanceLibelle
     *
     * @return string
     */
    public function getPaysNaissanceLibelle()
    {
        return $this->paysNaissanceLibelle;
    }



    /**
     * Set paysNationaliteCodeInsee
     *
     * @param string $paysNationaliteCodeInsee
     *
     * @return Intervenant
     */
    public function setPaysNationaliteCodeInsee($paysNationaliteCodeInsee)
    {
        $this->paysNationaliteCodeInsee = $paysNationaliteCodeInsee;

        return $this;
    }



    /**
     * Get paysNationaliteCodeInsee
     *
     * @return string
     */
    public function getPaysNationaliteCodeInsee()
    {
        return $this->paysNationaliteCodeInsee;
    }



    /**
     * Set paysNationaliteLibelle
     *
     * @param string $paysNationaliteLibelle
     *
     * @return Intervenant
     */
    public function setPaysNationaliteLibelle($paysNationaliteLibelle)
    {
        $this->paysNationaliteLibelle = $paysNationaliteLibelle;

        return $this;
    }



    /**
     * Get paysNationaliteLibelle
     *
     * @return string
     */
    public function getPaysNationaliteLibelle()
    {
        return $this->paysNationaliteLibelle;
    }



    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Intervenant
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }



    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }



    /**
     * Set telMobile
     *
     * @param string $telMobile
     *
     * @return Intervenant
     */
    public function setTelMobile($telMobile)
    {
        $this->telMobile = $telMobile;

        return $this;
    }



    /**
     * Get telMobile
     *
     * @return string
     */
    public function getTelMobile()
    {
        return $this->telMobile;
    }



    /**
     * Set telPro
     *
     * @param string $telPro
     *
     * @return Intervenant
     */
    public function setTelPro($telPro)
    {
        $this->telPro = $telPro;

        return $this;
    }



    /**
     * Get telPro
     *
     * @return string
     */
    public function getTelPro()
    {
        return $this->telPro;
    }



    /**
     * Set villeNaissanceCodeInsee
     *
     * @param string $villeNaissanceCodeInsee
     *
     * @return Intervenant
     */
    public function setVilleNaissanceCodeInsee($villeNaissanceCodeInsee)
    {
        $this->villeNaissanceCodeInsee = $villeNaissanceCodeInsee;

        return $this;
    }



    /**
     * Get villeNaissanceCodeInsee
     *
     * @return string
     */
    public function getVilleNaissanceCodeInsee()
    {
        return $this->villeNaissanceCodeInsee;
    }



    /**
     * Set villeNaissanceLibelle
     *
     * @param string $villeNaissanceLibelle
     *
     * @return Intervenant
     */
    public function setVilleNaissanceLibelle($villeNaissanceLibelle)
    {
        $this->villeNaissanceLibelle = $villeNaissanceLibelle;

        return $this;
    }



    /**
     * Get villeNaissanceLibelle
     *
     * @return string
     */
    public function getVilleNaissanceLibelle()
    {
        return $this->villeNaissanceLibelle;
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
     * Set annee
     *
     * @param \Application\Entity\Db\Annee $annee
     *
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
     * Add affectation
     *
     * @param \Application\Entity\Db\AffectationRecherche $affectation
     *
     * @return Intervenant
     */
    public function addAffectation(\Application\Entity\Db\AffectationRecherche $affectation)
    {
        $this->affectation[] = $affectation;

        return $this;
    }



    /**
     * Remove affectation
     *
     * @param \Application\Entity\Db\AffectationRecherche $affectation
     */
    public function removeAffectation(\Application\Entity\Db\AffectationRecherche $affectation)
    {
        $this->affectation->removeElement($affectation);
    }



    /**
     * Get affectation
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAffectation()
    {
        return $this->affectation;
    }



    /**
     * Add adresse
     *
     * @param \Application\Entity\Db\AdresseIntervenant $adresse
     *
     * @return Intervenant
     */
    public function addAdresse(\Application\Entity\Db\AdresseIntervenant $adresse)
    {
        $this->adresse[] = $adresse;

        return $this;
    }



    /**
     * Remove adresse
     *
     * @param \Application\Entity\Db\AdresseIntervenant $adresse
     */
    public function removeAdresse(\Application\Entity\Db\AdresseIntervenant $adresse)
    {
        $this->adresse->removeElement($adresse);
    }



    /**
     * Get adresse
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdresse()
    {
        return $this->adresse;
    }



    /**
     * Set statut
     *
     * @param \Application\Entity\Db\StatutIntervenant $statut
     *
     * @return Intervenant
     */
    public function setStatut(\Application\Entity\Db\StatutIntervenant $statut = null)
    {
        $this->statut = $statut;

        return $this;
    }



    /**
     * Get statut
     *
     * @return \Application\Entity\Db\StatutIntervenant
     */
    public function getStatut()
    {
        return $this->statut;
    }



    /**
     * Set civilite
     *
     * @param \Application\Entity\Db\Civilite $civilite
     *
     * @return Intervenant
     */
    public function setCivilite(\Application\Entity\Db\Civilite $civilite = null)
    {
        $this->civilite = $civilite;

        return $this;
    }



    /**
     * Get civilite
     *
     * @return \Application\Entity\Db\Civilite
     */
    public function getCivilite()
    {
        return $this->civilite;
    }



    /**
     * Set BIC
     *
     * @param string $BIC
     *
     * @return Intervenant
     */
    public function setBIC($BIC = null)
    {
        $this->BIC = $BIC;

        return $this;
    }



    /**
     * Get BIC
     *
     * @return string
     */
    public function getBIC()
    {
        return $this->BIC;
    }



    /**
     * Set IBAN
     *
     * @param string $IBAN
     *
     * @return Intervenant
     */
    public function setIBAN($IBAN = null)
    {
        $this->IBAN = $IBAN;

        return $this;
    }



    /**
     * Get IBAN
     *
     * @return string
     */
    public function getIBAN()
    {
        return $this->IBAN;
    }



    /**
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     *
     * @return Intervenant
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
     * Add service
     *
     * @param \Application\Entity\Db\Service $service
     *
     * @return Intervenant
     */
    public function addService(\Application\Entity\Db\Service $service)
    {
        $this->service[] = $service;

        return $this;
    }



    /**
     * Remove service
     *
     * @param \Application\Entity\Db\Service $service
     */
    public function removeService(\Application\Entity\Db\Service $service)
    {
        $this->service->removeElement($service);
    }



    /**
     * Get service
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getService()
    {
        return $this->service;
    }



    /**
     * Add histo service
     *
     * @param HistoIntervenantService $histoService
     *
     * @return Intervenant
     */
    public function addHistoService(HistoIntervenantService $histoService)
    {
        $this->histoService[] = $histoService;

        return $this;
    }



    /**
     * Remove histo service
     *
     * @param HistoIntervenantService $histoService
     */
    public function removeHistoService(HistoIntervenantService $histoService)
    {
        $this->histoService->removeElement($histoService);
    }



    /**
     * Get histo service
     *
     * @param TypeVolumeHoraire|null $typeVolumeHoraire
     * @param boolean                $referentiel
     *
     * @return HistoIntervenantService
     */
    public function getHistoService($typeVolumeHoraire, $referentiel = false)
    {
        $result = $this->histoService->filter(function (HistoIntervenantService $histoService) use ($typeVolumeHoraire, $referentiel) {
            return
                ($histoService->getTypeVolumeHoraire() == $typeVolumeHoraire || $histoService->getTypeVolumeHoraire() === null)
                && $histoService->getReferentiel() == $referentiel;
        });
        if ($result->count() == 1) { // un seul résultat
            return $result->first();
        } elseif ($result->count() == 2) { // deux possibles : pour le service et pour le VH
            $r = array_values($result->toArray());
            if ($r[0]->getHistoModification() > $r[1]->getHistoModification()) {
                return $r[0];
            } else {
                return $r[1];
            }
        } else {
            return null;
        }
    }



    /**
     * Add service référentiel
     *
     * @param \Application\Entity\Db\ServiceReferentiel $serviceReferentiel
     *
     * @return Intervenant
     */
    public function addServiceReferentiel(\Application\Entity\Db\ServiceReferentiel $serviceReferentiel)
    {
        $this->serviceReferentiel[] = $serviceReferentiel;

        return $this;
    }



    /**
     * Remove serviceReferentiel
     *
     * @param \Application\Entity\Db\ServiceReferentiel $serviceReferentiel
     * @param bool                                      $softDelete
     */
    public function removeServiceReferentiel(\Application\Entity\Db\ServiceReferentiel $serviceReferentiel, $softDelete = true)
    {
        if ($softDelete && $serviceReferentiel instanceof HistoriqueAwareInterface) {
            $serviceReferentiel->setHistoDestruction(new \DateTime());
        } else {
            $this->serviceReferentiel->removeElement($serviceReferentiel);
        }
    }



    /**
     * Get service référentiel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiceReferentiel()
    {
        return $this->serviceReferentiel;
    }



    /**
     * Get serviceReferentielToStrings
     *
     * @return string[]
     */
    public function getServiceReferentielToStrings()
    {
        $services = [];
        foreach ($this->getServiceReferentiel() as $sr) {
            /* @var $sr \Application\Entity\Db\ServiceReferentiel */
            $services[] = "" . $sr;
        }

        return $services;
    }



    /**
     * Remove all serviceReferentiel
     *
     * @param bool $softDelete
     *
     * @return self
     */
    public function removeAllServiceReferentiel($softDelete = true)
    {
        foreach ($this->getServiceReferentiel() as $serviceReferentiel) {
            $this->removeServiceReferentiel($serviceReferentiel, $softDelete);
        }

        return $this;
    }



    /**
     * Get validation
     *
     * @param \Application\Entity\Db\TypeValidation $type
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getValidation(TypeValidation $type = null)
    {
        if (null === $type) {
            return $this->validation;
        }
        if (null === $this->validation) {
            return null;
        }

        $filter      = function (Validation $validation) use ($type) {
            return $type === $validation->getTypeValidation();
        };
        $validations = $this->validation->filter($filter);

        return $validations;
    }



    /**
     * Add agrement
     *
     * @param \Application\Entity\Db\Agrement $agrement
     *
     * @return Intervenant
     */
    public function addAgrement(\Application\Entity\Db\Agrement $agrement)
    {
        $this->agrement[] = $agrement;

        return $this;
    }



    /**
     * Remove agrement
     *
     * @param \Application\Entity\Db\Agrement $agrement
     */
    public function removeAgrement(\Application\Entity\Db\Agrement $agrement)
    {
        $this->agrement->removeElement($agrement);
    }



    /**
     * Get agrement
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAgrement(TypeAgrement $typeAgrement = null)
    {
        if (null === $this->agrement) {
            return null;
        }
        if (null === $typeAgrement) {
            return $this->agrement;
        }

        $filter    = function (Agrement $agrement) use ($typeAgrement) {
            if ($typeAgrement && $typeAgrement !== $agrement->getType()) {
                return false;
            }

            return true;
        };
        $agrements = $this->agrement->filter($filter);

        return $agrements;
    }



    /**
     * Indique si cet intervenant est permanent.
     *
     * @return bool
     */
    public function estPermanent()
    {
        return $this->getStatut()->estPermanent();
    }



    /**
     * Get estUneFemme
     *
     * @return bool
     */
    public function estUneFemme()
    {
        $civilite = $this->getDossier() ? $this->getDossier()->getCivilite() : $this->getCivilite();

        return 'F' === $civilite->getSexe();
    }



    /**
     * Get civilite
     *
     * @return string
     */
    public function getCiviliteToString()
    {
        return $this->getCivilite()->getLibelleCourt();
    }



    /**
     * Get affectations
     *
     * @return string
     */
    public function getAffectationsToString()
    {
        return "" . $this->getStructure() ?: "(Inconnue)";
    }



    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return strtoupper($this->getNomUsuel()) . ' ' . ucfirst($this->getPrenom());
    }



    /**
     * Get nomUsuel
     *
     * @return string
     */
    public function getNomComplet($avecCivilite = false, $avecNomPatro = false)
    {
        $f = new \Application\Filter\NomCompletFormatter(true, $avecCivilite, $avecNomPatro);

        return $f->filter($this);
    }



    /**
     * Get dateNaissance
     *
     * @return \DateTime
     */
    public function getDateNaissanceToString()
    {
        return $this->dateNaissance->format(Constants::DATE_FORMAT);
    }



    /**
     * Retourne l'adresse principale.
     *
     * NB: si aucune adresse principale n'est trouvée, la 1ère adresse non principale trouvée est retournée.
     *
     * @return AdresseIntervenant|null
     */
    public function getAdressePrincipale()
    {
        $adresses = $this->getAdresse();

        if (!count($adresses)) {
            return null;
        }
        $adresse = $adresses->first();

        return $adresse ?: null;
    }



    /**
     * Set premierRecrutement
     *
     * @param null|boolean $premierRecrutement
     *
     * @return self
     */
    public function setPremierRecrutement($premierRecrutement)
    {
        $this->premierRecrutement = $premierRecrutement;

        return $this;
    }



    /**
     * Get premierRecrutement
     *
     * @return null|boolean
     */
    public function getPremierRecrutement()
    {
        return $this->premierRecrutement;
    }



    /**
     * Get vIndicDiffDossier
     *
     * @return \Application\Entity\Db\VIndicDiffDossier
     */
    public function getVIndicDiffDossier()
    {
        if (!count($this->vIndicDiffDossier)) {
            return null;
        }

        return $this->vIndicDiffDossier->first();
    }



    /**
     * Get indicDiffDossier
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIndicModifDossier()
    {
        return $this->indicModifDossier;
    }



    /**
     * Get vIndicAttenteDemandeMep
     *
     * @return VIndicAttenteDemandeMep[]
     */
    public function getVIndicAttenteDemandeMep()
    {
        return $this->vIndicAttenteDemandeMep;
    }



    /**
     * Get vIndicAttenteMep
     *
     * @return VIndicAttenteMep[]
     */
    public function getVIndicAttenteMep()
    {
        return $this->vIndicAttenteMep;
    }



    /**
     * Get vIndicDepassHcHorsRemuFc
     *
     * @return VIndicDepassHcHorsRemuFc[]
     */
    public function getVIndicDepassHcHorsRemuFc()
    {
        return $this->vIndicDepassHcHorsRemuFc;
    }



    /**
     * Get vIndicDepassRef
     *
     * @return VIndicDepassRef[]
     */
    public function getVIndicDepassRef()
    {
        return $this->vIndicDepassRef;
    }



    /**
     * Get formuleReferentiel
     *
     * @param Structure|null $structure
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormuleReferentiel(Structure $structure = null)
    {
        $filter = function (FormuleReferentiel $formuleReferentiel) use ($structure) {
            if ($structure && $structure !== $formuleReferentiel->getStructure()) {
                return false;
            }

            return true;
        };

        return $this->formuleReferentiel->filter($filter);
    }



    /**
     * Get formuleResultat
     *
     * @param TypeVolumeHoraire $typeVolumeHoraire
     * @param EtatVolumeHoraire $etatVolumehoraire
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormuleResultat(TypeVolumeHoraire $typeVolumeHoraire = null, EtatVolumeHoraire $etatVolumehoraire = null)
    {
        $filter = function (FormuleResultat $formuleResultat) use ($typeVolumeHoraire, $etatVolumehoraire) {
            if ($typeVolumeHoraire && $typeVolumeHoraire !== $formuleResultat->getTypeVolumeHoraire()) {
                return false;
            }
            if ($etatVolumehoraire && $etatVolumehoraire !== $formuleResultat->getEtatVolumeHoraire()) {
                return false;
            }

            return true;
        };

        return $this->formuleResultat->filter($filter);
    }



    /**
     * Get unique formuleResultat
     *
     * @param TypeVolumeHoraire $typeVolumeHoraire
     * @param EtatVolumeHoraire $etatVolumehoraire
     *
     * @return FormuleResultat
     */
    public function getUniqueFormuleResultat(TypeVolumeHoraire $typeVolumeHoraire, EtatVolumeHoraire $etatVolumehoraire)
    {
        $formuleResultat = $this->getFormuleResultat($typeVolumeHoraire, $etatVolumehoraire)->first();
        if (false === $formuleResultat) {
            $formuleResultat = new FormuleResultat;
            $formuleResultat->init($this, $typeVolumeHoraire, $etatVolumehoraire);
        }

        return $formuleResultat;
    }



    /**
     * Get FormuleIntervenant
     *
     * @return FormuleIntervenant
     */
    public function getFormuleIntervenant()
    {
        if (!count($this->formuleIntervenant)) {
            return null;
        }

        return $this->formuleIntervenant->first();
    }



    /**
     * Get miseEnPaiementIntervenantStructure
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMiseEnPaiementIntervenantStructure()
    {
        return $this->miseEnPaiementIntervenantStructure;
    }



    /**
     * Add modificationServiceDu
     *
     * @param \Application\Entity\Db\ModificationServiceDu $modificationServiceDu
     *
     * @return Intervenant
     */
    public function addModificationServiceDu(\Application\Entity\Db\ModificationServiceDu $modificationServiceDu)
    {
        $this->modificationServiceDu[] = $modificationServiceDu;

        return $this;
    }



    /**
     * Remove modificationServiceDu
     *
     * @param \Application\Entity\Db\ModificationServiceDu $modificationServiceDu
     * @param bool                                         $softDelete
     */
    public function removeModificationServiceDu(\Application\Entity\Db\ModificationServiceDu $modificationServiceDu, $softDelete = true)
    {
        if ($softDelete && $modificationServiceDu instanceof HistoriqueAwareInterface) {
            $modificationServiceDu->setHistoDestruction(new \DateTime());
        } else {
            $this->modificationServiceDu->removeElement($modificationServiceDu);
        }
    }



    /**
     * Get modificationServiceDu
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getModificationServiceDu()
    {
        return $this->modificationServiceDu;
    }



    /**
     * Get modificationServiceDuToStrings
     *
     * @return string[]
     */
    public function getModificationServiceDuToStrings()
    {
        $services = [];
        foreach ($this->getModificationServiceDu() as $sr) {
            /* @var $sr \Application\Entity\Db\ModificationServiceDu */
            $services[] = "" . $sr;
        }

        return $services;
    }



    /**
     * Remove all modificationServiceDu
     *
     * @param bool $softDelete
     *
     * @return self
     */
    public function removeAllModificationServiceDu($softDelete = true)
    {
        foreach ($this->getModificationServiceDu() as $modificationServiceDu) {
            $this->removeModificationServiceDu($modificationServiceDu, $softDelete);
        }

        return $this;
    }



    /**
     * Retourne l'adresse mail personnelle éventuelle.
     * Si elle est null et que le paramètre le demande, retourne l'adresse par défaut.
     *
     * @param bool $fallbackOnDefault
     *
     * @return string
     */
    public function getEmailPerso($fallbackOnDefault = false)
    {
        $mail = null;

        if ($this->getDossier()) {
            $mail = $this->getDossier()->getEmailPerso();
        }

        if (!$mail && $fallbackOnDefault) {
            $mail = $this->getEmail();
        }

        return $mail;
    }



    /**
     * Add contrat
     *
     * @param \Application\Entity\Db\Contrat $contrat
     *
     * @return Intervenant
     */
    public function addContrat(\Application\Entity\Db\Contrat $contrat)
    {
        $this->contrat[] = $contrat;

        return $this;
    }



    /**
     * Remove contrat
     *
     * @param \Application\Entity\Db\Contrat $contrat
     */
    public function removeContrat(\Application\Entity\Db\Contrat $contrat)
    {
        $this->contrat->removeElement($contrat);
    }



    /**
     * Get contrat
     *
     * @param \Application\Entity\Db\TypeContrat $typeContrat
     * @param \Application\Entity\Db\Structure   $structure
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContrat(TypeContrat $typeContrat = null, Structure $structure = null)
    {
        if (null === $this->contrat) {
            return null;
        }

        $filter   = function (Contrat $contrat) use ($typeContrat, $structure) {
            if ($typeContrat && $typeContrat !== $contrat->getTypeContrat()) {
                return false;
            }
            if ($structure && $structure !== $contrat->getStructure()) {
                return false;
            }

            return true;
        };
        $contrats = $this->contrat->filter($filter);

        return $contrats;
    }



    /**
     * Get contrat initial
     *
     * @return Contrat|null
     */
    public function getContratInitial()
    {
        if (!count($this->getContrat())) {
            return null;
        }

        $contrats = $this->getContrat()->filter(function ($contrat) {
            return TypeContrat::CODE_CONTRAT === $contrat->getTypeContrat()->getCode();
        });

        if (count($contrats) > 1){
            $contrats = $contrats->filter(function ($contrat) {
                return $contrat->getValidation();
            });
        }

        return count($contrats) ? $contrats->first() : null;
    }



    /**
     * Get avenants
     *
     * @return Contrat[]|null
     */
    public function getAvenants()
    {
        $type = TypeContrat::CODE_AVENANT;

        $filter   = function (Contrat $contrat) use ($type) {
            return $type === $contrat->getTypeContrat()->getCode();
        };
        $contrats = $this->getContrat()->filter($filter);

        return $contrats;
    }



    /**
     * @return float
     */
    public function getMontantIndemniteFc()
    {
        return $this->montantIndemniteFc;
    }



    /**
     * @param float $montantIndemniteFc
     *
     * @return Intervenant
     */
    public function setMontantIndemniteFc($montantIndemniteFc)
    {
        $this->montantIndemniteFc = $montantIndemniteFc;

        return $this;
    }



    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     * @see ResourceInterface
     */
    public function getResourceId()
    {
        return 'Intervenant';
    }



    /**
     * retourne le paramètre de route
     *
     * @return string
     */
    public function getRouteParam()
    {
        return $this->getSourceCode();
    }
}
