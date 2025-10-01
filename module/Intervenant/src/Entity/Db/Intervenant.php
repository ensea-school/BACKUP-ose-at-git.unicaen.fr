<?php

namespace Intervenant\Entity\Db;

use Agrement\Entity\Db\Agrement;
use Agrement\Entity\Db\TypeAgrement;
use Application\Entity\Db\Traits\AnneeAwareTrait;
use Contrat\Entity\Db\Contrat;
use Contrat\Entity\Db\TypeContrat;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Dossier\Entity\Db\Traits\EmployeurAwareTrait;
use Enseignement\Entity\Db\Service;
use Formule\Entity\Db\FormuleResultatIntervenant;
use Framework\User\UserProfile;
use Indicateur\Entity\Db\IndicModifDossier;
use Laminas\Hydrator\ClassMethodsHydrator;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\AdresseInterface;
use Lieu\Entity\AdresseTrait;
use Lieu\Entity\Db\Departement;
use Lieu\Entity\Db\Pays;
use Lieu\Entity\Db\Structure;
use Lieu\Entity\Db\StructureAwareTrait;
use Mission\Entity\Db\Mission;
use OffreFormation\Entity\Db\Traits\DisciplineAwareTrait;
use Paiement\Entity\Db\MiseEnPaiementIntervenantStructure;
use Plafond\Interfaces\PlafondDataInterface;
use Referentiel\Entity\Db\ServiceReferentiel;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\HistoIntervenantService;
use Service\Entity\Db\ModificationServiceDu;
use Service\Entity\Db\TypeVolumeHoraire;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;
use Workflow\Entity\Db\TypeValidation;
use Workflow\Entity\Db\Validation;

/**
 * Intervenant
 *
 */
class Intervenant implements HistoriqueAwareInterface, ResourceInterface, ImportAwareInterface, EntityManagerAwareInterface, AdresseInterface, PlafondDataInterface
{
    use AnneeAwareTrait;
    use StructureAwareTrait;
    use GradeAwareTrait;
    use DisciplineAwareTrait;
    use CiviliteAwareTrait;
    use SituationMatrimonialeAwareTrait;
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
     * @var Statut
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
    protected $syncPec = true;

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
     * @var Collection
     */
    protected $affectation;

    /**
     * @var Collection
     */
    protected $candidatures;

    /**
     * @var Collection
     */
    protected $agrement;

    /**
     * @var Collection
     */
    protected $contrat;

    /**
     * @var Collection
     */
    private $formuleResultat;

    /**
     * @var Collection
     */
    protected $histoService;

    /**
     * @var MiseEnPaiementIntervenantStructure
     */
    protected $miseEnPaiementIntervenantStructure;

    /**
     * @var Collection
     */
    protected $modificationServiceDu;

    /**
     * @var Collection
     */
    protected $pieceJointe;

    /**
     * @var Collection
     */
    protected $service;

    /**
     * @var Collection
     */
    protected $serviceReferentiel;

    /**
     * @var Collection
     */
    protected $validation;

    /**
     * @var Collection
     */
    protected $indicModifDossier;

    /**
     * @var Collection
     */
    protected $missions;

    /**
     * Cache
     *
     * @var bool
     */
    protected $hasMiseEnPaiement = null;

    /**
     * @var \DateTime
     */
    protected $exportDate;

    protected bool $irrecevable = false;

