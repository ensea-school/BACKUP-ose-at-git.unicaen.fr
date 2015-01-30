<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * FormuleIntervenant
 */
class FormuleIntervenant
{
    /**
     * @var float
     */
    private $heuresServiceStatutaire;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $formuleServiceModifie;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $formuleService;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $formuleServiceReferentiel;

    /**
     * @var \Application\Entity\Db\Structure
     */
    private $structure;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->formuleServiceModifie = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formuleService = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formuleServiceReferentiel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set heuresServiceStatutaire
     *
     * @param float $heuresServiceStatutaire
     * @return FormuleIntervenant
     */
    public function setHeuresServiceStatutaire($heuresServiceStatutaire)
    {
        $this->heuresServiceStatutaire = $heuresServiceStatutaire;

        return $this;
    }

    /**
     * Get heuresServiceStatutaire
     *
     * @return float 
     */
    public function getHeuresServiceStatutaire()
    {
        return $this->heuresServiceStatutaire;
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return FormuleIntervenant
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @return FormuleIntervenant
     */
    public function setIntervenant(\Application\Entity\Db\Intervenant $intervenant = null)
    {
        $this->intervenant = $intervenant;

        return $this;
    }

    /**
     * Get intervenant
     *
     * @return \Application\Entity\Db\Intervenant 
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }

    /**
     * Add formuleServiceModifie
     *
     * @param \Application\Entity\Db\FormuleServiceModifie $formuleServiceModifie
     * @return FormuleIntervenant
     */
    public function addFormuleServiceModifie(\Application\Entity\Db\FormuleServiceModifie $formuleServiceModifie)
    {
        $this->formuleServiceModifie[] = $formuleServiceModifie;

        return $this;
    }

    /**
     * Remove formuleServiceModifie
     *
     * @param \Application\Entity\Db\FormuleServiceModifie $formuleServiceModifie
     */
    public function removeFormuleServiceModifie(\Application\Entity\Db\FormuleServiceModifie $formuleServiceModifie)
    {
        $this->formuleServiceModifie->removeElement($formuleServiceModifie);
    }

    /**
     * Get formuleServiceModifie
     *
     * @param Annee $annee
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFormuleServiceModifie( Annee $annee=null )
    {
        $filter = function( FormuleServiceModifie $formuleServiceModifie ) use ($annee) {
            if ($annee && $annee !== $formuleServiceModifie->getAnnee()) {
                return false;
            }
            return true;
        };
        return $this->formuleServiceModifie->filter($filter);
    }

    /**
     *
     * @param Annee $annee
     * @return FormuleServiceModifie
     */
    public function getUniqueFormuleServiceModifie( Annee $annee )
    {
        $result = $this->getFormuleServiceModifie($annee)->first();
        if (false === $result) $result = new FormuleServiceModifie;
        return $result;
    }

    /**
     * Add formuleService
     *
     * @param \Application\Entity\Db\FormuleService $formuleService
     * @return FormuleIntervenant
     */
    public function addFormuleService(\Application\Entity\Db\FormuleService $formuleService)
    {
        $this->formuleService[] = $formuleService;

        return $this;
    }

    /**
     * Remove formuleService
     *
     * @param \Application\Entity\Db\FormuleService $formuleService
     */
    public function removeFormuleService(\Application\Entity\Db\FormuleService $formuleService)
    {
        $this->formuleService->removeElement($formuleService);
    }

    /**
     * Get formuleService
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFormuleService()
    {
        return $this->formuleService;
    }

    /**
     * Add formuleServiceReferentiel
     *
     * @param \Application\Entity\Db\FormuleServiceReferentiel $formuleServiceReferentiel
     * @return FormuleIntervenant
     */
    public function addFormuleServiceReferentiel(\Application\Entity\Db\FormuleServiceReferentiel $formuleServiceReferentiel)
    {
        $this->formuleServiceReferentiel[] = $formuleServiceReferentiel;

        return $this;
    }

    /**
     * Remove formuleServiceReferentiel
     *
     * @param \Application\Entity\Db\FormuleServiceReferentiel $formuleServiceReferentiel
     */
    public function removeFormuleServiceReferentiel(\Application\Entity\Db\FormuleServiceReferentiel $formuleServiceReferentiel)
    {
        $this->formuleServiceReferentiel->removeElement($formuleServiceReferentiel);
    }

    /**
     * Get formuleServiceReferentiel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFormuleServiceReferentiel()
    {
        return $this->formuleServiceReferentiel;
    }

    /**
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     * @return FormuleIntervenant
     */
    public function setStructure(\Application\Entity\Db\Structure $structure = null)
    {
        $this->structure = $structure;

        return $this;
    }

    /**
     * Get structure
     *
     * @return \Application\Entity\Db\Structure 
     */
    public function getStructure()
    {
        return $this->structure;
    }
}
