<?php

namespace Application\Assertion;

use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges; // sous réserve que vous utilisiez les privilèges d'UnicaenAuth et que vous ayez généré votre fournisseur
use Application\Service\Traits\WorkflowServiceAwareTrait;
use UnicaenAuth\Assertion\AbstractAssertion;
use Zend\Permissions\Acl\Resource\ResourceInterface;


/**
 * Description of ContratAssertion
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ContratAssertion extends AbstractAssertion
{
    use WorkflowServiceAwareTrait;

    /**
     * Exemple
     */
    protected function assertEntity(ResourceInterface $entity = null, $privilege = null)
    {

//        switch (true) {
//            case $entity instanceof VotreEntite:
//                switch ($privilege) {
//                    case Privileges::VOTRE_PRIVILEGE: // Attention à bien avoir généré le fournisseur de privilèges si vous utilisez la gestion des privilèges d'UnicaenAuth
//                        return $this->assertVotreAssertion($role, $entity);
//                }
//                break;
//        }

        return true;
    }



    /**
     * @param string $controller
     * @param string $action
     * @param string $privilege
     *
     * @return boolean
     */
    protected function assertController($controller, $action = null, $privilege = null)
    {
        $intervenant = $this->getMvcEvent()->getParam('intervenant');

        if ($intervenant){
            $wfOk = $this->getServiceWorkflow()->getEtape(WfEtape::CODE_CONTRAT, $intervenant)->isAtteignable();
            if (!$wfOk) return false;
        }

        return true;
    }

}