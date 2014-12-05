<?php

namespace Application\Service\Workflow\Step;

/**
 * Description of DebutStep
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class DebutStep extends Step
{
    public function __construct()
    {
        $this
                ->setLabels(['default' => "Début du workflow"])
                ->setDescriptions(['default' => "Début du workflow"])
                ->setCrossable(true);
    }
}