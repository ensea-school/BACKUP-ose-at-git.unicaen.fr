<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */


?>
<script>

    $(function () {
        $('[data-bs-toggle="popover"]').popover()
    })

</script>


<button type="button" class="btn btn-lg btn-danger" data-bs-toggle="popover" title="Popover title"
        data-bs-content="And here's some amazing content. It's very engaging. Right?">Click to toggle popover
</button>
