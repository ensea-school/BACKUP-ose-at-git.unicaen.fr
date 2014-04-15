<?php

namespace Application\Service;

use Application\Service\Context;

/**
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
interface ContextAwareInterface
{
    /**
     * Retourne le service fournissant le context global de l'application.
     *
     * @return Context
     */
    public function getContext();
}