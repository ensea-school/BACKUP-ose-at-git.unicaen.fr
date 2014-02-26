<?php

namespace Import\Model\Entity\Intervenant;

use Import\Model\Entity\Entity;

/**
 *
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class Adresse extends Entity {

    /**
     * Identifiant
     *
     * @var integer
     */
    protected $id;

    /**
     * ID intervenant
     *
     * @var integer
     */
    protected $intervenantId;

    /**
     * Si l'adresse est principale ou non
     *
     * @var boolean
     */
    protected $principale;

    /**
     * Téléphone du domicile
     *
     * @var string
     */
    protected $telDomicile;

    /**
     * Mention complémentaire
     *
     * @var string
     */
    protected $mentionComplementaire;

    /**
     * Bâtiment
     *
     * @var string
     */
    protected $batiment;

    /**
     * Numéro de voie
     *
     * @var string
     */
    protected $noVoie;

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
     * Ville
     *
     * @var string
     */
    protected $ville;

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





    public function getId()
    {
        return $this->id;
    }

    public function getIntervenantId()
    {
        return $this->intervenantId;
    }

    public function getPrincipale()
    {
        return $this->principale;
    }

    public function getTelDomicile()
    {
        return $this->telDomicile;
    }

    public function getMentionComplementaire()
    {
        return $this->mentionComplementaire;
    }

    public function getBatiment()
    {
        return $this->batiment;
    }

    public function getNoVoie()
    {
        return $this->noVoie;
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

    public function getVille()
    {
        return $this->ville;
    }

    public function getPaysCodeInsee()
    {
        return $this->paysCodeInsee;
    }

    public function getPaysLibelle()
    {
        return $this->paysLibelle;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setIntervenantId($intervenantId)
    {
        $this->intervenantId = $intervenantId;
        return $this;
    }

    public function setPrincipale($principale)
    {
        $this->principale = $principale;
        return $this;
    }

    public function setTelDomicile($telDomicile)
    {
        $this->telDomicile = $telDomicile;
        return $this;
    }

    public function setMentionComplementaire($mentionComplementaire)
    {
        $this->mentionComplementaire = $mentionComplementaire;
        return $this;
    }

    public function setBatiment($batiment)
    {
        $this->batiment = $batiment;
        return $this;
    }

    public function setNoVoie($noVoie)
    {
        $this->noVoie = $noVoie;
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

    public function setVille($ville)
    {
        $this->ville = $ville;
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

}