<?php

namespace Agrement\Entity\Db;

use Laminas\Permissions\Acl\Resource\ResourceInterface;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use Workflow\Resource\WorkflowResource;

/**
 * AgrementService
 */
class Agrement implements HistoriqueAwareInterface, ResourceInterface
{
    use HistoriqueAwareTrait;

    /**
     * @var string
     */
    private $url;

    /**
     * @var \DateTime
     */
    private $dateDecision;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var TypeAgrement
     */
    private $type;

    /**
     * @var Intervenant
     */
    private $intervenant;

    /**
     * @var Structure
     */
    private $structure;



    /**
     * Set url
     *
     * @param string $url
     *
     * @return Agrement
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }



    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }



    /**
     * Set dateDecision
     *
     * @param \DateTime $dateDecision
     *
     * @return Agrement
     */
    public function setDateDecision($dateDecision)
    {
        $this->dateDecision = $dateDecision;

        return $this;
    }



    /**
     * Get dateDecision
     *
     * @return \DateTime
     */
    public function getDateDecision()
    {
        return $this->dateDecision;
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
     * Set type
     *
     * @param \Agrement\Entity\Db\TypeAgrement $type
     *
     * @return Agrement
     */
    public function setType(\Agrement\Entity\Db\TypeAgrement $type = null)
    {
        $this->type = $type;

        return $this;
    }



    /**
     * Get type
     *
     * @return \Agrement\Entity\Db\TypeAgrement
     */
    public function getType()
    {
        return $this->type;
    }



    /**
     * Set intervenant
     *
     * @param \Intervenant\Entity\Db\Intervenant $intervenant
     *
     * @return Agrement
     */
    public function setIntervenant(\Intervenant\Entity\Db\Intervenant $intervenant = null)
    {
        $this->intervenant = $intervenant;

        return $this;
    }



    /**
     * Get intervenant
     *
     * @return \Intervenant\Entity\Db\Intervenant
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }



    /**
     * Set structure
     *
     * @param \Lieu\Entity\Db\Structure $structure
     *
     * @return Intervenant
     */
    public function setStructure(\Lieu\Entity\Db\Structure $structure = null)
    {
        $this->structure = $structure;

        return $this;
    }



    /**
     * Get structure
     *
     * @return \Lieu\Entity\Db\Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }



    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getDateDecision()->format(\Application\Constants::DATE_FORMAT);
    }



    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     */
    public function getResourceId()
    {
        return 'Agrement';
    }



    /**
     * @return WorkflowResource
     */
    public function getResourceWorkflow()
    {
        $etape = $this->getType()->getCode();

        return WorkflowResource::create($etape, $this->getIntervenant(), $this->getStructure());
    }
}
