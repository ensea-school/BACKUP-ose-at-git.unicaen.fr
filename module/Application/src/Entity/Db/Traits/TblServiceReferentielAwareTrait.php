<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TblServiceReferentiel;

/**
 * Description of TblServiceReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait TblServiceReferentielAwareTrait
{
    protected ?TblServiceReferentiel $entityDbTblServiceReferentiel;



    /**
     * @param TblServiceReferentiel|null $entityDbTblServiceReferentiel
     *
     * @return self
     */
    public function setEntityDbTblServiceReferentiel( ?TblServiceReferentiel $entityDbTblServiceReferentiel )
    {
        $this->entityDbTblServiceReferentiel = $entityDbTblServiceReferentiel;

        return $this;
    }



    public function getEntityDbTblServiceReferentiel(): ?TblServiceReferentiel
    {
        if (!$this->entityDbTblServiceReferentiel){
            $this->entityDbTblServiceReferentiel = \Application::$container->get(TblServiceReferentiel::class);
        }

        return $this->entityDbTblServiceReferentiel;
    }
}