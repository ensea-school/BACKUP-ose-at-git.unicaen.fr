<?php

namespace Application\Interfaces;

use Application\Entity\Db\FormuleResultat;

/**
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
interface FormuleResultatAwareInterface
{

    /**
     * Spécifie le résultat de formule concerné.
     *
     * @param FormuleResultat $formuleResultat
     * @return self
     */
    public function setFormuleResultat(FormuleResultat $formuleResultat);

    /**
     * Retourne le résultat de formule.
     *
     * @return FormuleResultat
     */
    public function getFormuleResultat();
}