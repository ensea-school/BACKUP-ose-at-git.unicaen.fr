<?php

namespace Application\ORM;


use Administration\Interfaces\ParametreEntityInterface;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Unicaen\Framework\Cache\CacheContainerTrait;
use Application\ORM\Event\Listeners\ParametreEntityListener;
use Application\Service\Traits\ContextServiceAwareTrait;
use Unicaen\Framework\Router\ParamFirewallInterface;
use Unicaen\Framework\User\UserManager;
use Intervenant\Service\IntervenantService;
use Laminas\Mvc\MvcEvent;

/**
 * Description of RouteEntitiesInjector
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class RouteEntitiesInjector implements ParamFirewallInterface
{
    use CacheContainerTrait;
    use ContextServiceAwareTrait;

    private array $entityParams = [];



    public function __construct(
        private readonly UserManager        $userManager,
        private readonly ContainerInterface $container,
        private readonly EntityManager      $entityManager,
    )
    {
        $this->entityParams = $this->getCacheContainer()->entityParams('makeEntityParams');
    }



    public function __invoke(MvcEvent $e)
    {
        $params = $e->getRouteMatch()->getParams();
        foreach ($params as $name => $value) {
            $errorMessage = null;
            $this->check($name, $value, null, $errorMessage);
            if ('intervenant' == $name) {
                $profile = $this->userManager->getProfile();
                if ($profile && $profile->getContext('intervenant')) {
                    $profile->setContext('intervenant', $value);
                }
            }
            $e->setParam($name, $value);
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

        $amd = $this->entityManager->getMetadataFactory()->getAllMetadata();
        foreach ($amd as $md) {
            $class                = $md->getName();
            $pos                  = strrpos($class, '\\');
            $param                = lcfirst(substr($class, $pos + 1));
            $entityParams[$param] = $class;
        }

        return $entityParams;
    }



    public function check(string $name, mixed &$value, mixed $constraint = null, ?string &$errorMessage): bool
    {
        switch ($name) {
            case 'intervenant':
                if ($value !== null) {
                    /** @var IntervenantService $serviceIntervenant */
                    $serviceIntervenant = $this->container->get(IntervenantService::class);
                    $value = $serviceIntervenant->getByRouteParam($value);
                }
                break;
            default:
                if (array_key_exists($name, $this->entityParams)) {
                    if (0 !== (int)$value) {
                        $repo  = $this->entityManager->getRepository($this->entityParams[$name]);
                        $value = $repo->find($value);
                        if ($value instanceof ParametreEntityInterface) {
                            $annee = $this->getServiceContext()->getAnnee();
                            if ($value->getAnnee() != $annee) {
                                $pel = new ParametreEntityListener();
                                $pel->setEntityManager($this->entityManager);
                                $value = $pel->entityAutreAnnee($value, $annee);
                            }
                        }
                    }
                }
                break;
        }

        return true;
    }


}