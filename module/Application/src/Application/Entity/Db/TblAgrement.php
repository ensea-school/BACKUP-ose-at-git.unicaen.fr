<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\AgrementAwareTrait;
use Application\Entity\Db\Traits\AnneeAwareTrait;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Application\Entity\Db\Traits\StructureAwareTrait;
use Application\Entity\Db\Traits\TypeAgrementAwareTrait;
use Application\Resource\WorkflowResource;
use Zend\Permissions\Acl\Resource\ResourceInterface;


/**
 * TblAgrement
 */
class TblAgrement implements ResourceInterface
{
    use AnneeAwareTrait;
    use TypeAgrementAwareTrait;
    use IntervenantAwareTrait;
    use StructureAwareTrait;
    use AgrementAwareTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $obligatoire;

    private $codeIntervenant;

    private $dureeVie;

    private $anneeAgrement;

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
     * @return boolean
     */
    public function getObligatoire()
    {
        return $this->obligatoire;
    }

    public function getCodeIntervenant()
    {
        return $this->codeIntervenant;
    }

    /**
     * @return mixed
     */
    public function getDureeVie()
    {
        return $this->dureeVie;
    }

    /**
     * @return mixed
     */
    public function getAnneeAgrement()
    {
        return $this->anneeAgrement;
    }


    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     */
    public function getResourceId()
    {
        return 'TblAgrement';
    }



    /**
     * @return WorkflowResource
     */
    public function getResourceWorkflow()
    {
        $etape = $this->getTypeAgrement()->getCode();

        return WorkflowResource::create($etape, $this->getIntervenant(), $this->getStructure());
    }
}