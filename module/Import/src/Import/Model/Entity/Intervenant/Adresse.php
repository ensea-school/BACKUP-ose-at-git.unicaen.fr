<?php

namespace Import\Model\Entity\Intervenant;

use DateTime;
use Import\Model\Entity\Entity;

/**
 *
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 * 
 * @property boolean $principale
 * @property string $habitantChez
 * @property string $telDomicile
 * @property string $noVoie
 * @property string $bisTerId
 * @property string $typeVoieId
 * @property string $nomVoie
 * @property string $localite
 * @property string $codePostal
 * @property string $villeCodeInsee
 * @property string $villeLibelle
 * @property string $paysCodeInsee
 * @property string $paysLibelle
 * @property DateTime $histoDebut
 * @property DateTime $histoModification
 * @property DateTime $histoDebut
 * @property DateTime $histoFin
 */
class Adresse extends Entity {

    /**
     * Si l'adresse est principale ou non
     *
     * @var boolean
     */
    protected $principale;

    /**
     * Si l'intervenant habite chez un autre ou non
     *
     * @var string
     */
    protected $habitantChez;

    /**
     * Téléphone du domicile
     *
     * @var string
     */
    protected $telDomicile;

    /**
     * Numéro de voie
     *
     * @var string
     */
    protected $noVoie;

    /**
     * Bis, Ter, Quarter, Quinties
     *
     * @var string
     */
    protected $bisTerId;

    /**
     * Code du type de voie (rue, voie, chemin, etc)
     *
     * @var string
     */
    protected $typeVoieId;

    /**
     * Nom de la voie
     *
     * @var string
     */
    protected $nomVoie;

    /**
     * Localité
     *
     * @var string
     */
    protected $localite;

    /**
     * Code postal
     *
     * @var string
     */
    protected $codePostal;

    /**
     * Code INSEE de la ville
     *
     * @var string
     */
    protected $villeCodeInsee;

    /**
     * Libellé de la ville
     *
     * @var string
     */
    protected $villeLibelle;

    /**
     * Code INSEE du pays
     *
     * @var string
     */
    protected $paysCodeInsee;

    /**
     * Libelle du pays
     *
     * @var string
     */
    protected $paysLibelle;

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


    


    public function getPrincipale()
    {
        return $this->principale;
    }

    public function getHabitantChez()
    {
        return $this->habitantChez;
    }

    public function getTelDomicile()
    {
        return $this->telDomicile;
    }

    public function getNoVoie()
    {
        return $this->noVoie;
    }

    public function getBisTerId()
    {
        return $this->bisTerId;
    }

    public function getTypeVoieId()
    {
        return $this->typeVoieId;
    }

    public function getNomVoie()
    {
        return $this->nomVoie;
    }

    public function getLocalite()
    {
        return $this->localite;
    }

    public function getCodePostal()
    {
        return $this->codePostal;
    }

    public function getVilleCodeInsee()
    {
        return $this->villeCodeInsee;
    }

    public function getVilleLibelle()
    {
        return $this->villeLibelle;
    }

    public function getPaysCodeInsee()
    {
        return $this->paysCodeInsee;
    }

    public function getPaysLibelle()
    {
        return $this->paysLibelle;
    }

    public function getHistoDebut()
    {
        return $this->histoDebut;
    }

    public function getHistoFin()
    {
        return $this->histoFin;
    }

    public function setPrincipale($principale)
    {
        $this->principale = $principale;
        return $this;
    }

    public function setHabitantChez($habitantChez)
    {
        $this->habitantChez = $habitantChez;
        return $this;
    }

    public function setTelDomicile($telDomicile)
    {
        $this->telDomicile = $telDomicile;
        return $this;
    }

    public function setNoVoie($noVoie)
    {
        $this->noVoie = $noVoie;
        return $this;
    }

    public function setBisTerId($bisTerId)
    {
        $this->bisTerId = $bisTerId;
        return $this;
    }

    public function setTypeVoieId($typeVoieId)
    {
        $this->typeVoieId = $typeVoieId;
        return $this;
    }

    public function setNomVoie($nomVoie)
    {
        $this->nomVoie = $nomVoie;
        return $this;
    }

    public function setLocalite($localite)
    {
        $this->localite = $localite;
        return $this;
    }

    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;
        return $this;
    }

    public function setVilleCodeInsee($villeCodeInsee)
    {
        $this->villeCodeInsee = $villeCodeInsee;
        return $this;
    }

    public function setVilleLibelle($villeLibelle)
    {
        $this->villeLibelle = $villeLibelle;
        return $this;
    }

    public function setPaysCodeInsee($paysCodeInsee)
    {
        $this->paysCodeInsee = $paysCodeInsee;
        return $this;
    }

    public function setPaysLibelle($paysLibelle)
    {
        $this->paysLibelle = $paysLibelle;
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