<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */


?>
<div id="app-2">
  <span v-bind:title="message">
    Passez votre souris sur moi pendant quelques secondes
    pour voir mon titre lié dynamiquement !
  </span>
</div>

<script type="application/javascript">

    var app2 = new Vue({
        el: '#app-2',
        data: {
            message: 'Vous avez affiché cette page le ' + new Date().toLocaleString()
        }
    })

</script>