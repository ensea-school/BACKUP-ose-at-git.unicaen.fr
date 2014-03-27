<?php

namespace Application\Entity\Db;

/**
 * IntervenantPermanent
 */
class IntervenantPermanent extends Intervenant
{
    /**
     * @var \DateTime
     */
    private $validiteDebut;

    /**
     * @var \DateTime
     */
    private $validiteFin;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $serviceReferentiel;

    /**
     * @var \Application\Entity\Db\Corps
     */
    private $corps;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->serviceReferentiel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set validiteDebut
     *
     * @param \DateTime $validiteDebut
     * @return IntervenantPermanent
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
     * @return IntervenantPermanent
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
     * Add serviceReferentiel
     *
     * @param \Application\Entity\Db\ServiceReferentiel $serviceReferentiel
     * @return IntervenantPermanent
     */
    public function addServiceReferentiel(\Application\Entity\Db\ServiceReferentiel $serviceReferentiel)
    {
        $this->serviceReferentiel[] = $serviceReferentiel;

        return $this;
    }

    /**
     * Remove serviceReferentiel
     *
     * @param \Application\Entity\Db\ServiceReferentiel $serviceReferentiel
     */
    public function removeServiceReferentiel(\Application\Entity\Db\ServiceReferentiel $serviceReferentiel)
    {
        $this->serviceReferentiel->removeElement($serviceReferentiel);
    }

    /**
     * Get serviceReferentiel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
//    public function getServiceReferentiel()
//    {
//        return $this->serviceReferentiel;
//    }
// NB: méthode redéfinie plus bas.

    /**
     * Set corps
     *
     * @param \Application\Entity\Db\Corps $corps
     * @return IntervenantPermanent
     */
    public function setCorps(\Application\Entity\Db\Corps $corps = null)
    {
        $this->corps = $corps;

        return $this;
    }

    /**
     * Get corps
     *
     * @return \Application\Entity\Db\Corps 
     */
    public function getCorps()
    {
        return $this->corps;
    }


	/*******************************************************************************************************
	 *										Début ajout
	 *******************************************************************************************************/

    /**
     * Get serviceReferentiel
     *
     * @param Annee $annee Seule année à retenir
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getServiceReferentiel(Annee $annee = null)
    {
        if (null === $annee) {
            $annee = $this->getAnneeCriterion();
        }
        
        if (null === $annee) {
            return $this->serviceReferentiel;
        }
        
//        $services = new \Doctrine\Common\Collections\ArrayCollection();
//        foreach ($this->serviceReferentiel as $sr) { /* @var $sr \Application\Entity\Db\ServiceReferentiel */
//            if ($sr->getAnnee()->getId() === $annee->getId()) {
//                $services->add($sr);
//            }
//        }
        $p = function($item) use ($annee) {
            return $item->getAnnee()->getId() === $annee->getId();
        };
        $services = $this->serviceReferentiel->filter($p);
        
        return $services;
    }

    /**
     * Get serviceReferentielToStrings
     *
     * @param Annee $annee Seule année à retenir
     * @return string[]
     */
    public function getServiceReferentielToStrings(Annee $annee = null)
    {
        $services = array();
        foreach ($this->getServiceReferentiel($annee) as $sr) { /* @var $sr \Application\Entity\Db\ServiceReferentiel */
            $services[] = "" . $sr;
        }
        
        return $services;
    }

    /**
     * Remove all serviceReferentiel
     *
     * @param Annee $annee Seule année à retenir
     * @param \Application\Entity\Db\ServiceReferentiel $serviceReferentiel
     */
    public function removeAllServiceReferentiel(Annee $annee = null)
    {
        if (null === $annee) {
            $annee = $this->getAnneeCriterion();
        }
        
        foreach ($this->getServiceReferentiel($annee) as $serviceReferentiel) {
            $this->removeServiceReferentiel($serviceReferentiel);
        }
        
        return $this;
    }
}
