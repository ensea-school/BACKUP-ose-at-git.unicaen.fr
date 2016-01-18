<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\AgrementAwareTrait;
use Application\Entity\Db\Traits\AnneeAwareTrait;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Application\Entity\Db\Traits\StructureAwareTrait;
use Application\Entity\Db\Traits\TypeAgrementAwareTrait;
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

    /**
     * @var boolean
     */
    private $atteignable;



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



    /**
     * @param boolean $obligatoire
     *
     * @return TblAgrement
     */
    public function setObligatoire($obligatoire)
    {
        $this->obligatoire = $obligatoire;

        return $this;
    }



    /**
     * @return boolean
     */
    public function getAtteignable()
    {
        return $this->atteignable;
    }



    /**
     * @param boolean $atteignable
     *
     * @return TblAgrement
     */
    public function setAtteignable($atteignable)
    {
        $this->atteignable = $atteignable;

        return $this;
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
}