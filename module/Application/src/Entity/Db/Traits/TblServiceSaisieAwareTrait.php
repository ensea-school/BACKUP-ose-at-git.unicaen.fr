<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TblServiceSaisie;

/**
 * Description of TblServiceSaisieAwareTrait
 *
 * @author UnicaenCode
 */
trait TblServiceSaisieAwareTrait
{
    protected ?TblServiceSaisie $entityDbTblServiceSaisie;



    /**
     * @param TblServiceSaisie|null $entityDbTblServiceSaisie
     *
     * @return self
     */
    public function setEntityDbTblServiceSaisie( ?TblServiceSaisie $entityDbTblServiceSaisie )
    {
        $this->entityDbTblServiceSaisie = $entityDbTblServiceSaisie;

        return $this;
    }



    public function getEntityDbTblServiceSaisie(): ?TblServiceSaisie
    {
        if (!$this->entityDbTblServiceSaisie){
            $this->entityDbTblServiceSaisie = \Application::$container->get(TblServiceSaisie::class);
        }

        return $this->entityDbTblServiceSaisie;
    }
}