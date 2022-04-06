<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */





class Flux
{
    private static ?self $instance = null;

    private string       $filename = '';

    private              $buffer;



    public function demarrer(string $filename): bool
    {
        $this->filename = $filename;

        if (!\Unicaen\Console\Console::isConsole()) {
            echo file_get_contents($this->filename);

            return false;
        }

        $this->buffer = fopen($this->filename, 'w');

        return true;
    }



    public function stopper()
    {
        fwrite($this->buffer, '<END-OF-FLUX />');
        fclose($this->buffer);
    }



    public function ecrire(string $data)
    {
        fwrite($this->buffer, $data);
    }



    public function alert(string $message, string $type)
    {
        $this->ecrire('<div class="alert alert-' . $type . '">' . $message . '</div>');
    }



    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }
}





$filename = getcwd() . '/cache/testRunner';
if (!Flux::getInstance()->demarrer($filename)) return;

$ws = $container->get(\Application\Service\WorkflowService::class);

$result = $ws->calculerTousTableauxBord(function (array $d) {
    Flux::getInstance()->ecrire('<h3>' . $d['tableau-bord'] . '</h3>');
}, function (array $d) {
    if ($d['result']) {
        $duree = round($d['duree'], 3) . ' secondes';
        Flux::getInstance()->ecrire('<div>Calcul effectué en ' . $duree . '</div>');
    } else {
        Flux::getInstance()->alert($d['exception']->getMessage(), 'danger');
    }
});

Flux::getInstance()->ecrire('Fin du calcul des tableaux de bord');
if ($result) {
    Flux::getInstance()->alert('Tout c\'est bien passé', 'success');
} else {
    Flux::getInstance()->alert('Attention : des erreurs ont été rencontrées!!', 'danger');
}

Flux::getInstance()->stopper();