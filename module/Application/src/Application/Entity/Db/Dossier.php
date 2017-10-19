<?php

namespace Application\Entity\Db;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * Dossier
 */
class Dossier implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;
    use IntervenantAwareTrait;

    /**
     * @var string
     */
    protected $adresse;

    /**
     * @var Civilite
     */
    protected $civilite;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $emailPerso;

    /**
     * @var string
     */
    protected $telephone;

    /**
     * @var string
     */
    protected $nomPatronymique;

    /**
     * @var string
     */
    protected $nomUsuel;

    /**
     * @var string
     */
    protected $numeroInsee;

    /**
     * @var boolean
     */
    protected $numeroInseeEstProvisoire = false;

    /**
     * @var string
     */
    protected $prenom;

    /**
     * @var string
     */
    protected $rib;

    /**
     * @var StatutIntervenant
     */
    protected $statut;

    /**
     * @var boolean
     */
    protected $premierRecrutement = false;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $pieceJointe;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \DateTime
     */
    protected $dateNaissance;

    /**
     * @var \Application\Entity\Db\Pays
     */
    protected $paysNaissance;

    /**
     * @var \Application\Entity\Db\Departement
     */
    protected $departementNaissance;

    /**
     * @var string
     */
    protected $villeNaissance;

    /**
     *
     */
    public function __construct()
    {
        $this->pieceJointe = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     * @return Dossier
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set civilite
     *
     * @param Civilite $civilite
     * @return Dossier
     */
    public function setCivilite(Civilite $civilite)
    {
        $this->civilite = $civilite;

        return $this;
    }

    /**
     * Get civilite
     *
     * @return Civilite
     */
    public function getCivilite()
    {
        return $this->civilite;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Dossier
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
     * Set emailPerso
     *
     * @param string $emailPerso
     * @return Dossier
     */
    public function setEmailPerso($emailPerso)
    {
        $this->emailPerso = $emailPerso;

        return $this;
    }

    /**
     * Get emailPerso
     *
     * @return string
     */
    public function getEmailPerso()
    {
        return $this->emailPerso;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return Dossier
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set nomPatronymique
     *
     * @param string $nomPatronymique
     * @return Dossier
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
     * @return Dossier
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
     * @return Dossier
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
     * Set numeroInseeEstProvisoire
     *
     * @param boolean $numeroInseeEstProvisoire
     * @return Dossier
     */
    public function setNumeroInseeEstProvisoire($numeroInseeEstProvisoire)
    {
        $this->numeroInseeEstProvisoire = $numeroInseeEstProvisoire;

        return $this;
    }

    /**
     * Get numeroInseeEstProvisoire
     *
     * @return boolean
     */
    public function getNumeroInseeEstProvisoire()
    {
        return $this->numeroInseeEstProvisoire;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return Dossier
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
     * Set rib
     *
     * @param string $rib
     * @return Dossier
     */
    public function setRib($rib)
    {
        $this->rib = $rib;

        return $this;
    }

    /**
     * Get rib
     *
     * @return string
     */
    public function getRib()
    {
        return $this->rib;
    }

    /**
     * Set statut
     *
     * @param StatutIntervenant $statut
     * @return Dossier
     */
    public function setStatut(StatutIntervenant $statut = null)
    {
        $this->statut = $statut;

        return $this;
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
     * Set premierRecrutement
     *
     * @param boolean $premierRecrutement
     * @return Dossier
     */
    public function setPremierRecrutement($premierRecrutement)
    {
        $this->premierRecrutement = $premierRecrutement;

        return $this;
    }

    /**
     * Get premierRecrutement
     *
     * @return boolean
     */
    public function getPremierRecrutement()
    {
        return $this->premierRecrutement;
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
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPieceJointe()
    {
        return $this->pieceJointe;
    }

    /**
     *
     * @return PieceJointe
     */
    public function addPieceJointe(PieceJointe $pieceJointe)
    {
        $this->pieceJointe->add($pieceJointe);

        return $this;
    }

    /**
     *
     * @param PieceJointe $pieceJointe
     * @return self
     */
    public function removePieceJointe(PieceJointe $pieceJointe)
    {
        $this->pieceJointe->removeElement($pieceJointe);

        return $this;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     * @return self
     */
    public function setDateNaissance($dateNaissance)
    {
        $dateNaissance->setTime(0, 0, 0);

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
     * Set paysNaissance
     *
     * @param Pays $paysNaissance
     * @return self
     */
    public function setPaysNaissance($paysNaissance)
    {
        $this->paysNaissance = $paysNaissance;

        return $this;
    }

    /**
     * Get paysNaissance
     *
     * @return Pays
     */
    public function getPaysNaissance()
    {
        return $this->paysNaissance;
    }

    /**
     * Set departementNaissance
     *
     * @param Departement $departementNaissance
     * @return self
     */
    public function setDepartementNaissance($departementNaissance)
    {
        $this->departementNaissance = $departementNaissance;

        return $this;
    }

    /**
     * Get departementNaissance
     *
     * @return Departement
     */
    public function getDepartementNaissance()
    {
        return $this->departementNaissance;
    }

    /**
     * Set villeNaissance
     *
     * @param string $villeNaissance
     * @return self
     */
    public function setVilleNaissance($villeNaissance)
    {
        $this->villeNaissance = $villeNaissance;

        return $this;
    }

    /**
     * Get villeNaissance
     *
     * @return string
     */
    public function getVilleNaissance()
    {
        return $this->villeNaissance;
    }

    /**
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @return \Application\Entity\Db\Dossier
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
                ->setDepartementNaissance($intervenant->getDepNaissance())
                ->setVilleNaissance($intervenant->getVilleNaissanceLibelle())
                ->setNumeroInsee($intervenant->getNumeroInsee() . $intervenant->getNumeroInseeCle())
                ->setAdresse((string) $intervenant->getAdressePrincipale())
                ->setEmail($intervenant->getEmail())
                ->setTelephone($intervenant->getTelPro() ?: $intervenant->getTelMobile())
                ->setStatut($intervenant->getStatut())
                ->setRib(preg_replace('/\s+/', '', $intervenant->getBIC() . '-' . $intervenant->getIBAN()));

        return $this;
    }
}
