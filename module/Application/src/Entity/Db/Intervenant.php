<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\AnneeAwareTrait;
use Application\Entity\Db\Traits\CiviliteAwareTrait;
use Application\Entity\Db\Traits\DisciplineAwareTrait;
use Application\Entity\Db\Traits\EmployeurAwareTrait;
use Application\Entity\Db\Traits\GradeAwareTrait;
use Application\Entity\Db\Traits\StructureAwareTrait;
use Application\Entity\Traits\AdresseTrait;
use Application\Interfaces\AdresseInterface;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectManagerAware;
use Indicateur\Entity\Db\IndicModifDossier;
use Intervenant\Entity\Db\Statut;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;
use Laminas\Hydrator\ClassMethodsHydrator;
use Laminas\Permissions\Acl\Resource\ResourceInterface;

/**
 * Intervenant
 *
 */
class Intervenant implements HistoriqueAwareInterface, ResourceInterface, ImportAwareInterface, ObjectManagerAware, AdresseInterface
{
    use AnneeAwareTrait;
    use StructureAwareTrait;
    use GradeAwareTrait;
    use DisciplineAwareTrait;
    use CiviliteAwareTrait;
    use AdresseTrait;
    use EmployeurAwareTrait;
    use ImportAwareTrait;
    use HistoriqueAwareTrait;
    use EntityManagerAwareTrait;

    /**
     * @var int|null
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $code;

    /**
     * @var string|null
     */
    protected $codeRh;

    /**
     * @var string|null
     */
    protected $utilisateurCode;

    /**
     * @var \Intervenant\Entity\Db\Statut
     */
    protected $statut;

    /**
     * @var string|null
     */
    protected $nomUsuel;

    /**
     * @var string|null
     */
    protected $prenom;

    /**
     * @var \DateTime|null
     */
    protected $dateNaissance;

    /**
     * @var string|null
     */
    protected $nomPatronymique;

    /**
     * @var string|null
     */
    protected $communeNaissance;

    /**
     * @var Pays|null
     */
    private $paysNaissance;

    /**
     * @var Departement|null
     */
    private $departementNaissance;

    /**
     * @var Pays|null
     */
    private $paysNationalite;

    /**
     * @var string|null
     */
    protected $telPro;

    /**
     * @var string|null
     */
    protected $telPerso;

    /**
     * @var string|null
     */
    protected $emailPro;

    /**
     * @var string|null
     */
    protected $emailPerso;

    /**
     * @var string|null
     */
    protected $numeroInsee;

    /**
     * @var bool|null
     */
    protected $numeroInseeProvisoire = false;

    /**
     * @var string|null
     */
    protected $IBAN;

    /**
     * @var string|null
     */
    protected $BIC;

    /**
     * @var bool
     */
    protected $ribHorsSepa = false;

    /**
     * @var string|null
     */
    protected $autre1;

    /**
     * @var string|null
     */
    protected $autre2;

    /**
     * @var string|null
     */
    protected $autre3;

    /**
     * @var string|null
     */
    protected $autre4;

    /**
     * @var string|null
     */
    protected $autre5;

    /**
     * @var float|null
     */
    protected $montantIndemniteFc;

    /**
     * @var bool|null
     */
    protected $premierRecrutement;

    /**
     * @var bool
     */
    protected $syncStatut = true;

    /**
     * @var bool
     */
    protected $syncStructure = true;

    /**
     * @var bool
     */
    protected $syncUtilisateurCode = true;

    /**
     * @var \DateTime
     */
    protected $validiteDebut;

    /**
     * @var \DateTime
     */
    protected $validiteFin;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $affectation;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $agrement;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $contrat;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $formuleResultat;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $histoService;

    /**
     * @var MiseEnPaiementIntervenantStructure
     */
    protected $miseEnPaiementIntervenantStructure;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $modificationServiceDu;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $pieceJointe;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $service;

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
    protected $indicModifDossier;

    /**
     * Cache
     *
     * @var bool
     */
    protected $hasMiseEnPaiement = null;

