<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */


?>
<template>
    <div>
        <button ref="button" class="btn btn-secondary" v-b-popover.hover="popoverContent">
            Afficher la popover
        </button>
    </div>
</template>

<script>
    export default {
        data()
        {
            return {
                popoverContent: 'Contenu de la popover'
            }
        }
    }
</script>