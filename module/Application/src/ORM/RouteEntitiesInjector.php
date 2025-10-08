<?php

namespace Application\ORM;


use Administration\Interfaces\ParametreEntityInterface;
use Agrement\Entity\Db\TypeAgrement;
use Framework\Cache\CacheContainerTrait;
use Application\ORM\Event\Listeners\ParametreEntityListener;
use Application\Service\Traits\ContextServiceAwareTrait;
use Framework\User\UserManager;
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

    public function __construct(
        private readonly UserManager $userManager,
    )
    {
    }



    public function __invoke(MvcEvent $e)
    {
        $entityParams = $this->getCacheContainer()->entityParams('makeEntityParams');
        $params       = $e->getRouteMatch()->getParams();
        foreach ($params as $name => $value) {
            switch ($name) {
                case 'intervenant':
                    /** @var IntervenantService $serviceIntervenant */
                    $serviceIntervenant = $e->getApplication()->getServiceManager()->get(IntervenantService::class);
                    $entity = $serviceIntervenant->getByRouteParam($value);

                    $profile = $this->userManager->getProfile();
                    if ($profile && $profile->getContext('intervenant')) {
                        $profile->setContext('intervenant', $entity);
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