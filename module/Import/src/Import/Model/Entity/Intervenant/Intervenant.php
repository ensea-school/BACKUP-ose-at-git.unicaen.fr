<?php

namespace Import\Model\Entity\Intervenant;

use DateTime;
use Import\Model\Entity\Entity;

/**
 *
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class Intervenant extends Entity {

    /**
     * Identifiant
     *
     * @var integer
     */
    protected $id;

    /**
     * Civilité
     *
     * @var integer
     */
    protected $civiliteId;

    /**
     * Nom usuel
     *
     * @var string
     */
    protected $nomUsuel;

    /**
     * Prénom
     *
     * @var string
     */
    protected $prenom;

    /**
     * Nom patronymique
     *
     * @var string
     */
    protected $nomPatronymique;

    /**
     * Date de naissance
     *
     * @var DateTime
     */
    protected $dateNaissance;

    /**
     * Code INSEE du pays de naissance
     *
     * @var string
     */
    protected $paysNaissanceCodeInsee;

    /**
     * Libellé du pays de naissance
     *
     * @var string
     */
    protected $paysNaissanceLibelle;

    /**
     * Code INSEE du département de naissance
     *
     * @var string
     */
    protected $depNaissanceCodeInsee;

    /**
     * Libellé du département de naissance
     *
     * @var string
     */
    protected $depNaissanceLibelle;

    /**
     * Code INSEE de la ville de naissance
     *
     * @var string
     */
    protected $villeNaissanceCodeInsee;

    /**
     * Libellé de la ville de naissance
     *
     * @var string
     */
    protected $villeNaissanceLibelle;

    /**
     * Code INSEE de la nationalité
     *
     * @var string
     */
    protected $paysNationaliteCodeInsee;

    /**
     * Libellé du pays de nationalité
     *
     * @var string
     */
    protected $paysNationaliteLibelle;


    /**
     * Téléphone professionnel
     *
     * @var string
     */
    protected $telPro;

    /**
     * Téléphone mobile
     *
     * @var string
     */
    protected $telMobile;

    /**
     * Email Pro
     *
     * @var string
     */
    protected $email;

    /**
     * Identifiant du type
     *
     * @var integer
     */
    protected $typeId;

    /**
     * Prime d'excellence scientifique
     *
     * @var boolean
     */
    protected $primeExcellenceScientifique;

    /**
     * Numéro INSEE
     *
     * @var string
     */
    protected $numeroInsee;

    /**
     * Clé du numéro INSEE
     *
     * @var string
     */
    protected $numeroInseeCle;

    /**
     * Si le numéro INSEE est provisoire ou non
     *
     * @var boolean
     */
    protected $numeroInseeProvisoire;


    


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getCiviliteId()
    {
        return $this->civiliteId;
    }

    public function getNomUsuel()
    {
        return $this->nomUsuel;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function getNomPatronymique()
    {
        return $this->nomPatronymique;
    }

    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    public function getPaysNaissanceCodeInsee()
    {
        return $this->paysNaissanceCodeInsee;
    }

    public function getPaysNaissanceLibelle()
    {
        return $this->paysNaissanceLibelle;
    }

    public function getDepNaissanceCodeInsee()
    {
        return $this->depNaissanceCodeInsee;
    }

    public function getDepNaissanceLibelle()
    {
        return $this->depnaissanceLibelle;
    }

    public function getVilleNaissanceCodeInsee()
    {
        return $this->villeNaissanceCodeInsee;
    }

    public function getVilleNaissanceLibelle()
    {
        return $this->villeNaissanceLibelle;
    }

    public function getPaysNationaliteCodeInsee()
    {
        return $this->paysNationaliteCodeInsee;
    }

    public function getPaysNationaliteLibelle()
    {
        return $this->paysNationaliteLibelle;
    }

    public function getTelMobile()
    {
        return $this->telMobile;
    }

    public function getTelPro()
    {
        return $this->telPro;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getTypeId()
    {
        return $this->typeId;
    }

    public function getPrimeExcellenceScientifique()
    {
        return $this->primeExcellenceScientifique;
    }

    public function getNumeroInsee()
    {
        return $this->numeroInsee;
    }

    public function getNumeroInseeCle()
    {
        return $this->numeroInseeCle;
    }

    public function getNumeroInseeProvisoire()
    {
        return $this->numeroInseeProvisoire;
    }

    public function setCiviliteId($civiliteId)
    {
        $this->civiliteId = $civiliteId;
        return $this;
    }

    public function setNomUsuel($nomUsuel)
    {
        $this->nomUsuel = $nomUsuel;
        return $this;
    }

    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function setNomPatronymique($nomPatronymique)
    {
        $this->nomPatronymique = $nomPatronymique;
        return $this;
    }

    public function setDateNaissance(DateTime $dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;
        return $this;
    }

    public function setPaysNaissanceCodeInsee($paysNaissanceCodeInsee)
    {
        $this->paysNaissanceCodeInsee = $paysNaissanceCodeInsee;
        return $this;
    }

    public function setPaysNaissanceLibelle($paysNaissanceLibelle)
    {
        $this->paysNaissanceLibelle = $paysNaissanceLibelle;
        return $this;
    }

    public function setDepNaissanceCodeInsee($depNaissanceCodeInsee)
    {
        $this->depNaissanceCodeInsee = $depNaissanceCodeInsee;
        return $this;
    }

    public function setDepNaissanceLibelle($depnaissanceLibelle)
    {
        $this->depnaissanceLibelle = $depnaissanceLibelle;
        return $this;
    }

    public function setVilleNaissanceCodeInsee($villeNaissanceCodeInsee)
    {
        $this->villeNaissanceCodeInsee = $villeNaissanceCodeInsee;
        return $this;
    }

    public function setVilleNaissanceLibelle($villeNaissanceLibelle)
    {
        $this->villeNaissanceLibelle = $villeNaissanceLibelle;
        return $this;
    }

    public function setPaysNationaliteCodeInsee($paysNationaliteCodeInsee)
    {
        $this->paysNationaliteCodeInsee = $paysNationaliteCodeInsee;
        return $this;
    }

    public function setPaysNationaliteLibelle($paysNationaliteLibelle)
    {
        $this->paysNationaliteLibelle = $paysNationaliteLibelle;
        return $this;
    }

    public function setTelMobile($telMobile)
    {
        $this->telMobile = $telMobile;
        return $this;
    }

    public function setTelPro($telPro)
    {
        $this->telPro = $telPro;
        return $this;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;
        return $this;
    }

    public function setPrimeExcellenceScientifique($primeExcellenceScientifique)
    {
        $this->primeExcellenceScientifique = $primeExcellenceScientifique;
        return $this;
    }

    public function setNumeroInsee($numeroInsee)
    {
        $this->numeroInsee = $numeroInsee;
        return $this;
    }

    public function setNumeroInseeCle($numeroInseeCle)
    {
        $this->numeroInseeCle = $numeroInseeCle;
        return $this;
    }

    public function setNumeroInseeProvisoire($numeroInseeProvisoire)
    {
        $this->numeroInseeProvisoire = $numeroInseeProvisoire;
        return $this;
    }

}
