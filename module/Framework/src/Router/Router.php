<?php

namespace Framework\Router;

use Framework\Container\Autowire;
use Laminas\View\Helper\Url;
use Psr\Container\ContainerInterface;

class Router
{
    public Route $home;

    private Url $laminasUrl;

    private ?string $rootUrl = null;

    /** @var array|Route[] */
    private array $routes = [];


    private ?Route $currentRoute = null;



    public function __construct(
        private readonly ContainerInterface $container,

        #[Autowire(config: 'router/routes')]
        array                               $routes,

        #[Autowire(config: 'bjyauthorize/guards/UnicaenPrivilege\Guard\PrivilegeController')]
        array                               $guards,
    )
    {
        $this->laminasUrl = $container->get('ViewHelperManager')->get('url');

        /*if (is_array($guards)) {
            foreach( $guards as $guard) {

            }
        }*/

//        foreach( $this->routes as $name => $routeData){
//            $route = new Route($routeData);
//        }

        $this->loadRoutes($routes, null);
        //dump($this->routes);
    }



    private function loadRoutes(array $routes, ?Route $parent = null): void
    {
        foreach ($routes as $name => $route) {
            $fullName = $parent?->getName() ?? '';
            if ($fullName) {
                $fullName .= '/';
            }
            $fullName .= $name;

            $children = [];
            if (array_key_exists('child_routes', $route)) {
                $children = $route['child_routes'];
                unset($route['child_routes']);
            }

            $allowedTypes = [
                'Literal', 'literal', 'Laminas\Router\Http\Literal',
                'Segment', 'segment', 'Laminas\Router\Http\Segment',
            ];
            $type         = $route['type'];
            if (in_array($type, $allowedTypes)) {
                $this->routes[$fullName] = new Route($fullName, $route, $parent);

                if (!empty($children)) {
                    $this->loadRoutes($children, $this->routes[$fullName]);
                }
            } else {
                //var_dump($route);
            }
        }
    }



    public function url(?string $route = null, array $params = [], array $options = [], bool $reuseMatchedParams = false): string
    {
        if ($route) {
            $route = $this->getRoute($route);
            return $this->makeUrl($route, $params, $options, $reuseMatchedParams);
        } else {
            // @todo à éliminer
            return $this->laminasUrl->__invoke($route, $params);
        }
    }



    private function makeUrl(Route $route, array $params = [], array $options = [], bool $reuseMatchedParams = false): string
    {
        $uri = $route->getRoute();

        if (!empty($uri) && str_contains($uri, ':') && !empty($params)) {
            $uri = $this->makeUri($uri, $params, $reuseMatchedParams);
        }


        $root = '';
        if (($options['force_canonical'] ?? false) === true) {
            $root = $this->getRootUrl();
        }


        $query = '';
        if (array_key_exists('query', $options) && !empty($options['query'])) {
            $query .= '?';
            $first = true;
            foreach ($options['query'] as $key => $value) {
                if (!$first) {
                    $query .= '&';
                }
                $query .= $key . '=' . rawurlencode($value);
                $first = false;
            }
        }

        return $root . $uri . $query;
    }



    private function makeUri(string $uri, array $params, bool $inOptional = false)
    {
        // On s'occupe de la partie optionnelle
        while (str_contains($uri, '[')) {
            $start        = strpos($uri, '[');
            $end          = strpos($uri, ']', $start);
            $optionalPart = substr($uri, $start + 1, $end - $start - 1);

            $optionalPart = $this->makeUri($optionalPart, $params, true);
            if (str_contains($optionalPart, ':')) {
                // un paramètre facultatif n'est pas connu => on dégage
                $optionalPart = '';
            }
            $uri = substr_replace($uri, $optionalPart, $start, $end + 1);
        }

        // Puis on fait le reste
        foreach ($params as $name => $value) {
            if (str_contains($uri, ':' . $name)) {
                $uri = str_replace(':' . $name, rawurlencode($value), $uri);
            }
        }

        if (str_contains($uri, ':') && !$inOptional) {
            throw new \Exception('Des paramètres sont manquants et l\'url ' . $uri . ' ne peut pas être construite');
        }

        return $uri;
    }



    public function detectRoute(string $url): ?Route
    {
        foreach ($this->routes as $route) {
            if ($res = $this->routeMatch($url, $route)) {
                if ($res !== false) {
                    return $route;
                }
            }
        }
        return null;
    }



    public function routeMatch(string $url, Route $route): array|false
    {
        if ($route->isLiteral()) {
            $r = $route->getRoute();
            if (!str_ends_with($r, '/')) {
                $r .= '/';
            }
            $u = $this->uriFromUrl($url);
            if (!str_ends_with($u, '/')) {
                $u .= '/';
            }

            return $r === $u ? [] : false;
        }

        $uri = $this->uriFromUrl($url);

        $params = [];

        $def = $this->parseRouteDefinition($route->getRoute());
        foreach ($def as $part) {
            [$type, $value] = $part;
            switch ($type) {
                case 'literal':
                    if (str_starts_with($uri, $value)) {
                        $uri = substr($uri, strlen($value));
                    } else {
                        return false; // pas de match!
                    }
                    break;
                case 'parameter':
                    $slash              = strpos($uri, '/');
                    if (false === $slash) {
                        $parameterTestValue = $uri;
                    }else{
                        $parameterTestValue = substr($uri, 0, $slash);
                    }
                    $params[$value]     = rawurldecode($parameterTestValue);
                    $uri                = substr($uri, strlen($parameterTestValue));
                    break;
                case 'optional':
                    if ($uri == '') {
                        // on arrête là : on a tout l'obligatoire
                        return $params;
                    } else {
                        // on continue le parsing à l'intérieur de l'optional
                        foreach ($value as $opart) {
                            [$otype, $ovalue] = $opart;
                            switch ($otype) {
                                case 'literal':
                                    if (str_starts_with($uri, $ovalue)) {
                                        $uri = substr($uri, strlen($ovalue));
                                    } else {
                                        return false; // pas de match!
                                    }
                                    break;
                                case 'parameter':
                                    $slash              = strpos($uri, '/');
                                    if (false === $slash) {
                                        $parameterTestValue = $uri;
                                    }else{
                                        $parameterTestValue = substr($uri, 0, $slash);
                                    }

                                    $params[$ovalue]    = rawurldecode($parameterTestValue);
                                    $uri                = substr($uri, strlen($parameterTestValue));
                                    break;
                            }
                        }
                    }
                    break;
            }
        }

        if ($uri === ""){
            return $params;
        }else{
            return false; // il reste du non parsé => on jette
        }
    }



