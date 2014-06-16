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
    protected $validiteDebut;

    /**
     * @var \DateTime
     */
    protected $validiteFin;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $serviceReferentiel;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $modificationServiceDu;

    /**
     * @var \Application\Entity\Db\Corps
     */
    protected $corps;

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
     * @param bool $softDelete
     */
    public function removeServiceReferentiel(\Application\Entity\Db\ServiceReferentiel $serviceReferentiel, $softDelete = true)
    {
        if ($softDelete && $serviceReferentiel instanceof HistoriqueAwareInterface) {
            $serviceReferentiel->setHistoDestruction(new \DateTime());
        }
        else {
            $this->serviceReferentiel->removeElement($serviceReferentiel);
        }
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
     * Add modificationServiceDu
     *
     * @param \Application\Entity\Db\ModificationServiceDu $modificationServiceDu
     * @return IntervenantPermanent
     */
    public function addModificationServiceDu(\Application\Entity\Db\ModificationServiceDu $modificationServiceDu)
    {
        $this->modificationServiceDu[] = $modificationServiceDu;

        return $this;
    }

    /**
     * Remove modificationServiceDu
     *
     * @param \Application\Entity\Db\ModificationServiceDu $modificationServiceDu
     * @param bool $softDelete
     */
    public function removeModificationServiceDu(\Application\Entity\Db\ModificationServiceDu $modificationServiceDu, $softDelete = true)
    {
        if ($softDelete && $modificationServiceDu instanceof HistoriqueAwareInterface) {
            $modificationServiceDu->setHistoDestruction(new \DateTime());
        }
        else {
            $this->modificationServiceDu->removeElement($modificationServiceDu);
        }
    }

    /**
     * Get modificationServiceDu
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
//    public function getModificationServiceDu()
//    {
//        return $this->modificationServiceDu;
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
            return $this->serviceReferentiel;
        }
        
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
     * @param bool $softDelete
     * @return self
     */
    public function removeAllServiceReferentiel(Annee $annee = null, $softDelete = true)
    {
        foreach ($this->getServiceReferentiel($annee) as $serviceReferentiel) {
            $this->removeServiceReferentiel($serviceReferentiel, $softDelete);
        }
        
        return $this;
    }

    /**
     * Get modificationServiceDu
     *
     * @param Annee $annee Seule année à retenir
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModificationServiceDu(Annee $annee = null)
    {
        if (null === $annee) {
            return $this->modificationServiceDu;
        }
        
        $p = function($item) use ($annee) {
            return $item->getAnnee()->getId() === $annee->getId();
        };
        $services = $this->modificationServiceDu->filter($p);
        
        return $services;
    }

    /**
     * Get modificationServiceDuToStrings
     *
     * @param Annee $annee Seule année à retenir
     * @return string[]
     */
    public function getModificationServiceDuToStrings(Annee $annee = null)
    {
        $services = array();
        foreach ($this->getModificationServiceDu($annee) as $sr) { /* @var $sr \Application\Entity\Db\ModificationServiceDu */
            $services[] = "" . $sr;
        }
        
        return $services;
    }

    /**
     * Remove all modificationServiceDu
     *
     * @param Annee $annee Seule année à retenir
     * @param bool $softDelete
     * @return self
     */
    public function removeAllModificationServiceDu(Annee $annee = null, $softDelete = true)
    {
        foreach ($this->getModificationServiceDu($annee) as $modificationServiceDu) {
            $this->removeModificationServiceDu($modificationServiceDu, $softDelete);
        }
        
        return $this;
    }
}