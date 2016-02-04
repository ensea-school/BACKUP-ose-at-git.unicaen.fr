<?php

/**
 * @var $this \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 */

$dir = getcwd();
$ignored = [
    $dir.'/vendor',
    $dir.'/.svn',
    $dir.'/.idea',
    $dir.'/nbproject',
    $dir.'/data',
    $dir.'/composer.lock',
    $dir.'/deploy.log',
];

$res = parseCode( $dir, $ignored );
$res['sql'] = parseFileSql( $dir.'/data/bdd.sql');
$res['total'] = $res['lignes'] + $res['sql'];

echo '<h3>Nombre de lignes de codes et de fichiers dans OSE :</h3>';
var_dump($res);




$dir = getcwd().'/vendor/unicaen/unicaen-app';
$ignored = [
    $dir.'/vendor',
    $dir.'/.svn',
    $dir.'/.idea',
    $dir.'/nbproject',
    $dir.'/data',
    $dir.'/composer.lock',
    $dir.'/deploy.log',
];
$res = parseCode( $dir, $ignored );

echo '<h3>Nombre de lignes de codes et de fichiers dans UnicaenApp :</h3>';
var_dump($res);




$dir = getcwd().'/vendor/unicaen/unicaen-auth';
$ignored = [
    $dir.'/vendor',
    $dir.'/.svn',
    $dir.'/.idea',
    $dir.'/nbproject',
    $dir.'/data',
    $dir.'/composer.lock',
    $dir.'/deploy.log',
];
$res = parseCode( $dir, $ignored );

echo '<h3>Nombre de lignes de codes et de fichiers dans UnicaenAuth :</h3>';
var_dump($res);




$dir = getcwd().'/vendor/unicaen/unicaen-code';
$ignored = [
    $dir.'/vendor',
    $dir.'/.svn',
    $dir.'/.idea',
    $dir.'/nbproject',
    $dir.'/data',
    $dir.'/composer.lock',
    $dir.'/deploy.log',
];
$res = parseCode( $dir, $ignored );

echo '<h3>Nombre de lignes de codes et de fichiers dans UnicaenCode :</h3>';
var_dump($res);








function parseCode( $dir, $ignored=[] )
{
    $count = [
        'fichiers' => 0,
        'lignes' => 0,
    ];
    $allowedExts = [
        'php', 'json', 'js', 'css', 'xml', 'phtml', 'dist', 'sql', 'yml', 'po', 'htaccess', 'txt', 'html'
    ];
    $excluded = [
        '.',
        '..',
        'autoload_classmap.php',
        //        'Traits',
        //        'Interfaces',
    ];

    $i = scandir( $dir );

    foreach( $i as $fd ){
        if (!in_array($fd,$excluded)){
            $item = $dir.'/'.$fd;
            if (!in_array($item,$ignored)){
                if (is_file($item)){
                    $ext = strtolower(substr( $item, strrpos($item, '.')+1));
                    if (in_array($ext,$allowedExts)){
                        $count['fichiers'] += 1;
                        $count['lignes'] += parseFileLines( $item );
                    }
                }elseif(is_dir($item)){
                    $res = parseCode( $item, $ignored );
                    $count['fichiers'] += $res['fichiers'];
                    $count['lignes']   += $res['lignes'];
                }
            }
        }
    }
    return $count;
}

function parseFileLines( $filename )
{
    $res = 0;
    $lines = file($filename);
    foreach( $lines as $line ){
        if ('' != trim($line))
            $res++; // on supprime les lignes vides
    }
    return $res;
}

function parseFileSql( $filename )
{
    $res = 0;
    $lines = file($filename);
    foreach( $lines as $line ){
        if ('' != trim($line) && 0 !== strpos($line,'--')) $res++; // on supprime les lignes vides ou les commentaires
    }
    return $res;
}