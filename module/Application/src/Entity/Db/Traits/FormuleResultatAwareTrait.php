<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\FormuleResultat;

/**
 * Description of FormuleResultatAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatAwareTrait
{
    protected ?FormuleResultat $entityDbFormuleResultat;



    /**
     * @param FormuleResultat|null $entityDbFormuleResultat
     *
     * @return self
     */
    public function setEntityDbFormuleResultat( ?FormuleResultat $entityDbFormuleResultat )
    {
        $this->entityDbFormuleResultat = $entityDbFormuleResultat;

        return $this;
    }



    public function getEntityDbFormuleResultat(): ?FormuleResultat
    {
        if (!$this->entityDbFormuleResultat){
            $this->entityDbFormuleResultat = \Application::$container->get('FormElementManager')->get(FormuleResultat::class);
        }

        return $this->entityDbFormuleResultat;
    }
}