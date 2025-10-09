<?php

namespace Plafond\Assertion;

use Application\Provider\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Unicaen\Framework\Authorize\AbstractAssertion;
use Intervenant\Entity\Db\Intervenant;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;


/**
 * Description of PlafondAssertion
 *
 * @author UnicaenCode
 */
class PlafondAssertion extends AbstractAssertion
{
    use ContextServiceAwareTrait;

    /**
     * @param string $controller
     * @param string $action
     * @param string $privilege
     *
     * @return boolean
     */
    protected function assertController(string $controller, ?string $action): bool
    {
        $structure = $this->getMvcEvent()->getParam('structure');
        /* @var $structure Structure */

        // Si c'est bon alors on affine...
        switch ($action) {
            case 'index':
            case 'editer':
                return $this->assertStructure($structure);
                break;
        }

        return true;
    }



    protected function assertEntity(?ResourceInterface $entity = null, $privilege = null): bool
    {
        switch (true) {
            case $entity instanceof Intervenant:
                switch ($privilege) {
                    case Privileges::PLAFONDS_DEROGATIONS_EDITION:
                        return $this->assertIntervenant($entity);
                }
                break;
            case $entity instanceof Structure:
                switch ($privilege) {
                    case Privileges::PLAFONDS_CONFIG_STRUCTURE:
                        return $this->assertStructure($entity);
                }
                break;
        }

        return true;
    }



    protected function assertIntervenant(Intervenant $intervenant): bool
    {
        if ($intervenant->getStructure()) {
            return $this->assertStructure($intervenant->getStructure());
        }

        return true;
    }



    protected function assertStructure(Structure $structure): bool
    {
        if (!$this->getServiceContext()->getStructure()) return true;

        return $structure->inStructure($this->getServiceContext()->getStructure());
    }

}