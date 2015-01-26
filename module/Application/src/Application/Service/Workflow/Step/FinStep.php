<?php

namespace Application\Service\Workflow\Step;

/**
 * Description of DebutStep
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class FinStep extends Step
{
    public function __construct()
    {
        $this
                ->setLabels(['default' => "Fin du workflow"])
                ->setDescriptions(['default' => "Fin du workflow"])
                ->setCrossable(false);
    }
}