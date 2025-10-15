<?php

namespace Application\ORM;


use Administration\Interfaces\ParametreEntityInterface;
use Doctrine\ORM\EntityManager;
use Intervenant\Entity\Db\Intervenant;
use Psr\Container\ContainerInterface;
use Unicaen\Framework\Cache\CacheContainerTrait;
use Application\ORM\Event\Listeners\ParametreEntityListener;
use Application\Service\Traits\ContextServiceAwareTrait;
use Unicaen\Framework\Params\ParamFirewallInterface;
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

    private ?string $paramClass = null;



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
            $this->check($name, $params, null, $errorMessage);
            if ('intervenant' == $name) {
                $profile = $this->userManager->getProfile();
                if ($profile && $profile->getContext('intervenant')) {
                    $profile->setContext('intervenant', $params[$name]);
                }
            }
            if ($this->paramClass) {
                $e->setParam($name, $params[$this->paramClass]);
                $e->getRouteMatch()->setParam($this->paramClass, $params[$this->paramClass]);
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

        $amd = $this->entityManager->getMetadataFactory()->getAllMetadata();
        foreach ($amd as $md) {
            $class                = $md->getName();
            $pos                  = strrpos($class, '\\');
            $param                = lcfirst(substr($class, $pos + 1));
            $entityParams[$param] = $class;
        }

        return $entityParams;
    }



    public function check(string $name, array &$params, mixed $constraint = null, ?string &$errorMessage): bool
    {
        $this->paramClass = null;
        switch ($name) {
            case 'intervenant':
                if ($params[$name] !== null) {
                    /** @var IntervenantService $serviceIntervenant */
                    $serviceIntervenant        = $this->container->get(IntervenantService::class);
                    $this->paramClass          = Intervenant::class;
                    $params[$this->paramClass] = $serviceIntervenant->getByRouteParam($params[$name]);
                }
                break;
            default:
                if (array_key_exists($name, $this->entityParams)) {
                    if (0 !== (int)$params[$name]) {
                        $this->paramClass          = $this->entityParams[$name];
                        $repo                      = $this->entityManager->getRepository($this->paramClass);
                        $params[$this->paramClass] = $repo->find($params[$name]);

                        if ($params[$this->paramClass] instanceof ParametreEntityInterface) {
                            $annee = $this->getServiceContext()->getAnnee();
                            if ($params[$this->paramClass]->getAnnee() != $annee) {
                                $pel = new ParametreEntityListener();
                                $pel->setEntityManager($this->entityManager);
                                $params[$this->paramClass] = $pel->entityAutreAnnee($params[$this->paramClass], $annee);
                            }
                        }
                    }
                }
                break;
        }

        return true;
    }


}