    protected function parseRouteDefinition($def)
    {
        $currentPos = 0;
        $length     = strlen($def);
        $parts      = [];
        $levelParts = [&$parts];
        $level      = 0;

        while ($currentPos < $length) {
            preg_match('(\G(?P<literal>[^:{\[\]]*)(?P<token>[:{\[\]]|$))', $def, $matches, 0, $currentPos);

            $currentPos += strlen($matches[0]);

            if (isset($matches['literal']) && $matches['literal'] !== '') {
                $levelParts[$level][] = ['literal', $matches['literal']];
            }

            if ($matches['token'] === ':') {
                if (
                    !preg_match(
                        '(\G(?P<name>[^:/{\[\]]+)(?:{(?P<delimiters>[^}]+)})?:?)',
                        $def,
                        $matches,
                        0,
                        $currentPos
                    )
                ) {
                    throw new Exception\RuntimeException('Found empty parameter name');
                }

                $levelParts[$level][] = [
                    'parameter',
                    $matches['name'],
                    $matches['delimiters'] ?? null,
                ];

                $currentPos += strlen($matches[0]);
            } elseif ($matches['token'] === '{') {
                if (!preg_match('(\G(?P<literal>[^}]+)\})', $def, $matches, 0, $currentPos)) {
                    throw new Exception\RuntimeException('Translated literal missing closing bracket');
                }

                $currentPos += strlen($matches[0]);

                $levelParts[$level][] = ['translated-literal', $matches['literal']];
            } elseif ($matches['token'] === '[') {
                $levelParts[$level][]   = ['optional', []];
                $levelParts[$level + 1] = &$levelParts[$level][count($levelParts[$level]) - 1][1];

                $level++;
            } elseif ($matches['token'] === ']') {
                unset($levelParts[$level]);
                $level--;

                if ($level < 0) {
                    throw new Exception\RuntimeException('Found closing bracket without matching opening bracket');
                }
            } else {
                break;
            }
        }

        if ($level > 0) {
            throw new Exception\RuntimeException('Found unbalanced brackets');
        }

        return $parts;
    }



    public function hasRoute(string $route): bool
    {
        return array_key_exists($route, $this->routes);
    }



    public function getRoute(string $name): Route
    {
        if (!$this->hasRoute($name)) {
            throw new \Exception("Route $name does not exist");
        }
        return $this->routes[$name];
    }



    /** @return array|Route[] */
    public function getRoutes(): array
    {
        return $this->routes;
    }



    public function getRootUrl(bool $withProtocol = true): string
    {
        if ($this->rootUrl) {
            if (!$withProtocol) {
                if (str_starts_with($this->rootUrl, 'http://')) {
                    return substr($this->rootUrl, strlen('http://'));
                }
                if (str_starts_with($this->rootUrl, 'https://')) {
                    return substr($this->rootUrl, strlen('https://'));
                }
            }
            return $this->rootUrl;
        }

        // 1. Détection du protocole (HTTP/HTTPS)
        if ($withProtocol) {
            $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
                || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
                || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on')
                || (!empty($_SERVER['HTTP_CF_VISITOR']) && strpos($_SERVER['HTTP_CF_VISITOR'], 'https') !== false);

            $protocol = $isHttps ? 'https://' : 'http://';
        } else {
            $protocol = '';
        }

        // 2. Détection du host (domaine)
        $host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'];

        return $protocol . $host;
    }



    public function setRootUrl(?string $rootUrl): Router
    {
        $this->rootUrl = $rootUrl;

        return $this;
    }



    public function getCurrentUri(): string
    {
        $uri = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'];
        $uri .= $_SERVER['REQUEST_URI'];

        if ($pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        $rootUrl = $this->getRootUrl(false);

        if (!str_starts_with($uri, $rootUrl)) {
            throw new \Exception('Erreur au niveau de la détection de l\'URL : la racine ne correspond pas');
        }

        return substr($uri, strlen($rootUrl));
    }



    public function getCurrentRoute(): ?Route
    {
        if (empty($this->currentRoute)) {
            $this->currentRoute = $this->detectRoute($this->getCurrentUri());
        }
        return $this->currentRoute;
    }



    public function uriFromUrl(string $url): string
    {
        $uri = $url;

        // On retire les paramètres GET
        if ($pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        $rootUrl = $this->getRootUrl();
        if (!str_starts_with($uri, $rootUrl)) {
            return $url;
        } else {
            return substr($uri, strlen($rootUrl));
        }
    }
}