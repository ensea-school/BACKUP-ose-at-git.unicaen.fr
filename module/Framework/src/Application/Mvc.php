<?php

namespace Framework\Application;

use Laminas\Mvc\Controller\AbstractController;
use Laminas\Stdlib\StringUtils;
use Laminas\View\Model\JsonModel;
use Laminas\View\Renderer\JsonRenderer;
use Laminas\View\Renderer\PhpRenderer;
use Framework\Container\Container;
use Framework\Router\Router;
use Laminas\View\Model\ViewModel;
use Laminas\View\Renderer\RendererInterface;
use Psr\Container\ContainerInterface;
use UnicaenApp\View\Model\CsvModel;
use UnicaenApp\View\Renderer\CsvRenderer;
use UnicaenVue\View\Model\AxiosModel;
use UnicaenVue\View\Model\VueModel;
use UnicaenVue\View\Renderer\AxiosRenderer;
use UnicaenVue\View\Renderer\VueRenderer;

class Mvc
{
    private ContainerInterface $controllerContainer;
    private Router             $router;



    public function __construct(
        private readonly Container $container
    )
    {
        $this->controllerContainer = $this->container->get('ControllerManager');
        $this->router              = $this->container->get(Router::class);
    }



    public function start()
    {
        $route = $this->router->getCurrentRoute();

        $controller = $this->controllerContainer->get($route->getController());

        $action = $route->getAction();
        $actionMethod = $action.'Action';

        $controllerResponse = $controller->$actionMethod();

        $response = $this->completeControllerResponse($controller, $action, $controllerResponse);

        $renderer = $this->detectRenderer($response);

        $content = $renderer->render($response);

        dd($content);
        /**
        $layoutViewModel = new ViewModel([]);
        $layoutViewModel->setTemplate($this->detectLayout());
        $layoutViewModel->setVariable('content', $content);

        $phpRenderer = new PhpRenderer();
        echo $phpRenderer->render($layoutViewModel);
        */
    }



    private function completeControllerResponse(AbstractController $controller, ?string $action, mixed $controllerResponse): mixed
    {
        if (is_array($controllerResponse)) {
            $viewModel = new ViewModel();
            $viewModel->setVariables($controllerResponse);
            $controllerResponse = $viewModel;
        }

        if ($controllerResponse instanceof ViewModel) {
            if (!$controllerResponse->getTemplate()){
                $controllerResponse->setTemplate($this->detectTemplate($controller, $action));
            }
        }

        return $controllerResponse;
    }



    protected function detectRenderer(mixed $response): RendererInterface
    {
        switch (get_class($response)) {
            case ViewModel::class:
                return $this->container->get(PhpRenderer::class);

            case JsonModel::class:
                return $this->container->get(JsonRenderer::class);

            case AxiosModel::class:
                return $this->container->get(AxiosRenderer::class);

            case VueModel::class:
                return $this->container->get(VueRenderer::class);

            case CsvModel::class:
                return $this->container->get(CsvRenderer::class);

            default:
                throw new \Exception('Unexpected response type for ' . get_class($response));
        }
    }



    protected function detectTemplate(AbstractController $controller, ?string $action): string
    {
        $parts = explode('\\', $controller::class);
        array_pop($parts);
        $parts = array_diff($parts, ['Controller']);
        //strip trailing Controller in class name
        $parts[]    = $this->deriveControllerClass($controller::class);
        $controller = implode('/', $parts);

        $template = trim($controller, '/');

        $template = $this->inflectName($template);

        if ($action){
            $template .= '/' . $this->inflectName($action);
        }

        return $template;
    }



    protected function detectLayout(): string
    {
        /** @todo utiliser le plugin de ontrôleur layout() pour détecter le layout courant */
        return 'layout/layout';
    }



    protected function deriveControllerClass(string $controller): string
    {
        if (str_contains($controller, '\\')) {
            $controller = substr($controller, strrpos($controller, '\\') + 1);
        }

        if ((10 < strlen($controller))
            && (str_ends_with($controller, 'Controller'))
        ) {
            $controller = substr($controller, 0, -10);
        }

        return $controller;
    }



    protected function inflectName(string $name): string
    {
        if (StringUtils::hasPcreUnicodeSupport()) {
            $pattern     = ['#(?<=(?:\p{Lu}))(\p{Lu}\p{Ll})#', '#(?<=(?:\p{Ll}|\p{Nd}))(\p{Lu})#'];
            $replacement = ['-\1', '-\1'];
        } else {
            $pattern     = ['#(?<=(?:[A-Z]))([A-Z]+)([A-Z][a-z])#', '#(?<=(?:[a-z0-9]))([A-Z])#'];
            $replacement = ['\1-\2', '-\1'];
        }

        $name = preg_replace($pattern, $replacement, $name);
        return strtolower($name);
    }

}
