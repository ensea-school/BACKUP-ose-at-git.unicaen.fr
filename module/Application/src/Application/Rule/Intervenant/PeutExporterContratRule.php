<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\Contrat;
use Application\Entity\Db\Intervenant;

/**
 * Règle métier déterminant si le contrat/avenant d'un intervenant peut être exporté (en PDF par ex).
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutExporterContratRule extends \Application\Rule\AbstractRule
{
    use \Application\Entity\Db\Traits\IntervenantAwareTrait;

    const MESSAGE_IMPOSSIBLE = 'messageImpossible';

    /**
     * Message template definitions
     * @var array
     */
    protected $messageTemplates = [
        self::MESSAGE_IMPOSSIBLE => "L'intervenant spécifié ne peut pas exporter ce contrat/avenant.",
    ];

    /**
     * @var Contrat
     */
    private $contrat;

    /**
     *
     * @param Intervenant $intervenant
     * @param Contrat $contrat
     */
    public function __construct(Intervenant $intervenant, Contrat $contrat)
    {
        parent::__construct();
        $this->setIntervenant($intervenant);
        $this->contrat = $contrat;
    }

    public function execute()
    {
        if ($this->contrat->getIntervenant() !== $this->getIntervenant()) {
            $this->message(self::MESSAGE_IMPOSSIBLE);
            return false;
        }

        return true;
    }

    public function isRelevant()
    {
        return true;
    }
}