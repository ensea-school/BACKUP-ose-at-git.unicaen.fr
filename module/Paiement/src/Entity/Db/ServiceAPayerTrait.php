<?php

namespace Paiement\Entity\Db;

use Application\Entity\Db\MiseEnPaiementListe;
use Application\Entity\Db\Periode;
use OffreFormation\Entity\Db\TypeHeures;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraire;

trait ServiceAPayerTrait
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\FormuleResultat
     */
    private $formuleResultat;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $miseEnPaiement;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $centreCout;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->miseEnPaiement = new \Doctrine\Common\Collections\ArrayCollection();
        $this->centreCout     = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add miseEnPaiement
     *
     * @param MiseEnPaiement $miseEnPaiement
     *
     * @return ServiceAPayerInterface
     */
    public function addMiseEnPaiement(\Paiement\Entity\Db\MiseEnPaiement $miseEnPaiement)
    {
        $this->miseEnPaiement[] = $miseEnPaiement;

        return $this;
    }



    /**
     * Remove miseEnPaiement
     *
     * @param \Paiement\Entity\Db\MiseEnPaiement $miseEnPaiement
     *
     * @return ServiceAPayerInterface
     */
    public function removeMiseEnPaiement(\Paiement\Entity\Db\MiseEnPaiement $miseEnPaiement)
    {
        $this->miseEnPaiement->removeElement($miseEnPaiement);

        return $this;
    }



    /**
     * Get miseEnPaiement
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMiseEnPaiement()
    {
        return $this->miseEnPaiement;
    }



    /**
     * Get centreCout
     *
     * @param TypeHeures $typeHeures
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCentreCout(TypeHeures $typeHeures = null)
    {
        $filter = function (CentreCout $centreCout) use ($typeHeures) {
            if ($typeHeures) {
                return $centreCout->typeHeuresMatches($typeHeures);
            } else {
                return true;
            }
        };

        return $this->centreCout->filter($filter);
    }



    /**
     * @return MiseEnPaiementListe
     */
    public function getMiseEnPaiementListe(\DateTime $dateMiseEnPaiement = null, Periode $periodePaiement = null)
    {
        $liste = new MiseEnPaiementListe($this);
        if ($dateMiseEnPaiement) $liste->setDateMiseEnPaiement($dateMiseEnPaiement);
        if ($periodePaiement) $liste->setPeriodePaiement($periodePaiement);

        return $liste;
    }



    /**
     * Get formuleResultat
     *
     * @return \Application\Entity\Db\FormuleResultat
     */
    public function getFormuleResultat()
    {
        return $this->formuleResultat;
    }



    /**
     * @retun boolean
     */
    public function isPayable()
    {
        $fr = $this->getFormuleResultat();

        return $fr->getTypeVolumeHoraire()->getCode() === TypeVolumeHoraire::CODE_REALISE
            && $fr->getEtatVolumeHoraire()->getCode() === EtatVolumeHoraire::CODE_VALIDE;
    }
}