<?php

namespace Dossier\Entity\Db;

use Administration\Interfaces\ChampsAutresInterface;
use Administration\Traits\ChampsAutresTrait;
use Dossier\Entity\Db\Traits\EmployeurAwareTrait;
use Intervenant\Entity\Db\CiviliteAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Entity\Db\SituationMatrimonialeAwareTrait;
use Intervenant\Entity\Db\Statut;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\AdresseInterface;
use Lieu\Entity\AdresseTrait;
use Lieu\Entity\Db\Departement;
use Lieu\Entity\Db\Pays;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;

class IntervenantDossier implements HistoriqueAwareInterface, ResourceInterface, EntityManagerAwareInterface, AdresseInterface, ChampsAutresInterface
{
    use CiviliteAwareTrait;
    use AdresseTrait;
    use EmployeurAwareTrait;
    use HistoriqueAwareTrait;
    use EntityManagerAwareTrait;
    use ChampsAutresTrait;
    use SituationMatrimonialeAwareTrait;

    /**
     * @var int|null
     */
    protected $id;

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
     * @var integer
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
     * @var Intervenant|null
     */
    protected $intervenant;

    /**
     * @var TblDossier
     */
    protected $tblDossier;



    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }



    public function getResourceId(): string
    {
        return self::class;
    }



    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return strtoupper($this->getNomUsuel()) . ' IntervenantDossier.php' . ucfirst($this->getPrenom());
    }



    function __sleep()
    {
        return [];
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
     * @param ?Statut $statut
     *
     * @return IntervenantDossier
     */
    public function setStatut(?Statut $statut = null): IntervenantDossier
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
     * @return IntervenantDossier
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
     * @return IntervenantDossier
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
     * @return IntervenantDossier
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
     * @return IntervenantDossier
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
     * @return IntervenantDossier
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
     * @return IntervenantDossier
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
     * @return IntervenantDossier
     */
    public function setDepartementNaissance(?Departement $departementNaissance): IntervenantDossier
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
     * @return IntervenantDossier
     */
    public function setPaysNationalite(?Pays $paysNationalite): IntervenantDossier
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
     * @return IntervenantDossier
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
     * @return IntervenantDossier
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
     * @return IntervenantDossier
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
     * @return IntervenantDossier
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
     * @return IntervenantDossier
     */
    public function setNumeroInsee(?string $numeroInsee): IntervenantDossier
    {
        $this->numeroInsee = $numeroInsee;

        return $this;
    }



    /**
     * @return integer
     */
    public function getNumeroInseeProvisoire(): ?int
    {
        return $this->numeroInseeProvisoire;
    }



    /**
     * @param integer $numeroInseeProvisoire
     *
     * @return IntervenantDossier
     */
    public function setNumeroInseeProvisoire(?int $numeroInseeProvisoire): IntervenantDossier
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
     * @return IntervenantDossier
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
     * @return IntervenantDossier
     */
    public function setBIC(?string $BIC): IntervenantDossier
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
     * @param bool $ribHorsSepa
     *
     * @return IntervenantDossier
     */
    public function setRibHorsSepa(bool $ribHorsSepa): IntervenantDossier
    {
        $this->ribHorsSepa = $ribHorsSepa;

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
     * @return IntervenantDossier|null
     */
    public function getIntervenant(): ?Intervenant
    {
        return $this->intervenant;
    }



    public function getTblDossier(): ?TblDossier
    {
        return $this->tblDossier;
    }



    /**
     *
     * @param \Intervenant\Entity\Db\Intervenant $intervenant
     *
     * @return \Dossier\Entity\Db\IntervenantDossier
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
            ->setCommuneNaissance($intervenant->getCommuneNaissance())
            ->setPaysNaissance($intervenant->getPaysNaissance())
            ->setPaysNationalite($intervenant->getPaysNationalite())
            ->setDepartementNaissance($intervenant->getDepartementNaissance())
            ->setNumeroInsee($intervenant->getNumeroInsee())
            ->setNumeroInseeProvisoire($intervenant->getNumeroInseeProvisoire())
            ->setEmailPro($intervenant->getEmailPro())
            ->setEmailPerso($intervenant->getEmailPerso())
            ->setTelPerso($intervenant->getTelPerso())
            ->setTelPro($intervenant->getTelPro())
            ->setStatut($intervenant->getStatut())
            ->setIBAN(preg_replace('/\s+/', '', $intervenant->getIBAN() ?? ''))
            ->setRibHorsSepa($intervenant->isRibHorsSepa())
            ->setBIC(preg_replace('/\s+/', '', $intervenant->getBIC() ?? ''))
            ->setAdressePrecisions($intervenant->getAdressePrecisions())
            ->setAdresseCodePostal($intervenant->getAdresseCodePostal())
            ->setAdresseCommune($intervenant->getAdresseCommune())
            ->setAdresseLieuDit($intervenant->getAdresseLieuDit())
            ->setAdresseNumero($intervenant->getAdresseNumero())
            ->setAdresseNumeroCompl($intervenant->getAdresseNumeroCompl())
            ->setAdressePays($intervenant->getAdressePays())
            ->setAdresseVoie($intervenant->getAdresseVoie())
            ->setAdresseVoirie($intervenant->getAdresseVoirie())
            ->setAutre1($intervenant->getAutre1())
            ->setAutre2($intervenant->getAutre2())
            ->setAutre3($intervenant->getAutre3())
            ->setAutre4($intervenant->getAutre4())
            ->setAutre5($intervenant->getAutre5());

        return $this;
    }

}
