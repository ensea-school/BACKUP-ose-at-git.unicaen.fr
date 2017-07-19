<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\TypeIntervenantAwareTrait;
use Application\Entity\Db\Traits\TypeVolumeHoraireAwareTrait;

/**
 * RegleStructureValidation
 */
class RegleStructureValidation
{
    use TypeVolumeHoraireAwareTrait;
    use TypeIntervenantAwareTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $priorite;

    /**
     * @var string
     */
    protected $message;



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * @param int $id
     *
     * @return RegleStructureValidation
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }



    /**
     * @return string
     */
    public function getPriorite()
    {
        return $this->priorite;
    }



    /**
     * @param string $priorite
     *
     * @return RegleStructureValidation
     */
    public function setPriorite($priorite)
    {
        $this->priorite = $priorite;

        return $this;
    }



    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }



    /**
     * @param string $message
     *
     * @return RegleStructureValidation
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

}
