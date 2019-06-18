<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

$tags = [
    '7.0',
    '7.1 BETA 5',
    '7.1',
    '7.2 ALPHA',
    '8.0beta',
];
foreach ( $tags as $i => $tag ){
    $tag = strtolower($tag);
    if (false !== ($p = strpos($tag,'alpha'))){
        $tags[$i] = trim(substr($tag, 0, $p));
    }
    if (false !== ($p = strpos($tag,'beta'))){
        $tags[$i] = trim(substr($tag, 0, $p));
    }
}
$tags = array_unique($tags);
var_dump($tags);