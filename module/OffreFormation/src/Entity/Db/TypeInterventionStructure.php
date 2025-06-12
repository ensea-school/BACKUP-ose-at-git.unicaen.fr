<?php

namespace OffreFormation\Entity\Db;

use Application\Entity\Db\Annee;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use OffreFormation\Entity\Db\Traits\TypeInterventionAwareTrait;
use Lieu\Entity\Db\StructureAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * TypeInterventionStructure
 */
class TypeInterventionStructure implements HistoriqueAwareInterface, ResourceInterface
{
    use HistoriqueAwareTrait;
    use TypeInterventionAwareTrait;
    use StructureAwareTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $visible;

    /**
     * @var Annee
     */
    private $anneeDebut;

    /**
     * @var Annee
     */
    private $anneeFin;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }



    public function getResourceId(): string
    {
        return 'TypeInterventionStructure';
    }



    /**
     * @return bool
     */
    public function isVisible()
    {
        return $this->visible;
    }



    /**
     * @param bool $visible
     *
     * @return TypeInterventionStructure
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
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
     * @return TypeInterventionStructure
     */
    public function setAnneeDebut(?Annee $anneeDebut = null)
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
     * @return TypeInterventionStructure
     */
    public function setAnneeFin(?Annee $anneeFin = null)
    {
        $this->anneeFin = $anneeFin;

        return $this;
    }



    /**
     * @param Annee          $annee
     *
     * @return bool
     */
    public function isValide(Annee $annee): bool
    {
        $deb = $this->getAnneeDebut() ?: $this->getTypeIntervention()->getAnneeDebut();
        $fin = $this->getAnneeFin() ?: $this->getTypeIntervention()->getAnneeFin();

        $deb = $deb ? $deb->getId() : 0;
        $fin = $fin ? $fin->getId() : 99999;
        $a = $annee->getId();

        return $a >= $deb && $a <= $fin;
    }

}
