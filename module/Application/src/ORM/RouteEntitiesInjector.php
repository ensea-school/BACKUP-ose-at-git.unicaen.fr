<?php

namespace Application\ORM;


use Administration\Interfaces\ParametreEntityInterface;
use Agrement\Entity\Db\TypeAgrement;
use Application\Cache\Traits\CacheContainerTrait;
use Application\ORM\Event\Listeners\ParametreEntityListener;
use Application\Service\Traits\ContextServiceAwareTrait;
use Intervenant\Service\IntervenantService;
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
    use ContextServiceAwareTrait;


    public function __invoke(MvcEvent $e)
    {
        $entityParams = $this->getCacheContainer()->entityParams('makeEntityParams');
        $params       = $e->getRouteMatch()->getParams();
        foreach ($params as $name => $value) {
            switch ($name) {
                case 'intervenant':
                    /** @var IntervenantService $serviceIntervenant */
                    $serviceIntervenant = $e->getApplication()->getServiceManager()->get(IntervenantService::class);

                    /* @var $role \Utilisateur\Acl\Role */
                    $role   = $serviceIntervenant->getServiceContext()->getSelectedIdentityRole();
                    $entity = $serviceIntervenant->getByRouteParam($value);
                    if ($role && $role->getIntervenant()) {
                        if ($role->getIntervenant()->getCode() != $entity->getCode()) {
                            $entity = $role->getIntervenant(); // c'est l'intervenant du rôle qui prime
                        } else {
                            $role->setIntervenant($entity); // Si c'est la même personne, on lui donne sa fiche d'ID demandée
                        }

                        $contextIntervenant = $this->getServiceContext()->getIntervenant();
                        $roleIntervenant    = $role->getIntervenant();

                        if ($contextIntervenant && $contextIntervenant !== $roleIntervenant) {
                            if ($contextIntervenant->getCode() === $roleIntervenant->getCode()) {
                                $this->getServiceContext()->setIntervenant($roleIntervenant);
                            }
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
                            $repo   = $this->getEntityManager()->getRepository($entityParams[$name]);
                            $entity = $repo->find($value);
                            if ($entity instanceof ParametreEntityInterface) {
                                $annee = $this->getServiceContext()->getAnnee();
                                if ($entity->getAnnee() != $annee) {
                                    $pel = new ParametreEntityListener();
                                    $pel->setEntityManager($this->getEntityManager());
                                    $entity = $pel->entityAutreAnnee($entity, $annee);
                                }
                            }
                            $e->setParam($name, $entity);
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