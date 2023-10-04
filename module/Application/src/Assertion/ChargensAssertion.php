<?php

namespace Application\Assertion;

use Application\Acl\Role;
use Application\Entity\Db\Scenario;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\ScenarioServiceAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use UnicaenPrivilege\Assertion\AbstractAssertion;

// sous réserve que vous utilisiez les privilèges d'UnicaenAuth et que vous ayez généré votre fournisseur


/**
 * Description of ChargensAssertion
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ChargensAssertion extends AbstractAssertion
{
    use ScenarioServiceAwareTrait;
    use ContextServiceAwareTrait;

    const SCENARIO_EDITION = 'scenario-edition';





    /**
     * Exemple
     */
    protected function assertEntity(ResourceInterface $entity = null, $privilege = null)
    {
        $role = $this->getRole();
        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;

        // Si c'est bon alors on affine...
        switch (true) {
            case $entity instanceof Scenario:
                switch ($privilege) {
                    case self::SCENARIO_EDITION:
                        return $this->assertScenarioEdition($entity);
                }
            break;
            case $entity instanceof Structure:
                switch ($privilege) {
                    case self::SCENARIO_EDITION:
                        return $this->assertScenarioStructureEdition($entity);
                }
            break;
        }

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
        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        // Si c'est bon alors on affine...
        switch ($action) {
            case 'scenario-supprimer':
            case 'scenario-saisir':
                /** @var Scenario $scenario */
                $scenario = $this->getMvcEvent()->getParam('scenario');
                if ($scenario){
                    return $this->assertScenarioEdition($scenario);
                }else{
                    $structure = $this->getServiceContext()->getStructure();
                    if ($structure){
                        return $this->assertScenarioStructureEdition($structure);
                    }else{
                        return true;
                    }
                }


            break;
        }

        return true;
    }



    private function assertScenarioEdition(Scenario $scenario)
    {
        return $this->assertScenarioStructureEdition($scenario->getStructure());
    }



    private function assertScenarioStructureEdition(Structure $structure = null)
    {
        if ($structure) {
            return $this->getAcl()->isAllowed($this->getRole(), Privileges::getResourceId(Privileges::CHARGENS_SCENARIO_COMPOSANTE_EDITION));
        } else {
            return $this->getAcl()->isAllowed($this->getRole(), Privileges::getResourceId(Privileges::CHARGENS_SCENARIO_ETABLISSEMENT_EDITION));
        }
    }

}