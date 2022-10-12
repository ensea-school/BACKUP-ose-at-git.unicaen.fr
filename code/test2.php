<?php

use Unicaen\OpenDocument\Calc;

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

$file = getcwd() . '/cache/t.xlsx';

$fc = new \Application\Model\FormuleCalcul($file, 'TEST');
$d  = $fc->getFormuleCells();

echo '<table class="table table-bordered">';
foreach ($d as $name => $cell) {
    echo '<tr><th>' . $name . '</th><td>' . htmlentities($cell->getFormule() ?? $cell->getValue()) . '</td></tr>';
}
echo '</table>';
