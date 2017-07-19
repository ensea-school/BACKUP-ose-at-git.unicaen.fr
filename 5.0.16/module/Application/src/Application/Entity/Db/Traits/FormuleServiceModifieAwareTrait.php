<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\FormuleServiceModifie;

/**
 * Description of FormuleServiceModifieAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleServiceModifieAwareTrait
{
    /**
     * @var FormuleServiceModifie
     */
    private $formuleServiceModifie;





    /**
     * @param FormuleServiceModifie $formuleServiceModifie
     * @return self
     */
    public function setFormuleServiceModifie( FormuleServiceModifie $formuleServiceModifie = null )
    {
        $this->formuleServiceModifie = $formuleServiceModifie;
        return $this;
    }



    /**
     * @return FormuleServiceModifie
     */
    public function getFormuleServiceModifie()
    {
        return $this->formuleServiceModifie;
    }
}