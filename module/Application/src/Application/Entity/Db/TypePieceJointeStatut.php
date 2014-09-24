<?php

namespace Application\Entity\Db;

use \Application\Traits\ObligatoireSelonSeuilHETDAwareTrait;

/**
 * TypePieceJointeStatut
 */
class TypePieceJointeStatut
{
    use ObligatoireSelonSeuilHETDAwareTrait;
    
    /**
     * @var \DateTime
     */
    private $histoCreation;

    /**
     * @var \DateTime
     */
    private $histoDestruction;

    /**
     * @var \DateTime
     */
    private $histoModification;

    /**
     * @var boolean
     */
    private $premierRecrutement;

    /**
     * @var \DateTime
     */
    private $validiteDebut;

    /**
     * @var \DateTime
     */
    private $validiteFin;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    private $histoModificateur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    private $histoDestructeur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    private $histoCreateur;

    /**
     * @var \Application\Entity\Db\TypePieceJointe
     */
    private $type;

    /**
     * @var \Application\Entity\Db\StatutIntervenant
     */
    private $statut;


    /**
     * Set histoCreation
     *
     * @param \DateTime $histoCreation
     * @return TypePieceJointeStatut
     */
    public function setHistoCreation($histoCreation)
    {
        $this->histoCreation = $histoCreation;

        return $this;
    }

    /**
     * Get histoCreation
     *
     * @return \DateTime 
     */
    public function getHistoCreation()
    {
        return $this->histoCreation;
    }

    /**
     * Set histoDestruction
     *
     * @param \DateTime $histoDestruction
     * @return TypePieceJointeStatut
     */
    public function setHistoDestruction($histoDestruction)
    {
        $this->histoDestruction = $histoDestruction;

        return $this;
    }

    /**
     * Get histoDestruction
     *
     * @return \DateTime 
     */
    public function getHistoDestruction()
    {
        return $this->histoDestruction;
    }

    /**
     * Set histoModification
     *
     * @param \DateTime $histoModification
     * @return TypePieceJointeStatut
     */
    public function setHistoModification($histoModification)
    {
        $this->histoModification = $histoModification;

        return $this;
    }

    /**
     * Get histoModification
     *
     * @return \DateTime 
     */
    public function getHistoModification()
    {
        return $this->histoModification;
    }

    /**
     * Set premierRecrutement
     *
     * @param boolean $premierRecrutement
     * @return TypePieceJointeStatut
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
     * Set obligatoire
     *
     * @param boolean $obligatoire
     * @return TypePieceJointeStatut
     */
    public function setObligatoire($obligatoire)
    {
        $this->obligatoire = $obligatoire;

        return $this;
    }

    /**
     * Set seuilHetd
     *
     * @param integer $seuilHetd
     * @return TypePieceJointeStatut
     */
    public function setSeuilHetd($seuilHetd)
    {
        $this->seuilHetd = $seuilHetd;

        return $this;
    }

    /**
     * Set validiteDebut
     *
     * @param \DateTime $validiteDebut
     * @return TypePieceJointeStatut
     */
    public function setValiditeDebut($validiteDebut)
    {
        $this->validiteDebut = $validiteDebut;

        return $this;
    }

    /**
     * Get validiteDebut
     *
     * @return \DateTime 
     */
    public function getValiditeDebut()
    {
        return $this->validiteDebut;
    }

    /**
     * Set validiteFin
     *
     * @param \DateTime $validiteFin
     * @return TypePieceJointeStatut
     */
    public function setValiditeFin($validiteFin)
    {
        $this->validiteFin = $validiteFin;

        return $this;
    }

    /**
     * Get validiteFin
     *
     * @return \DateTime 
     */
    public function getValiditeFin()
    {
        return $this->validiteFin;
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
     * Set histoModificateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoModificateur
     * @return TypePieceJointeStatut
     */
    public function setHistoModificateur(\Application\Entity\Db\Utilisateur $histoModificateur = null)
    {
        $this->histoModificateur = $histoModificateur;

        return $this;
    }

    /**
     * Get histoModificateur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getHistoModificateur()
    {
        return $this->histoModificateur;
    }

    /**
     * Set histoDestructeur
     *
     * @param \Application\Entity\Db\Utilisateur $histoDestructeur
     * @return TypePieceJointeStatut
     */
    public function setHistoDestructeur(\Application\Entity\Db\Utilisateur $histoDestructeur = null)
    {
        $this->histoDestructeur = $histoDestructeur;

        return $this;
    }

    /**
     * Get histoDestructeur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getHistoDestructeur()
    {
        return $this->histoDestructeur;
    }

    /**
     * Set histoCreateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoCreateur
     * @return TypePieceJointeStatut
     */
    public function setHistoCreateur(\Application\Entity\Db\Utilisateur $histoCreateur = null)
    {
        $this->histoCreateur = $histoCreateur;

        return $this;
    }

    /**
     * Get histoCreateur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getHistoCreateur()
    {
        return $this->histoCreateur;
    }

    /**
     * Set type
     *
     * @param \Application\Entity\Db\TypePieceJointe $type
     * @return TypePieceJointeStatut
     */
    public function setType(\Application\Entity\Db\TypePieceJointe $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Application\Entity\Db\TypePieceJointe 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set statutIntervenant
     *
     * @param \Application\Entity\Db\StatutIntervenant $statut
     * @return TypePieceJointeStatut
     */
    public function setStatut(\Application\Entity\Db\StatutIntervenant $statut = null)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statutIntervenant
     *
     * @return \Application\Entity\Db\StatutIntervenant 
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Redéfiniton pour gestion du cas particulier du RIB.
     * 
     * @todo Comment appeler la méthode getObligatoireToString() du trait ?
     */
    public function getObligatoireToString($totalHETDIntervenant)
    {
        if ($this->isObligatoire($totalHETDIntervenant)) {
            $seuilHETD   = $this->getSeuilHetd();
            $obligatoire = "À fournir obligatoirement";
            $obligatoire .= $this->isSeuilHETDDepasse($totalHETDIntervenant) ? 
                    " car <abbr title=\"Total d'heures de service réelles de l'intervenant toutes structures confondues\">total HETD</abbr> > {$seuilHETD}h" : 
                    null;
            return $obligatoire;
        }
        
        if ($this->getType()->getCode() === TypePieceJointe::RIB) {
            return "À fournir en cas de changement";
        }
        
        return "Facultatif";
    }
}
