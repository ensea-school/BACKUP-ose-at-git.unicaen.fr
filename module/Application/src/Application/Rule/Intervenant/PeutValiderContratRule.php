<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Contrat;

/**
 * Règle métier déterminant si le contrat/avenant d'un intervenant peut être validé.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutValiderContratRule extends \Application\Rule\AbstractRule
{
    use \Application\Entity\Db\Traits\IntervenantAwareTrait;

    const MESSAGE_DEJA_VALIDE = 'messageDejaValide';

    /**
     * Message template definitions
     * @var array
     */
    protected $messageTemplates = [
        self::MESSAGE_DEJA_VALIDE => "%value%.",
    ];

    private $contrat;

    public function __construct(Intervenant $intervenant, Contrat $contrat)
    {
        parent::__construct();
        $this->setIntervenant($intervenant);
        $this->contrat = $contrat;
    }

    public function execute()
    {
        if (($validation = $this->contrat->getValidation())) {
            $contratToString = $this->contrat->toString(true);
            $dateValidation  = $validation->getHistoModification()->format(\Common\Constants::DATETIME_FORMAT);
            $this->message(
                    self::MESSAGE_DEJA_VALIDE,
                    "$contratToString est a déjà été validé le $dateValidation par {$validation->getHistoModificateur()}");
            return false;
        }

        return true;
    }

    public function isRelevant()
    {
        return true;
    }
}