<?php

namespace Application\Provider\Resource;

use Laminas\Permissions\Acl\Resource\ResourceInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use BjyAuthorize\Provider\Resource\ProviderInterface;
use UnicaenPrivilege\Entity\Db\AbstractPrivilege;

class ResourceProvider implements ProviderInterface
{
    use EntityManagerAwareTrait;


    protected array $resources = [];



    /**
     * @return \Laminas\Permissions\Acl\Resource\ResourceInterface[]|void
     */
    public function getResources()
    {
        if (empty($this->resources)) {
            $this->resources = [];

            $metas = $this->getEntityManager()->getMetadataFactory()->getAllMetadata();
            foreach ($metas as $meta) {
                $entityClass = $meta->getName();
                if (is_subclass_of($entityClass, ResourceInterface::class)) {
                    if (!str_starts_with($entityClass, 'Unicaen')) {
                        try {
                            $entity                                    = new $entityClass;
                            if (!$entity instanceof AbstractPrivilege) {
                                $this->resources[$entity->getResourceId()] = [];
                            }
                        } catch (\Exception $e) {

                        }
                    }
                }
            }
        }

        return $this->resources;
    }

}
