<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Contrat;

/**
 * Règle métier déterminant si une date de retour signé peut être saisie pour un contrat/avenant.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutSaisirRetourContratRule extends \Application\Rule\AbstractRule
{
    use \Application\Service\Initializer\ContratServiceAwareTrait;
    use \Application\Entity\Db\Traits\IntervenantAwareTrait;

    const MESSAGE_NON_VALIDE = 'messageNonValide';

    /**
     * Message template definitions
     * @var array
     */
    protected $messageTemplates = [
        self::MESSAGE_NON_VALIDE => "%value% n'est pas encore validé.",
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
        $contratToString = $this->contrat->toString(true);

        if (!($validation = $this->contrat->getValidation())) {
            $this->message(self::MESSAGE_NON_VALIDE, $contratToString);
            return false;
        }

        return true;
    }

    public function isRelevant()
    {
        return $this->getIntervenant()->getStatut()->estVacataire();
    }
}