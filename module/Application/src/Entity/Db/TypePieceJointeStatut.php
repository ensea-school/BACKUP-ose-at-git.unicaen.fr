<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\TypePieceJointeAwareTrait;
use Application\Traits\ObligatoireSelonSeuilHeuresTrait;
use Intervenant\Entity\Db\StatutAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;


/**
 * TypePieceJointeStatut
 */
class TypePieceJointeStatut implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;
    use ObligatoireSelonSeuilHeuresTrait;
    use StatutAwareTrait;
    use TypePieceJointeAwareTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var float
     * @see  ObligatoireSelonSeuilHeuresTrait
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
