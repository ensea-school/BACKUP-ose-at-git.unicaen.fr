<?php

namespace Workflow\Entity;

use Intervenant\Entity\Db\IntervenantAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use Lieu\Entity\Db\StructureAwareTrait;
use Workflow\Entity\Db\TblWorkflow;
use Workflow\Entity\Db\WfEtape;
use Workflow\Resource\WorkflowResource;

/**
 * WorkflowEtape
 */
class WorkflowEtape implements ResourceInterface
{
    use IntervenantAwareTrait;
    use StructureAwareTrait;

    /**
     * @var WfEtape
     */
    private $etape;

    /**
     * @var TblWorkflow[]
     */
    private $etapes = [];

    /**
     * @var string
     */
    private $url;

    /**
     * @var bool
     */
    private $atteignable;

    /**
     * @var bool
     */
    private $courante;

    /**
     * @var float
     */
    private $franchie;



    /**
     * @return WfEtape
     */
    public function getEtape()
    {
        return $this->etape;
    }



    /**
     * @param WfEtape $etape
     *
     * @return WorkflowEtape
     */
    public function setEtape(WfEtape $etape)
    {
        $this->etape = $etape;

        return $this;
    }



    /**
     * @return TblWorkflow[]
     */
    public function getEtapes()
    {
        return $this->etapes;
    }



    /**
     * @param Structure $structure
     *
     * @return TblWorkflow|null
     */
    public function getStructureEtape( Structure $structure )
    {
        $etapes = $this->getEtapes();
        foreach( $etapes as $etape ){
            if ($etape->getStructure() == $structure){
                return $etape;
            }
        }
        return null;
    }


    /**
     * @param TblWorkflow $etape
     *
     * @return WorkflowEtape
     */
    public function addEtape(TblWorkflow $etape)
    {
        $this->etapes[$etape->getId()] = $etape;

        return $this;
    }



    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }



    /**
     * @param string $url
     *
     * @return WfEtape
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }



    /**
     * @return boolean
     */
    public function isAtteignable()
    {
        return $this->atteignable;
    }



    /**
     * @param boolean $atteignable
     *
     * @return WfEtape
     */
    public function setAtteignable($atteignable)
    {
        $this->atteignable = $atteignable;

        return $this;
    }



    /**
     * @return boolean
     */
    public function isCourante()
    {
        return $this->courante;
    }



    /**
     * @param boolean $courante
     *
     * @return WfEtape
     */
    public function setCourante($courante)
    {
        $this->courante = $courante;

        return $this;
    }



    /**
     * @return float
     */
    public function getFranchie()
    {
        return $this->franchie;
    }



    /**
     * @return float
     */
    public function getObjectif()
    {
        $objectif = 0;
        foreach( $this->getEtapes() as $etape ){
            $objectif += $etape->getObjectif();
        }
        return $objectif;
    }



    /**
     * @param float $franchie
     *
     * @return WorkflowEtape
     */
    public function setFranchie($franchie)
    {
        $this->franchie = $franchie;

        return $this;
    }



    public function getResource()
    {
        return WorkflowResource::create($this);
    }



    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     */
    public function getResourceId()
    {
        return 'WorkflowEtape';
    }

}
