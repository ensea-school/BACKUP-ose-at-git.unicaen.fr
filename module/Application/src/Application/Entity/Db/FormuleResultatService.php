<?php

namespace Application\Entity\Db;

use Application\Entity\MiseEnPaiementListe;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * FormuleResultatService
 */
class FormuleResultatService implements ServiceAPayerInterface, ResourceInterface
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
     * @var \Application\Entity\Db\Service
     */
    private $service;

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
     * @return FormuleResultatService
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
        $element = $this->getService()->getElementPedagogique();
        if (! $element) return null;
        $result = $element->getCentreCoutEp($typeHeures->getTypeHeuresElement());
        if (false == $result) return null;
        $ccep = $result->first();
        if ($ccep instanceof CentreCoutEp){
            return $ccep->getCentreCout();
        }else{
            return null;
        }
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
     * Get Service
     *
     * @return \Application\Entity\Db\Service 
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @return Structure
     */
    public function getStructure()
    {
        $service = $this->getService();
        if ($service->getStructureEns())
            return $service->getStructureEns ();
        else
            return $service->getStructureAff ();
    }

    public function getResourceId()
    {
        return 'FormuleResultatService';
    }
}
