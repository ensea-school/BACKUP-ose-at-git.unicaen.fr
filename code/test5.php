<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

$url = (string)$this->url('chargens');

?>

<button type="button" data-confirm="sür ?" data-b-title="Mon titre" class="btn btn-secondary pop-ajax tt" data-url="<?= $url ?>">
    pop-ajax
</button>
<script>

    $(function () {

    });

</script>


