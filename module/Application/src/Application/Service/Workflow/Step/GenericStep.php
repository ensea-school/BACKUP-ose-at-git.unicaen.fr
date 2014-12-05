<?php

namespace Application\Service\Workflow\Step;

/**
 * Classe générique d'étape de workflow.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class GenericStep extends Step
{
    /**
     * Constructeur.
     * 
     * @param string $label Label éventuel de l'étape
     * @param string $description Description éventuelle de l'étape
     */
    public function __construct($label = null, $description = null)
    {
        if ($label) {
            $this->setLabel($label);
        }
        if ($description) {
            $this->setDescription($description);
        }
        $this->setCrossable(true);
    }
}