    /**
     * @var string|null
     */
    protected $numeroPec;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->affectation                        = new ArrayCollection();
        $this->agrement                           = new ArrayCollection();
        $this->contrat                            = new ArrayCollection();
        $this->formuleResultat                    = new ArrayCollection();
        $this->histoService                       = new ArrayCollection();
        $this->miseEnPaiementIntervenantStructure = new ArrayCollection();
        $this->modificationServiceDu              = new ArrayCollection();
        $this->pieceJointe                        = new ArrayCollection();
        $this->service                            = new ArrayCollection();
        $this->serviceReferentiel                 = new ArrayCollection();
        $this->validation                         = new ArrayCollection();
        $this->missions                           = new ArrayCollection();
    }



    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
        return [
            'id',
            'code',
            'codeRh',
            'utilisateurCode',
            'nomUsuel',
            'prenom',
        ];
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
     * @param bool $demande
     */
    public function hasMiseEnPaiement($demande = true)
    {
        if ($this->hasMiseEnPaiement === null) {
            $id     = (int)$this->getId();
            $heures = $demande ? 'heures_demandees' : 'heures_payees';

            $sql = "SELECT COUNT(*) res FROM tbl_paiement p "
                . "WHERE p.intervenant_id = $id AND p.$heures" . "_AA + p.$heures" . "_AC > 0 AND rownum = 1";

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
    public function getStatut(): ?Statut
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
    public function setStatut(?Statut $statut = null)
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



    public function isSyncPec(): bool
    {
        return $this->syncPec;
    }



    public function setSyncPec(bool $syncPec): self
    {
        $this->syncPec = $syncPec;

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
     * @return Collection
     */
    public function getAffectation()
    {
        return $this->affectation;
    }



    /**
     * Get candidatures
     *
     * @return Collection
     */
    public function getCandidatures()
    {
        return $this->candidatures;
    }



    /**
     * Get agrement
     *
     * @return Collection
     */
    public function getAgrement(?TypeAgrement $typeAgrement = null)
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
     * @param \Contrat\Entity\Db\Contrat $contrat
     *
     * @return Intervenant
     */
    public function addContrat(\Contrat\Entity\Db\Contrat $contrat)
    {
        $this->contrat[] = $contrat;

        return $this;
    }



    /**
     * Remove contrat
     *
     * @param \Contrat\Entity\Db\Contrat $contrat
     */
    public function removeContrat(\Contrat\Entity\Db\Contrat $contrat)
    {
        $this->contrat->removeElement($contrat);
    }



    /**
     * Get contrat
     *
     * @param \Contrat\Entity\Db\TypeContrat $typeContrat
     * @param \Lieu\Entity\Db\Structure      $structure
     *
     * @return Collection
     */
    public function getContrat(?TypeContrat $typeContrat = null, ?Structure $structure = null)
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



    public function getAvenantEnfant(Contrat $contratParent)
    {
        if (null === $this->contrat) {
            return null;
        }

        $filter   = function (Contrat $contrat) use ($contratParent) {
            if ($contrat->getContrat() != $contratParent) {
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
    public function getContratInitial(): ?Contrat
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



    public function getFormuleResultat(TypeVolumeHoraire $typeVolumeHoraire, EtatVolumeHoraire $etatVolumehoraire): FormuleResultatIntervenant
    {
        $filter = function (FormuleResultatIntervenant $formuleResultat) use ($typeVolumeHoraire, $etatVolumehoraire) {
            if ($typeVolumeHoraire && $typeVolumeHoraire !== $formuleResultat->getTypeVolumeHoraire()) {
                return false;
            }
            if ($etatVolumehoraire && $etatVolumehoraire !== $formuleResultat->getEtatVolumeHoraire()) {
                return false;
            }

            return true;
        };

        $formuleResultat = $this->formuleResultat->filter($filter)->first();

        if (false === $formuleResultat) {
            $formuleResultat = new FormuleResultatIntervenant;
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
     * Add modificationServiceDu
     *
     * @param ModificationServiceDu $modificationServiceDu
     *
     * @return Intervenant
     */
    public function addModificationServiceDu(ModificationServiceDu $modificationServiceDu)
    {
        $this->modificationServiceDu[] = $modificationServiceDu;

        return $this;
    }



    /**
     * Remove modificationServiceDu
     *
     * @param ModificationServiceDu $modificationServiceDu
     * @param bool                  $softDelete
     */
    public function removeModificationServiceDu(ModificationServiceDu $modificationServiceDu, $softDelete = true)
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
     * @return Collection
     */
    public function getModificationServiceDu()
    {
        return $this->modificationServiceDu;
    }



    /**
     * Get pieceJointe
     *
     * @return Collection
     */
    public function getPieceJointe()
    {
        return $this->pieceJointe;
    }



    /**
     * Get service
     *
     * @return Collection|Service[]
     */
    public function getService()
    {
        return $this->service;
    }



    /**
     * Get service référentiel
     *
     * @return Collection|ServiceReferentiel[]
     */
    public function getServiceReferentiel()
    {
        return $this->serviceReferentiel;
    }



    /**
     * Get IndicModifDossier
     *
     * @return Collection
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
     * Get missions
     *
     * @return Collection|null|Mission[]
     */
    public function getMissions(): ?Collection
    {
        if (null === $this->missions) {
            return null;
        }

        $filter = function (Mission $mission) {
            return ($mission->estHistorise()) ? false : true;
        };

        return $this->missions->filter($filter);
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



    public function getNumeroPec(): ?string
    {
        return $this->numeroPec;
    }



    public function setNumeroPec(?string $numeroPec): self
    {
        $this->numeroPec = $numeroPec;

        return $this;
    }



    /**
     * Get validation
     *
     * @param \Workflow\Entity\Db\TypeValidation $type
     *
     * @return Collection
     */
    public function getValidation(?TypeValidation $type = null)
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



    public function getProfile(): UserProfile
    {
        $profile = new UserProfile($this->getStatut()->getRoleId(), $this->getStatut()->getTypeIntervenant()->getLibelle());
        $profile->setContext('statut', $this->getStatut());
        $profile->setContext('intervenant', $this);

        return $profile;
    }
}