    /**
     * @var \DateTime
     */
    protected      $exportDate;

    protected bool $irrecevable = false;



    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->affectation                        = new \Doctrine\Common\Collections\ArrayCollection();
        $this->agrement                           = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contrat                            = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formuleResultat                    = new \Doctrine\Common\Collections\ArrayCollection();
        $this->histoService                       = new \Doctrine\Common\Collections\ArrayCollection();
        $this->miseEnPaiementIntervenantStructure = new \Doctrine\Common\Collections\ArrayCollection();
        $this->modificationServiceDu              = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pieceJointe                        = new \Doctrine\Common\Collections\ArrayCollection();
        $this->service                            = new \Doctrine\Common\Collections\ArrayCollection();
        $this->serviceReferentiel                 = new \Doctrine\Common\Collections\ArrayCollection();
        $this->validation                         = new \Doctrine\Common\Collections\ArrayCollection();
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



    function __sleep()
    {
        return [];
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



    public function getAdresseIdentite(): ?string
    {
        $identite = [];
        if ($this->getCivilite()) $identite[] = (string)$this->getCivilite();
        if ($this->getNomUsuel()) $identite[] = $this->getNomUsuel();
        if ($this->getPrenom()) $identite[] = $this->getPrenom();

        if (empty($identite)) {
            return null;
        } else {
            return implode(' ', $identite);
        }
    }



    /**
     * Injects responsible ObjectManager and the ClassMetadata into this persistent object.
     *
     * @param ObjectManager $objectManager
     * @param ClassMetadata $classMetadata
     *
     * @return void
     */
    public function injectObjectManager(ObjectManager $objectManager, ClassMetadata $classMetadata)
    {
        $this->setEntityManager($objectManager);
    }



    /**
     * @param bool $demande
     */
    public function hasMiseEnPaiement($demande = true)
    {
        if ($this->hasMiseEnPaiement === null) {
            $id     = (int)$this->getId();
            $heures = $demande ? 'heures_demandees' : 'heures_payees';

            $sql = "SELECT COUNT(*) res FROM tbl_paiement p "
                . "WHERE p.intervenant_id = $id AND p.$heures > 0 AND rownum = 1";

            $res = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql);

            $this->hasMiseEnPaiement = $res[0]['RES'] == 1;
        }

        return $this->hasMiseEnPaiement;
    }



    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }



    /**
     * @param string|null $code
     *
     * @return Intervenant
     */
    public function setCode(?string $code): Intervenant
    {
        $this->code = $code;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getCodeRh(): ?string
    {
        return $this->codeRh;
    }



    /**
     * @param string|null $codeRh
     *
     * @return Intervenant
     */
    public function setCodeRh(?string $codeRh): Intervenant
    {
        $this->codeRh = $codeRh;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getUtilisateurCode(): ?string
    {
        return $this->utilisateurCode;
    }



    /**
     * @param string|null $utilisateurCode
     *
     * @return Intervenant
     */
    public function setUtilisateurCode(?string $utilisateurCode): Intervenant
    {
        $this->utilisateurCode = $utilisateurCode;

        return $this;
    }



    /**
     * Get statut
     *
     * @return Statut
     */
    public function getStatut()
    {
        return $this->statut;
    }



    /**
     * Set statut
     *
     * @param Statut $statut
     *
     * @return Intervenant
     */
    public function setStatut(Statut $statut = null)
    {
        $this->statut = $statut;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getNomUsuel(): ?string
    {
        return $this->nomUsuel;
    }



    /**
     * @param string|null $nomUsuel
     *
     * @return Intervenant
     */
    public function setNomUsuel(?string $nomUsuel): Intervenant
    {
        $this->nomUsuel = $nomUsuel;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }



    /**
     * @param string|null $prenom
     *
     * @return Intervenant
     */
    public function setPrenom(?string $prenom): Intervenant
    {
        $this->prenom = $prenom;

        return $this;
    }



    /**
     * @return \DateTime|null
     */
    public function getDateNaissance(): ?\DateTime
    {
        return $this->dateNaissance;
    }



    /**
     * @param \DateTime|null $dateNaissance
     *
     * @return Intervenant
     */
    public function setDateNaissance(?\DateTime $dateNaissance): Intervenant
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getNomPatronymique(): ?string
    {
        return $this->nomPatronymique;
    }



    /**
     * @param string|null $nomPatronymique
     *
     * @return Intervenant
     */
    public function setNomPatronymique(?string $nomPatronymique): Intervenant
    {
        $this->nomPatronymique = $nomPatronymique;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getCommuneNaissance(): ?string
    {
        return $this->communeNaissance;
    }



    /**
     * @param string|null $communeNaissance
     *
     * @return Intervenant
     */
    public function setCommuneNaissance(?string $communeNaissance): Intervenant
    {
        $this->communeNaissance = $communeNaissance;

        return $this;
    }



    /**
     * @return Pays|null
     */
    public function getPaysNaissance(): ?Pays
    {
        return $this->paysNaissance;
    }



    /**
     * @param Pays|null $paysNaissance
     *
     * @return Intervenant
     */
    public function setPaysNaissance(?Pays $paysNaissance): Intervenant
    {
        $this->paysNaissance = $paysNaissance;

        return $this;
    }



    /**
     * @return Departement|null
     */
    public function getDepartementNaissance(): ?Departement
    {
        return $this->departementNaissance;
    }



    /**
     * @param Departement|null $departementNaissance
     *
     * @return Intervenant
     */
    public function setDepartementNaissance(?Departement $departementNaissance): Intervenant
    {
        $this->departementNaissance = $departementNaissance;

        return $this;
    }



    /**
     * @return Pays|null
     */
    public function getPaysNationalite(): ?Pays
    {
        return $this->paysNationalite;
    }



    /**
     * @param Pays|null $paysNationalite
     *
     * @return Intervenant
     */
    public function setPaysNationalite(?Pays $paysNationalite): Intervenant
    {
        $this->paysNationalite = $paysNationalite;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getTelPro(): ?string
    {
        return $this->telPro;
    }



    /**
     * @param string|null $telPro
     *
     * @return Intervenant
     */
    public function setTelPro(?string $telPro): Intervenant
    {
        $this->telPro = $telPro;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getTelPerso(): ?string
    {
        return $this->telPerso;
    }



    /**
     * @param string|null $telPerso
     *
     * @return Intervenant
     */
    public function setTelPerso(?string $telPerso): Intervenant
    {
        $this->telPerso = $telPerso;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getEmailPro(): ?string
    {
        return $this->emailPro;
    }



    /**
     * @param string|null $emailPro
     *
     * @return Intervenant
     */
    public function setEmailPro(?string $emailPro): Intervenant
    {
        $this->emailPro = $emailPro;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getEmailPerso(): ?string
    {
        return $this->emailPerso;
    }



    /**
     * @param string|null $emailPerso
     *
     * @return Intervenant
     */
    public function setEmailPerso(?string $emailPerso): Intervenant
    {
        $this->emailPerso = $emailPerso;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getNumeroInsee(): ?string
    {
        return $this->numeroInsee;
    }



    /**
     * @param string|null $numeroInsee
     *
     * @return Intervenant
     */
    public function setNumeroInsee(?string $numeroInsee): Intervenant
    {
        $this->numeroInsee = $numeroInsee;

        return $this;
    }



    /**
     * @return bool|null
     */
    public function getNumeroInseeProvisoire(): ?bool
    {
        return $this->numeroInseeProvisoire;
    }



    /**
     * @param bool|null $numeroInseeProvisoire
     *
     * @return Intervenant
     */
    public function setNumeroInseeProvisoire(?bool $numeroInseeProvisoire): Intervenant
    {
        $this->numeroInseeProvisoire = $numeroInseeProvisoire;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getIBAN(): ?string
    {
        return $this->IBAN;
    }



    /**
     * @param string|null $IBAN
     *
     * @return Intervenant
     */
    public function setIBAN(?string $IBAN): Intervenant
    {
        $this->IBAN = $IBAN;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getBIC(): ?string
    {
        return $this->BIC;
    }



    /**
     * @param string|null $BIC
     *
     * @return Intervenant
     */
    public function setBIC(?string $BIC): Intervenant
    {
        $this->BIC = $BIC;

        return $this;
    }



    /**
     * Renvoi le RIB : concaténation du BIC et IBAN si les deux sont renseignés
     *
     * @return string|null
     */
    public function getRib(): ?string
    {
        $rib = '';

        if ($this->BIC && $this->IBAN) {
            $rib = $this->BIC . ' ' . $this->IBAN;
        }

        return $rib;
    }



    /**
     * @return bool
     */
    public function isRibHorsSepa(): bool
    {
        return $this->ribHorsSepa;
    }



    /**
     * @return bool
     */
    public function getRibHorsSepa(): bool
    {
        return $this->ribHorsSepa;
    }



    /**
     * @param bool $ribHorsSepa
     *
     * @return Intervenant
     */
    public function setRibHorsSepa(bool $ribHorsSepa): Intervenant
    {
        $this->ribHorsSepa = $ribHorsSepa;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getAutre1(): ?string
    {
        return $this->autre1;
    }



    /**
     * @param string|null $autre1
     *
     * @return Intervenant
     */
    public function setAutre1(?string $autre1): Intervenant
    {
        $this->autre1 = $autre1;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getAutre2(): ?string
    {
        return $this->autre2;
    }



    /**
     * @param string|null $autre2
     *
     * @return Intervenant
     */
    public function setAutre2(?string $autre2): Intervenant
    {
        $this->autre2 = $autre2;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getAutre3(): ?string
    {
        return $this->autre3;
    }



    /**
     * @param string|null $autre3
     *
     * @return Intervenant
     */
    public function setAutre3(?string $autre3): Intervenant
    {
        $this->autre3 = $autre3;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getAutre4(): ?string
    {
        return $this->autre4;
    }



    /**
     * @param string|null $autre4
     *
     * @return Intervenant
     */
    public function setAutre4(?string $autre4): Intervenant
    {
        $this->autre4 = $autre4;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getAutre5(): ?string
    {
        return $this->autre5;
    }



    /**
     * @param string|null $autre5
     *
     * @return Intervenant
     */
    public function setAutre5(?string $autre5): Intervenant
    {
        $this->autre5 = $autre5;

        return $this;
    }



    /**
     * @return float|null
     */
    public function getMontantIndemniteFc(): ?float
    {
        return $this->montantIndemniteFc;
    }



    /**
     * @param float|null $montantIndemniteFc
     *
     * @return Intervenant
     */
    public function setMontantIndemniteFc(?float $montantIndemniteFc): Intervenant
    {
        $this->montantIndemniteFc = $montantIndemniteFc;

        return $this;
    }



    /**
     * @return bool|null
     */
    public function getPremierRecrutement(): ?bool
    {
        return $this->premierRecrutement;
    }



    /**
     * @param bool|null $premierRecrutement
     *
     * @return Intervenant
     */
    public function setPremierRecrutement(?bool $premierRecrutement): Intervenant
    {
        $this->premierRecrutement = $premierRecrutement;

        return $this;
    }



    /**
     * @return bool
     */
    public function isSyncStatut(): bool
    {
        return $this->syncStatut;
    }



    /**
     * @param bool $syncStatut
     *
     * @return Intervenant
     */
    public function setSyncStatut(bool $syncStatut): Intervenant
    {
        $this->syncStatut = $syncStatut;

        return $this;
    }



    /**
     * @return bool
     */
    public function isSyncStructure(): bool
    {
        return $this->syncStructure;
    }



    /**
     * @param bool $syncStructure
     *
     * @return Intervenant
     */
    public function setSyncStructure(bool $syncStructure): Intervenant
    {
        $this->syncStructure = $syncStructure;

        return $this;
    }



    /**
     * @return bool
     */
    public function isSyncUtilisateurCode(): bool
    {
        return $this->syncUtilisateurCode;
    }



    /**
     * @param bool $syncUtilisateurCode
     *
     * @return Intervenant
     */
    public function setSyncUtilisateurCode(bool $syncUtilisateurCode): Intervenant
    {
        $this->syncUtilisateurCode = $syncUtilisateurCode;

        return $this;
    }



    /**
     * @return \DateTime
     */
    public function getValiditeDebut(): ?\DateTime
    {
        return $this->validiteDebut;
    }



    /**
     * @param \DateTime $validiteDebut
     *
     * @return Intervenant
     */
    public function setValiditeDebut(?\DateTime $validiteDebut): Intervenant
    {
        $this->validiteDebut = $validiteDebut;

        return $this;
    }



    /**
     * @return \DateTime
     */
    public function getValiditeFin(): ?\DateTime
    {
        return $this->validiteFin;
    }



    /**
     * @param \DateTime $validiteFin
     *
     * @return Intervenant
     */
    public function setValiditeFin(?\DateTime $validiteFin): Intervenant
    {
        $this->validiteFin = $validiteFin;

        return $this;
    }



    /**
     * @return \DateTime
     */
    public function getExportDate(): ?\DateTime
    {
        return $this->exportDate;
    }



    /**
     * @param \DateTime $exportDate
     *
     * @return Intervenant
     */
    public function setExportDate(?\DateTime $exportDate): Intervenant
    {
        $this->exportDate = $exportDate;

        return $this;
    }



    public function getValidite(): string
    {
        if (!$this->validiteDebut && !$this->validiteFin) {
            return '';
        }
        if (!$this->validiteDebut) {
            return 'jusqu\'au ' . $this->validiteFin->format('d/m/Y');
        }
        if (!$this->validiteFin) {
            return 'à partir du ' . $this->validiteDebut->format('d/m/Y');
        }

        return 'du ' . $this->validiteDebut->format('d/m/Y') . ' au ' . $this->validiteFin->format('d/m/Y');
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
            if ($structure && $structure !== $contrat->getStructure() && $contrat->getStructure() !== null) {
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

        if (count($contrats) > 1) {
            $contrats = $contrats->filter(function ($contrat) {
                return $contrat->getValidation();
            });
        }

        return count($contrats) ? $contrats->first() : null;
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
                ($histoService->getTypeVolumeHoraire() == $typeVolumeHoraire)
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
     * Get pieceJointe
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPieceJointe()
    {
        return $this->pieceJointe;
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
     * Get service référentiel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiceReferentiel()
    {
        return $this->serviceReferentiel;
    }



    /**
     * Get IndicModifDossier
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIndicModifDossier()
    {
        if (null === $this->indicModifDossier) {
            return null;
        }

        $filter = function (IndicModifDossier $indicModifDossier) {
            return ($indicModifDossier->estHistorise()) ? false : true;
        };

        // return $this->indicModifDossier;

        return $this->indicModifDossier->filter($filter);
    }



    /**
     * @return bool
     */
    public function isIrrecevable(): bool
    {
        return $this->irrecevable;
    }



    /**
     * @param bool $irrecevable
     *
     * @return Intervenant
     */
    public function setIrrecevable(bool $irrecevable): Intervenant
    {
        $this->irrecevable = $irrecevable;

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



    public function dupliquer()
    {
        $intervenant = new Intervenant();

        $hydrator = new ClassMethodsHydrator();
        $data     = $hydrator->extract($this);
        $hydrator->hydrate($data, $intervenant);
        $intervenant->setValiditeDebut(new \DateTime());
        $intervenant->setValiditeFin(null);
        $intervenant->setHistoDestructeur(null);
        $intervenant->setHistoDestruction(null);

        return $intervenant;
    }
}
