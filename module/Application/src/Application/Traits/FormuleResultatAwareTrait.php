<?php

namespace Application\Traits;

use Application\Entity\Db\FormuleResultat;

/**
 * Description of FormuleResultatAwareTrait
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
trait FormuleResultatAwareTrait
{
    /**
     * @var FormuleResultat
     */
    protected $formuleResultat;

    /**
     * Spécifie le résultat de formule concerné.
     *
     * @param FormuleResultat $formuleResultat
     * @return self
     */
    public function setFormuleResultat(FormuleResultat $formuleResultat)
    {
        $this->formuleResultat = $formuleResultat;

        return $this;
    }

    /**
     * Retourne le résultat de formule.
     *
     * @return FormuleResultat
     */
    public function getFormuleResultat()
    {
        return $this->formuleResultat;
    }

}