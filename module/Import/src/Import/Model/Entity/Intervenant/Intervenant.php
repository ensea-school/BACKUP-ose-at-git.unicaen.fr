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
     * Civilité
     *
     * @var string
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
    protected $dateDeNaissance;

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
    protected $depnaissanceLibelle;

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
     * Téléphone mobile
     *
     * @var string
     */
    protected $telMobile;

    /**
     * Téléphone professionnel
     *
     * @var string
     */
    protected $telPro;

    /**
     * Email Pro
     *
     * @var string
     */
    protected $email;

    /**
     * Identifiant du type (P ou E)
     *
     * @var string
     */
    protected $typeId;

    /**
     * Identifiant de la structure d'affectation principale
     *
     * @var string
     */
    protected $structureId;

    /**
     * Source de données
     *
     * @var string
     */
    protected $source;

    /**
     * Identifiant de l'intervenant au niveau de la source
     *
     * @var string
     */
    protected $sourceId;

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

    /**
     * Date de début d'historique
     *
     * @var DateTime
     */
    protected $histoDebut;

    /**
     * Date de fin d'historique
     *
     * @var DateTime
     */
    protected $histoFin;





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

    public function getDateDeNaissance()
    {
        return $this->dateDeNaissance;
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

    public function getDepnaissanceLibelle()
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

    public function getStructureId()
    {
        return $this->structureId;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getSourceId()
    {
        return $this->sourceId;
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

    public function getHistoDebut()
    {
        return $this->histoDebut;
    }

    public function getHistoFin()
    {
        return $this->histoFin;
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

    public function setDateDeNaissance(DateTime $dateDeNaissance)
    {
        $this->dateDeNaissance = $dateDeNaissance;
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

    public function setDepnaissanceLibelle($depnaissanceLibelle)
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

    public function setStructureId($structureId)
    {
        $this->structureId = $structureId;
        return $this;
    }

    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    public function setSourceId($sourceId)
    {
        $this->sourceId = $sourceId;
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

    public function setHistoDebut(DateTime $histoDebut)
    {
        $this->histoDebut = $histoDebut;
        return $this;
    }

    public function setHistoFin(DateTime $histoFin)
    {
        $this->histoFin = $histoFin;
        return $this;
    }

}
