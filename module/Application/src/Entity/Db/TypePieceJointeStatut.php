<?php

namespace Application\Entity\Db;

use \Application\Traits\ObligatoireSelonSeuilHeuresAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;


/**
 * TypePieceJointeStatut
 */
class TypePieceJointeStatut implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;
    use ObligatoireSelonSeuilHeuresAwareTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\TypePieceJointe
     */
    private $typePieceJointe;

    /**
     * @var \Application\Entity\Db\StatutIntervenant
     */
    private $statutIntervenant;

    /**
     * @var float
     * @see  ObligatoireSelonSeuilHeuresAwareTrait
     * @todo A supprimer lorsque la colonne de la table sera renommée "SEUIL_HEURES"
     */
    private $seuilHetd;

    /**
     * @var boolean
     */
    private $typeHeureHetd;

    /**
     * @var Annee
     */
    private $anneeDebut;

    /**
     * @var Annee
     */
    private $anneeFin;

    /**
     * @var boolean
     */
    private $fc;

    /**
     * @var boolean
     */
    private $changementRIB;

    /**
     * @var integeer
     */
    private $dureeVie;

    /**
     * @var boolean
     */
    private $obligatoireHNP;



    /**
     * Set obligatoire
     *
     * @param boolean $obligatoire
     *
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
     *
     * @return TypePieceJointeStatut
     */
    public function setSeuilHetd($seuilHetd)
    {
        $this->seuilHetd = $seuilHetd;

        return $this;
    }



    /**
     * Get seuilHetd
     *
     * @return integer
     */
    public function getSeuilHetd()
    {
        return $this->seuilHetd;
    }



    /**
     * Set typeHeure
     *
     * @param integer $typeHeureHetd
     *
     * @return TypePieceJointeStatut
     */
    public function setTypeHeureHetd($typeHeureHetd)
    {
        $this->typeHeureHetd = $typeHeureHetd;

        return $this;
    }



    /**
     * Get typeHeure
     *
     * @return integer
     */
    public function getTypeHeureHetd()
    {
        return $this->typeHeureHetd;
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
     * Set typePieceJointe
     *
     * @param \Application\Entity\Db\TypePieceJointe $typePieceJointe
     *
     * @return TypePieceJointeStatut
     */
    public function setTypePieceJointe(\Application\Entity\Db\TypePieceJointe $typePieceJointe = null)
    {
        $this->typePieceJointe = $typePieceJointe;

        return $this;
    }



    /**
     * Get typePieceJointe
     *
     * @return \Application\Entity\Db\TypePieceJointe
     */
    public function getTypePieceJointe()
    {
        return $this->typePieceJointe;
    }



    /**
     * Set statutIntervenant
     *
     * @param \Application\Entity\Db\StatutIntervenant $statutIntervenant
     *
     * @return TypePieceJointeStatut
     */
    public function setStatutIntervenant(\Application\Entity\Db\StatutIntervenant $statutIntervenant = null)
    {
        $this->statutIntervenant = $statutIntervenant;

        return $this;
    }



    /**
     * Get statutIntervenant
     *
     * @return \Application\Entity\Db\StatutIntervenant
     */
    public function getStatutIntervenant()
    {
        return $this->statutIntervenant;
    }



    /**
     * @return Annee
     */
    public function getAnneeDebut()
    {
        return $this->anneeDebut;
    }



    /**
     * @param Annee $anneeDebut
     *
     * @return TypePieceJointeStatut
     */
    public function setAnneeDebut(Annee $anneeDebut = null)
    {
        $this->anneeDebut = $anneeDebut;

        return $this;
    }



    /**
     * @return Annee
     */
    public function getAnneeFin()
    {
        return $this->anneeFin;
    }



    /**
     * @param Annee $anneeFin
     *
     * @return * TypePieceJointeStatut
     */
    public function setAnneeFin(Annee $anneeFin = null)
    {
        $this->anneeFin = $anneeFin;

        return $this;
    }



    /**
     * @return boolean
     */
    public function getFC()
    {
        if (!isset($this->fc)) return false;

        return $this->fc;
    }



    /**
     * @param boolean $fC
     *
     * * @return * TypePieceJointeStatut
     */
    public function setFC($fc = null)
    {
        $this->fc = $fc;

        return $this;
    }



    /**
     * @param boolean $changementRIB
     *
     * * @return * TypePieceJointeStatut
     */
    public function setChangementRIB($changementRIB = null)
    {
        $this->changementRIB = $changementRIB;

        return $this;
    }



    /**
     * @return boolean
     */
    public function getChangementRIB()
    {
        if (!isset($this->changementRIB)) return false;

        return $this->changementRIB;
    }



    /**
     * @return integer
     */
    public function getDureeVie()
    {
        if (!isset($this->dureeVie)) return 1;

        return $this->dureeVie;
    }



    /**
     * @param integer $dureeVie
     *
     * * @return * TypePieceJointeStatut
     */
    public function setDureeVie($dureeVie = null)
    {
        $this->dureeVie = $dureeVie;

        return $this;
    }



    /**
     * @return bool
     */
    public function isObligatoireHNP()
    {
        return $this->obligatoireHNP;
    }



    /**
     * @param bool $obligatoireHNP
     */
    public function setObligatoireHNP($obligatoireHNP)
    {
        $this->obligatoireHNP = $obligatoireHNP;

        return $this;
    }



    public function __toString()
    {
        $txt = $this->getObligatoire() ? 'Obl' : 'Fac';
        if ($this->getSeuilHetd()) $txt .= ' >' . $this->getSeuilHetd();
        if ($this->getFC()) $txt .= ' FC ';
        if ($this->getChangementRIB()) $txt .= ' RIB';
        if ($this->getDureeVie() && $this->getDureeVie() > 1) $txt .= ' ' . $this->getDureeVie() . 'ans';


        return $txt;
    }



    /**
     * @return string
     */
    public function getTitle(): string
    {
        $t   = [];
        $t[] = $this->getObligatoire() ? 'Pièce obligatoire' : 'Pièce facultative';
        if ($this->getSeuilHetd()) $t[] = 'À partir de ' . $this->getSeuilHetd() . ' heures';
        if ($this->getFC()) $t[] = 'Uniquement avec des enseignements en Formation Continue';
        if ($this->getChangementRIB()) $t[] = 'Uniquement si le RIB a changé';
        if ($this->getAnneeDebut()) $t[] = 'Actif à partir de ' . $this->getAnneeDebut();
        if ($this->getAnneeFin()) $t[] = 'Actif jusqu\'à' . $this->getAnneeFin();
        if ($this->getDureeVie()) $t[] = 'Redemander la pièce tous les ' . $this->getDureeVie();

        return implode("\n", $t);
    }

}
