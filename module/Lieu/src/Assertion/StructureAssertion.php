<?php

namespace Lieu\Assertion;

use Application\Provider\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Unicaen\Framework\Authorize\AbstractAssertion;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;


/**
 * Description of StructureAssertion
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class StructureAssertion extends AbstractAssertion
{
    use ContextServiceAwareTrait;

    protected function assertEntity(?ResourceInterface $entity = null, $privilege = null): bool
    {
        switch (true) {
            case $entity instanceof Structure:
                switch ($privilege) {
                    case Privileges::STRUCTURES_ADMINISTRATION_EDITION:
                        //case Privileges::STRUCTURES_ADMINISTRATION_VISUALISATION:
                        return $this->assertStructure($entity);
                }
            break;
        }

        return true;
    }



    protected function assertController(string $controller, ?string $action): bool
    {
        $structure = $this->getParam(Structure::class);
        /* @var $structure Structure */

        // Si c'est bon alors on affine...
        if ($structure) switch ($action) {
            case 'saisie':
            case 'delete':
                return $this->assertStructure($structure);
            break;
        }

        return true;
    }



    protected function assertStructure(Structure $structure): bool
    {
        $curStructure = $this->getServiceContext()->getStructure();

        if (!$curStructure) return true;

        return $structure->inStructure($curStructure);
    }

}