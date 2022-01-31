<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

//\UnicaenCode\Util::run('NewService', ['class' => 'Intervenant\Service\ExempleService', 'awareTrait' => false, 'useGetter' => false, 'factory' => false]);


$rc = new ReflectionClass(\Application\View\Helper\AgrementViewHelper::class);
$rm = $rc->getMethod('__invoke');

$d = \UnicaenCode\Util::getMethodDocDeclaration($rm);

var_dump($d);

return;
$classes = \UnicaenCode\Util::introspection()->getClasses('Application');
//var_dump($classes);


foreach ($classes as $class => $file) {
    if (trait_exists($class)) {
        if ('AwareTrait' === substr($class, -10)) {

            $c  = new ReflectionClass($class);
            $ms = $c->getMethods();
            foreach ($ms as $m) {
                if (0 === strpos($m->getName(), 'get')) {
                    $refClass = null;
                    if ($m->getReturnType()) {
                        $refClass = $m->getReturnType()->getName();
                    } else {
                        $dc = $m->getDocComment();
                        if (false !== strpos($dc, '@return')) {
                            $deb  = strpos($dc, '@return') + 8;
                            $type = substr($dc, $deb);
                            $type = trim(substr($type, 0, strpos($type, "\n")));
                            $ns   = \UnicaenCode\Util::classNamespace($class);
                            if ('\Traits' === substr($ns, -7)) {
                                $ns = substr($ns, 0, -7);
                            }
                            $refClass = $ns . "\\" . $type;
                        }
                    }

                    if (!$refClass) {
                        echo '<h2>' . $class . '</h2>';
                        echo '<div class="bg-danger">Type de retour INCONNU</div>';
                    }
                }
            }
        }
    }
}