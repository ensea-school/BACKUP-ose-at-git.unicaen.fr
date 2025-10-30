<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 * @var $io         \Symfony\Component\Console\Style\SymfonyStyle
 */

use UnicaenCode\Util;

$guardProvider = $container->get(\Unicaen\Framework\Authorize\GuardProvider::class);
$ruleProvider  = $container->get(\Unicaen\Framework\Authorize\RuleProvider::class);

$privilegesClass = \Application\Provider\Privileges::class;


$assertions = [];


$guards = $guardProvider->getAll();
foreach ($guards as $controller => $actionGuards) {
    foreach ($actionGuards as $action => $rule) {
        if ($rule->assertion) {
            if (!isset($assertions[$rule->assertion])) {
                $assertions[$rule->assertion] = [
                    'assertController' => [],
                    'assertEntity'     => [],
                ];
            }

            $controllers                = &$assertions[$rule->assertion]['assertController'];
            $controllers[$controller][] = $action;
        }
    }
}


$rules = $ruleProvider->getAll();
foreach ($rules as $ressourceId => $rules) {
    foreach ($rules as $rule) {
        if ($rule->assertion) {
            if (!isset($assertions[$rule->assertion])) {
                $assertions[$rule->assertion] = [
                    'assertController' => [],
                    'assertEntity'     => [],
                ];
            }

            $entities               = &$assertions[$rule->assertion]['assertEntity'];
            $entities[$ressourceId] = $rule->privileges;
        }
    }
}


$aKeys = array_keys($assertions);
sort($aKeys);
$params = \UnicaenCode\Util::codeGenerator()->generer(
    [
        'assertion' => [
            'type'    => 'select',
            'label'   => 'Assertion à générer',
            'options' => $aKeys,
        ],
    ]
);

if (!$params['assertion']) {
    return;
}

$assertion = $aKeys[$params['assertion']];
$assertions = [$assertion => $assertions[$assertion]];

foreach ($assertions as $assertion => $asserts) {
    $php = "<?php\n\n";
    $php .= "namespace " . Util::classNamespace($assertion) . ";\n\n";

    $php .= "use Unicaen\Framework\Authorize\AbstractAssertion;\n";
    $php .= "use Unicaen\Framework\Authorize\UnAuthorizedException;\n";
    if (!empty($asserts['assertEntity'])) {
        $php .= "use $privilegesClass;\n";
    }

    foreach ($asserts['assertController'] as $controller => $null) {
        $php .= "use $controller;\n";
    }
    foreach ($asserts['assertEntity'] as $ressource => $null) {
        $php .= "use $ressource;\n";
    }

    $php .= "\n\n";
    $php .= "class " . Util::classClassname($assertion) . " extends AbstractAssertion\n";
    $php .= "{\n";

    if (!empty($asserts['assertController'])) {
        $php .= phpAssertController($asserts['assertController']);
    }

    if (!empty($asserts['assertEntity'])) {
        $php .= phpAssertEntity($asserts['assertEntity'], $privilegesClass);
    }

    $php .= "}\n";

    phpDump($php);
}


function phpAssertController(array $controllers): string
{
    $php = [];

    $php[] = 'protected function assertController(string $controller, ?string $action): bool';
    $php[] = '{';

    $php[] = "    switch (\$controller . '.' . \$action) {";

    foreach ($controllers as $controller => $actions) {
        foreach ($actions as $action) {
            $php[] = "        case " . Util::classClassname($controller) . "::class . '." . $action . "':";
            $php[] = "            return true;";
        }
    }
    $php[] = "    }";
    $php[] = "";
    $php[] = "    throw new UnAuthorizedException('Action de contrôleur ' . \$controller . ':' . \$action . ' non traitée');";
    $php[] = '}';

    return '   ' . implode("\n    ", $php) . "\n";
}


function phpAssertEntity(array $ressources, $privilegesClass): string
{
    $php = [];

    $php[] = 'protected function assertEntity(ResourceInterface $entity, ?string $privilege = null): bool';
    $php[] = '{';

    $php[] = "    switch (true) {";

    foreach ($ressources as $ressource => $privileges) {
        $php[] = "        case \$entity instanceof " . Util::classClassname($ressource) . ":";
        $php[] = "            switch (\$privilege) {";
        foreach ($privileges as $privilege) {
            $privName = Util::constantName($privilegesClass, $privilege);
            if ($privName) {
                $privName = Util::classClassname($privilegesClass) . '::' . $privName;
            } else {
                $privName = '"' . $privName . '"';
            }

            $php[] = "                case $privName:";
            $php[] = "                    return true;";
        }
        $php[] = "            }";
        $php[] = "            break;";
    }
    $php[] = "    }";
    $php[] = "";
    $php[] = "    throw new UnAuthorizedException('Action interdite pour la resource ' . \$entity->getResourceId() . ', privilège ' . \$privilege);";
    $php[] = '}';

    return '   ' . implode("\n    ", $php) . "\n";
}
