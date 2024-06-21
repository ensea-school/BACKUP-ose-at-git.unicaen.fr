<?php

namespace Signature\Controller;

use Application\Controller\AbstractController;
use Signature\Service\CircuitServiceAwareTrait;
use UnicaenSignature\Entity\Db\Signature;
use UnicaenSignature\Entity\Db\SignatureRecipient;
use UnicaenSignature\Service\SignatureConfigurationServiceAwareTrait;
use UnicaenSignature\Service\SignatureService;
use UnicaenSignature\Service\SignatureServiceAwareTrait;


/**
 * Description of CircuitController
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class CircuitController extends AbstractController
{

    use CircuitServiceAwareTrait;

    public function indexAction()
    {
        return [];
    }



    public function circuitsAction()
    {
        $circuits = $this->getServiceCircuit()->getListeCircuitsSignature();

        var_dump($circuits);

        die;
    }

}

