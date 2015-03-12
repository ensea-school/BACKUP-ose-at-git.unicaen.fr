<?php

namespace Application\Entity\Db;

use Application\Entity\MiseEnPaiementListe;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * FormuleResultatServiceReferentiel
 */
class FormuleResultatServiceReferentiel implements ServiceAPayerInterface, ResourceInterface
{
    use FormuleResultatTypesHeuresTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $miseEnPaiement;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $centreCout;

    /**
     * @var \Application\Entity\Db\FormuleResultat
     */
    private $formuleResultat;

    /**
     * @var \Application\Entity\Db\ServiceReferentiel
     */
    private $serviceReferentiel;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->miseEnPaiement = new \Doctrine\Common\Collections\ArrayCollection();
        $this->centreCout = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param \Application\Entity\Db\MiseEnPaiement $miseEnPaiement
     * @return FormuleResultatServiceReferentiel
     */
    public function addMiseEnPaiement(\Application\Entity\Db\MiseEnPaiement $miseEnPaiement)
    {
        $this->miseEnPaiement[] = $miseEnPaiement;

        return $this;
    }

    /**
     * Remove miseEnPaiement
     *
     * @param \Application\Entity\Db\MiseEnPaiement $miseEnPaiement
     */
    public function removeMiseEnPaiement(\Application\Entity\Db\MiseEnPaiement $miseEnPaiement)
    {
        $this->miseEnPaiement->removeElement($miseEnPaiement);
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
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCentreCout( TypeHeures $typeHeures=null )
    {
        $filter = function( CentreCout $centreCout ) use ($typeHeures) {
            if ($typeHeures){
                return $centreCout->typeHeuresMatches( $typeHeures );
            }else{
                return true;
            }
        };
        return $this->centreCout->filter($filter);
    }

    /**
     *
     * @param TypeHeures $typeHeures
     * @return CentreCout|null
     */
    public function getDefaultCentreCout( TypeHeures $typeHeures )
    {
        return null; // pas encore de centre de cout par défaut
    }

    /**
     * @return MiseEnPaiementListe
     */
    public function getMiseEnPaiementListe( \DateTime $dateMiseEnPaiement=null, Periode $periodePaiement=null )
    {
        $liste = new MiseEnPaiementListe( $this );
        if ($dateMiseEnPaiement) $liste->setDateMiseEnPaiement( $dateMiseEnPaiement );
        if ($periodePaiement)    $liste->setPeriodePaiement( $periodePaiement );
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
     * Get ServiceReferentiel
     *
     * @return \Application\Entity\Db\ServiceReferentiel 
     */
    public function getServiceReferentiel()
    {
        return $this->serviceReferentiel;
    }

    /**
     * @return Structure
     */
    public function getStructure()
    {
        return $this->getServiceReferentiel()->getStructure();
    }

    public function getResourceId()
    {
        return 'FormuleResultatServiceReferentiel';
    }
}