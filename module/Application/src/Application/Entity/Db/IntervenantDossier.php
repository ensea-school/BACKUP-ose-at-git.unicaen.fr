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
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * IntervenantDossier
 *
 */
class IntervenantDossier implements HistoriqueAwareInterface, ResourceInterface, ObjectManagerAware, AdresseInterface
{
    use CiviliteAwareTrait;
    use AdresseTrait;
    use EmployeurAwareTrait;
    use HistoriqueAwareTrait;
    use EntityManagerAwareTrait;

    /**
     * @var int|null
     */
    protected $id;


    /**
     * @var \Application\Entity\Db\StatutIntervenant
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
     * @var string|null
     */
    private $villeNaissance;

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
    protected $numeroInseeProvisoire;

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
     * @var Intervenant|null
     */
    protected $intervenant;

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
        return 'IntervenantDossier';
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
     * Get statut
     *
     * @return StatutIntervenant
     */
    public function getStatut()
    {
        return $this->statut;
    }



    /**
     * Set statut
     *
     * @param StatutIntervenant $statut
     *
     *@return IntervenantDossier
     */
    public function setStatut(StatutIntervenant $statut = null)
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
     *@return IntervenantDossier
     */
    public function setNomUsuel(?string $nomUsuel): IntervenantDossier
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
     *@return IntervenantDossier
     */
    public function setPrenom(?string $prenom): IntervenantDossier
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
     *@return IntervenantDossier
     */
    public function setDateNaissance(?\DateTime $dateNaissance): IntervenantDossier
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
     *@return IntervenantDossier
     */
    public function setNomPatronymique(?string $nomPatronymique): IntervenantDossier
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
     *@return IntervenantDossier
     */
    public function setCommuneNaissance(?string $communeNaissance): IntervenantDossier
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
     *@return IntervenantDossier
     */
    public function setPaysNaissance(?Pays $paysNaissance): IntervenantDossier
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
     *@return IntervenantDossier
     */
    public function setDepartementNaissance(?Departement $departementNaissance): IntervenantDossier
    {
        $this->departementNaissance = $departementNaissance;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getVilleNaissance(): ?string
    {
        return $this->villeNaissance;
    }



    /**
     * @param string|null $villeNaissance
     */
    public function setVilleNaissance(?string $villeNaissance): IntervenantDossierDossier
    {
        $this->villeNaissance = $villeNaissance;

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
     *@return IntervenantDossier
     */
    public function setPaysNationalite(?Pays $paysNationalite): IntervenantDossierDossier
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
     *@return IntervenantDossier
     */
    public function setTelPro(?string $telPro): IntervenantDossier
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
     *@return IntervenantDossier
     */
    public function setTelPerso(?string $telPerso): IntervenantDossier
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
     *@return IntervenantDossier
     */
    public function setEmailPro(?string $emailPro): IntervenantDossier
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
     *@return IntervenantDossier
     */
    public function setEmailPerso(?string $emailPerso): IntervenantDossier
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
     *@return IntervenantDossier
     */
    public function setNumeroInsee(?string $numeroInsee): IntervenantDossier
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
     *@return IntervenantDossier
     */
    public function setNumeroInseeProvisoire(?bool $numeroInseeProvisoire): IntervenantDossier
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
     *@return IntervenantDossier
     */
    public function setIBAN(?string $IBAN): IntervenantDossier
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
     *@return IntervenantDossier
     */
    public function setBIC(?string $BIC): IntervenantDossier
    {
        $this->BIC = $BIC;

        return $this;
    }



    /**
     * @return bool
     */
    public function isRibHorsSepa(): bool
    {
        return $this->ribHorsSepa;
    }



    /**
     * @param bool $ribHorsSepa
     *
     *@return IntervenantDossier
     */
    public function setRibHorsSepa(bool $ribHorsSepa): IntervenantDossier
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
     *@return IntervenantDossier
     */
    public function setAutre1(?string $autre1): IntervenantDossier
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
     *@return IntervenantDossier
     */
    public function setAutre2(?string $autre2): IntervenantDossier
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
     *@return IntervenantDossier
     */
    public function setAutre3(?string $autre3): IntervenantDossier
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
     *@return IntervenantDossier
     */
    public function setAutre4(?string $autre4): IntervenantDossier
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
     *@return IntervenantDossier
     */
    public function setAutre5(?string $autre5): IntervenantDossier
    {
        $this->autre5 = $autre5;

        return $this;
    }

    /**
     * @param Intervenant|null $intervenant
     *
     * @return self
     */
    public function setIntervenant(?Intervenant $intervenant = null)
    {
        $this->intervenant = $intervenant;

        return $this;
    }



    /**
     *@return IntervenantDossier|null
     */
    public function getIntervenant(): ?Intervenant
    {
        return $this->intervenant;
    }

    /**
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     *
     * @return \Application\Entity\Db\IntervenantDossier
     */
    public function fromIntervenant(Intervenant $intervenant)
    {
        $this
            ->setIntervenant($intervenant)
            ->setNomUsuel($intervenant->getNomUsuel())
            ->setNomPatronymique($intervenant->getNomPatronymique())
            ->setPrenom($intervenant->getPrenom())
            ->setCivilite($intervenant->getCivilite())
            ->setDateNaissance($intervenant->getDateNaissance())
            ->setPaysNaissance($intervenant->getPaysNaissance())
            ->setDepartementNaissance($intervenant->getDepartementNaissance())
            ->setNumeroInsee($intervenant->getNumeroInsee())
            ->setEmailPerso($intervenant->getEmailPerso())
            ->setTelPerso($intervenant->getTelPerso())
            ->setTelPro($intervenant->getTelPro())
            ->setStatut($intervenant->getStatut());
            //->setRib(preg_replace('/\s+/', '', $intervenant->getBIC() . '-' . $intervenant->getIBAN()))
            //TODO refactor complet de l'adresse
            //->setAdresse($intervenant->getAdresse(false));

        return $this;
    }

}
