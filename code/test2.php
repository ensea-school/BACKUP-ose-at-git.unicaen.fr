<?php

use Unicaen\OpenDocument\Calc;

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

?>

<button onclick="ts()">Suceess</button>
<button onclick="te()">Error</button>

<script>

    function ts()
    {
        unicaenVue.flashMessenger.toast('Mon message uh iuh iuhx iscuh sqidhf zihuf azif rhaoqh foaizuhf oqh foiazer hoiuzhq oiazuh oizhu iqduhf', 'success');
    }

    function te()
    {
        unicaenVue.flashMessenger.toast('Mon erreur', 'error');
    }

</script>
