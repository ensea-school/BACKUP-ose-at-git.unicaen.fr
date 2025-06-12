<?php

namespace Workflow\Resource;

use Intervenant\Entity\Db\Intervenant;
use Intervenant\Entity\Db\IntervenantAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use Lieu\Entity\Db\StructureAwareTrait;
use Workflow\Entity\Db\TblWorkflow;
use Workflow\Entity\Db\WfEtape;
use Workflow\Entity\WorkflowEtape;

class WorkflowResource implements ResourceInterface
{

    use IntervenantAwareTrait;
    use StructureAwareTrait;

    /**
     * @var WfEtape|WorkflowEtape|TblWorkflow|string
     */
    private $etape;



    /**
     * WorkflowResource constructor.
     *
     * @param WfEtape|TblWorkflow|WorkflowEtape|string $etape
     * @param Intervenant|null                         $intervenant
     * @param Structure|null                           $structure
     */
    public function __construct($etape, ?Intervenant $intervenant = null, ?Structure $structure = null)
    {
        if ($etape) $this->setEtape($etape);
        if ($intervenant) $this->setIntervenant($intervenant);
        if ($structure) $this->setStructure($structure);
    }



    /**
     * WorkflowResource constructor.
     *
     * @param WfEtape|TblWorkflow|WorkflowEtape|string $etape
     * @param Intervenant|null                         $intervenant
     * @param Structure|null                           $structure
     *
     * @return self
     */
    public static function create($etape, ?Intervenant $intervenant = null, ?Structure $structure = null)
    {
        $wr = new self($etape, $intervenant, $structure);

        return $wr;
    }



    /**
     * @return WfEtape|TblWorkflow|WorkflowEtape|string
     */
    public function getEtape()
    {
        return $this->etape;
    }



    /**
     * @param WfEtape|TblWorkflow|WorkflowEtape|string $etape
     *
     * @return WorkflowResource
     */
    public function setEtape($etape)
    {
        if ($etape instanceof WorkflowEtape) {
            $this->setIntervenant($etape->getIntervenant());
            $this->setStructure($etape->getStructure());
        } elseif ($etape instanceof TblWorkflow) {
            $this->setIntervenant($etape->getIntervenant());
            $this->setStructure($this->getStructure());
        }
        $this->etape = $etape;

        return $this;
    }



    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     */
    public function getResourceId()
    {
        return 'WorkflowResource';
    }

}