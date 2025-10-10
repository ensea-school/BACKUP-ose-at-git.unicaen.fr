<?php

namespace Chargens\Assertion;

use Application\Provider\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Chargens\Entity\Db\Scenario;
use Chargens\Service\ScenarioServiceAwareTrait;
use Unicaen\Framework\Authorize\AbstractAssertion;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;

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
    protected function assertEntity(string|ResourceInterface|null $entity = null, ?string $privilege = null): bool
    {
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



    protected function assertController(string $controller, ?string $action): bool
    {
        // Si c'est bon alors on affine...
        switch ($action) {
            case 'scenario-supprimer':
            case 'scenario-saisir':
                /** @var Scenario $scenario */
                $scenario = $this->getParam('scenario');
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
        }

        return true;
    }



    private function assertScenarioEdition(Scenario $scenario): bool
    {
        return $this->assertScenarioStructureEdition($scenario->getStructure());
    }



    private function assertScenarioStructureEdition(?Structure $structure = null): bool
    {
        $asserts = [];

        if ($structure) {
            $asserts[] = $this->authorize->isAllowedPrivilege(Privileges::CHARGENS_SCENARIO_COMPOSANTE_EDITION);
            if ($this->getServiceContext()->getStructure()){
                $asserts[] = $structure->inStructure($this->getServiceContext()->getStructure());
            }
        } else {
            $asserts[] = $this->authorize->isAllowedPrivilege(Privileges::CHARGENS_SCENARIO_ETABLISSEMENT_EDITION);
        }

        return $this->asserts($asserts);
    }

}