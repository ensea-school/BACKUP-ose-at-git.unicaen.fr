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
    /**
     * @var FormuleResultat
     */
    private $formuleResultat;





    /**
     * @param FormuleResultat $formuleResultat
     * @return self
     */
    public function setFormuleResultat( FormuleResultat $formuleResultat = null )
    {
        $this->formuleResultat = $formuleResultat;
        return $this;
    }



    /**
     * @return FormuleResultat
     */
    public function getFormuleResultat()
    {
        return $this->formuleResultat;
    }
}