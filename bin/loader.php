<?php

declare(strict_types=1);

use Laminas\Cli\ApplicationFactory;
use Laminas\Cli\ApplicationProvisioner;
use Laminas\Cli\ContainerResolver;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

$projectRoot = dirname(__DIR__);
$autoloadFile = $projectRoot.'/vendor/autoload.php';

if (file_exists($autoloadFile)) {
    require $autoloadFile;
}else{
    fwrite(STDERR, 'Cannot locate autoloader; please run "composer install"' . PHP_EOL);
    exit(1);
}

// Set the main application directory as the current working directory
chdir($projectRoot);

$app = (new ApplicationFactory())();
$definition = $app->getDefinition();
$output = new ConsoleOutput();
$containerNotFoundMessage = '';
$input = new ArgvInput();

try {
    $input->bind($definition);
} catch (\Symfony\Component\Console\Exception\RuntimeException $exception) {
    // Ignore validation issues as we did not yet have the commands definition
    // As we only need the `--container` option, we are good to go until it is passed *before* the first command argument
    // Symfony parses the `argv` in its direct order and raises an error when more arguments or options are passed
    // than described by the default definition.
}

try {
    $container = (new ContainerResolver($projectRoot))->resolve($input);
    $app = (new ApplicationProvisioner())($app, $container);
} catch (RuntimeException | InvalidArgumentException $exception) {
    // Usage information provided by the `ContainerResolver` should be passed to the CLI output
    die(sprintf('<error>%s</error>', $exception->getMessage()));
}

return $container;