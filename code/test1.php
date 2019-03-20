<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

$max = 10;

$ress = [];

for( $i = 1;$i<$max;$i++){
    for( $j = 1;$j<$max;$j++){
        $res = round($i / $j,6);
        if (!in_array($res, $ress)) {
            if ($res != round($res, 5)) {
                echo "$res: '$i/$j',<br />";
            }
            $ress[] = $res;
        }
    }
}



$t = [
    '1/3' => 0.333333,
    '1/6' => 0.166667,
    '1/7' => 0.142857,
    '1/9' => 0.111111,
    '2/3' => 0.666667,
    '2/7' => 0.285714,
    '2/9' => 0.222222,
    '3/7' => 0.428571,
    '4/3' => 1.333333,
    '4/7' => 0.571429,
    '4/9' => 0.444444,
    '5/3' => 1.666667,
    '5/6' => 0.833333,
    '5/7' => 0.714286,
    '5/9' => 0.555556,
    '6/7' => 0.857143,
    '7/3' => 2.333333,
    '7/6' => 1.166667,
    '7/9' => 0.777778,
    '8/3' => 2.666667,
    '8/7' => 1.142857,
    '8/9' => 0.888889,
    '9/7' => 1.285714,

];