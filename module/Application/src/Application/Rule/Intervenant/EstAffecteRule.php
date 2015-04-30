<?php

namespace Application\Rule\Intervenant;

use Application\Rule\AbstractRule;
use Application\Traits\IntervenantAwareTrait;
use Application\Traits\StructureAwareTrait;

/**
 * Règle métier déterminant si un intervenant est affecté à une structure de niveau 2 précise ou à l'une de ses sous-structures.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class EstAffecteRule extends AbstractRule
{
    use IntervenantAwareTrait;
    use StructureAwareTrait;

    const MESSAGE_AFFECTATION = 'affectation';

    /**
     * Message template definitions
     * @var array
     */
    protected $messageTemplates = [
        self::MESSAGE_AFFECTATION => "L'intervenant n'est pas affecté à la structure suivante (ou à l'une de ses sous-structures) : %value%.",
    ];

    public function execute()
    {
        if ($this->getIntervenant()->getStructure() !== $this->getStructure()
                && $this->getIntervenant()->getStructure()->getParenteNiv2() !== $this->getStructure()->getParenteNiv2()) {
            $this->message(self::MESSAGE_AFFECTATION, $this->getStructure());
            return false;
        }

        return true;
    }

    public function isRelevant()
    {
        return null !== $this->getIntervenant()->getStructure();
    }
}
