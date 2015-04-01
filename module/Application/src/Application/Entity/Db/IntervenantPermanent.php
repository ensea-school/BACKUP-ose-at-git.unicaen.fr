<?php

namespace Application\Entity\Db;

/**
 * IntervenantPermanent
 */
class IntervenantPermanent extends Intervenant
{
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

        $this->modificationServiceDu = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
        $services = [];
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