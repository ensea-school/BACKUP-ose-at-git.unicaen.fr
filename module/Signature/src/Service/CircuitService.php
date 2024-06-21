<?php

namespace Signature\Service;

use Application\Service\AbstractEntityService;
use UnicaenSignature\Entity\Db\Signature;

/**
 * Description of CircuitService
 *
 * @author Antony LE COURTES <antony.lecourtes at unicaen.fr>
 */
class CircuitService
{

    use \UnicaenSignature\Service\SignatureServiceAwareTrait;


    public function getListeCircuitsSignature(): array
    {
        $listCircuit = $this->getSignatureService()->getSignatureFlows();

        return $listCircuit;
    }

}