<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * VolumeHoraire
 *
 * @ORM\Table(name="VOLUME_HORAIRE", indexes={@ORM\Index(name="IDX_C2A901856DA70281", columns={"TYPE_INTERVENTION_ID"}), @ORM\Index(name="IDX_C2A90185D09A4004", columns={"MOTIF_NON_PAIEMENT_ID"}), @ORM\Index(name="IDX_C2A90185C0569A10", columns={"PERIODE_ID"}), @ORM\Index(name="IDX_C2A90185DE8EF239", columns={"SERVICE_ID"})})
 * @ORM\Entity
 */
class VolumeHoraire
{
    /**
     * @var string
     *
     * @ORM\Column(name="A_PAYER", type="string", length=1, nullable=true)
     */
    private $aPayer;

    /**
     * @var float
     *
     * @ORM\Column(name="HEURES", type="float", precision=126, scale=0, nullable=false)
     */
    private $heures;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="VOLUME_HORAIRE_ID_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \Application\Entity\Db\TypeIntervention
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Db\TypeIntervention")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="TYPE_INTERVENTION_ID", referencedColumnName="ID")
     * })
     */
    private $typeIntervention;

    /**
     * @var \Application\Entity\Db\MotifNonPaiement
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Db\MotifNonPaiement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="MOTIF_NON_PAIEMENT_ID", referencedColumnName="ID")
     * })
     */
    private $motifNonPaiement;

    /**
     * @var \Application\Entity\Db\Periode
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Db\Periode")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PERIODE_ID", referencedColumnName="ID")
     * })
     */
    private $periode;

    /**
     * @var \Application\Entity\Db\Service
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Db\Service")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SERVICE_ID", referencedColumnName="ID")
     * })
     */
    private $service;



    /**
     * Set aPayer
     *
     * @param string $aPayer
     * @return VolumeHoraire
     */
    public function setAPayer($aPayer)
    {
        $this->aPayer = $aPayer;

        return $this;
    }

    /**
     * Get aPayer
     *
     * @return string 
     */
    public function getAPayer()
    {
        return $this->aPayer;
    }

    /**
     * Set heures
     *
     * @param float $heures
     * @return VolumeHoraire
     */
    public function setHeures($heures)
    {
        $this->heures = $heures;

        return $this;
    }

    /**
     * Get heures
     *
     * @return float 
     */
    public function getHeures()
    {
        return $this->heures;
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
     * Set typeIntervention
     *
     * @param \Application\Entity\Db\TypeIntervention $typeIntervention
     * @return VolumeHoraire
     */
    public function setTypeIntervention(\Application\Entity\Db\TypeIntervention $typeIntervention = null)
    {
        $this->typeIntervention = $typeIntervention;

        return $this;
    }

    /**
     * Get typeIntervention
     *
     * @return \Application\Entity\Db\TypeIntervention 
     */
    public function getTypeIntervention()
    {
        return $this->typeIntervention;
    }

    /**
     * Set motifNonPaiement
     *
     * @param \Application\Entity\Db\MotifNonPaiement $motifNonPaiement
     * @return VolumeHoraire
     */
    public function setMotifNonPaiement(\Application\Entity\Db\MotifNonPaiement $motifNonPaiement = null)
    {
        $this->motifNonPaiement = $motifNonPaiement;

        return $this;
    }

    /**
     * Get motifNonPaiement
     *
     * @return \Application\Entity\Db\MotifNonPaiement 
     */
    public function getMotifNonPaiement()
    {
        return $this->motifNonPaiement;
    }

    /**
     * Set periode
     *
     * @param \Application\Entity\Db\Periode $periode
     * @return VolumeHoraire
     */
    public function setPeriode(\Application\Entity\Db\Periode $periode = null)
    {
        $this->periode = $periode;

        return $this;
    }

    /**
     * Get periode
     *
     * @return \Application\Entity\Db\Periode 
     */
    public function getPeriode()
    {
        return $this->periode;
    }

    /**
     * Set service
     *
     * @param \Application\Entity\Db\Service $service
     * @return VolumeHoraire
     */
    public function setService(\Application\Entity\Db\Service $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \Application\Entity\Db\Service 
     */
    public function getService()
    {
        return $this->service;
    }
}
