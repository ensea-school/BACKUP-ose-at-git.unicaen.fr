<?php

namespace Application\ORM;


use Application\Cache\Traits\CacheContainerTrait;
use Application\Entity\Db\TypeAgrement;
use Application\Service\IntervenantService;
use Laminas\Mvc\MvcEvent;
use UnicaenApp\Service\EntityManagerAwareTrait;

/**
 * Description of RouteEntitiesInjector
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class RouteEntitiesInjector
{
    use CacheContainerTrait;
    use EntityManagerAwareTrait;


    public function __invoke(MvcEvent $e)
    {
        $entityParams = $this->getCacheContainer()->entityParams('makeEntityParams');
        $params       = $e->getRouteMatch()->getParams();
        foreach ($params as $name => $value) {
            switch ($name) {
                case 'intervenant':
                    /** @var IntervenantService $serviceIntervenant */
                    $serviceIntervenant = \Application::$container->get(IntervenantService::class);

                    /* @var $role \Application\Acl\Role */
                    $role   = $serviceIntervenant->getServiceContext()->getSelectedIdentityRole();
                    $entity = $serviceIntervenant->getByRouteParam($value);
                    if ($role && $role->getIntervenant()) {
                        if ($role->getIntervenant()->getCode() != $entity->getCode()) {
                            $entity = $role->getIntervenant(); // c'est l'intervenant du rôle qui prime
                        } else {
                            $role->setIntervenant($entity); // Si c'est la même personne, on lui donne sa fiche d'ID demandée
                        }
                    }
                    $e->setParam($name, $entity);
                break;
                case 'typeAgrementCode':
                    $repo = $this->getEntityManager()->getRepository(TypeAgrement::class);
                    $e->setParam('typeAgrement', $repo->findOneBy(['code' => $value]));
                break;
                default:
                    if (array_key_exists($name, $entityParams)) {
                        if (0 !== (int)$value) {
                            $repo = $this->getEntityManager()->getRepository($entityParams[$name]);
                            $e->setParam($name, $repo->find($value));
                        }
                    }
                break;
            }
        }
    }



    /**
     * Attention : méthode utilisée par le cache dans la méthode __invoke!!
     *
     * @return array
     */
    public function makeEntityParams(): array
    {
        $entityParams = [];

        $amd = $this->getEntityManager()->getMetadataFactory()->getAllMetadata();
        foreach ($amd as $md) {
            $class                = $md->getName();
            $pos                  = strrpos($class, '\\');
            $param                = lcfirst(substr($class, $pos + 1));
            $entityParams[$param] = $class;
        }

        return $entityParams;
    }
}