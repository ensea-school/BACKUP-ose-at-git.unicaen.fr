<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\IntervenantExterieur;

/**
 * Description of PossedeDossierRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PossedeDossierRule extends IntervenantRule
{
    const REASON = "Un vacataire non-BIATSS doit avoir saisi un dossier.";
    public function execute()
    {
        // un vacataire non-BIATSS doit avoir saisi un dossier
        $dossier = $this->getIntervenant()->getDossier();
        if (null === $dossier || !$dossier->getId()) {
            $this->setMessage(static::REASON);
            return false;
        }
        return true;
    }
    public function isRelevant()
    {
        return $this->getIntervenant()->getStatut()->estVacataireNonBiatss() && $this->getIntervenant() instanceof IntervenantExterieur;
    }
}
