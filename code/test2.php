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
<script>







    const a = {
        languages: ["Spanish", "Portuguese"],
        name: "Todd",
        adresse: {
            rue: '1 rue George Sand',
            cp: 14400,
            ville: 'Saint Martin des Entrées'
        },
        age: 20
    };

    const b = {
        languages: ["Français", "Portuguese"],
        married: true,
        adresse: {
            cp: 14400,
            ville: 'Bayeux',
            pays: 'France'
        },
    };


    const c = mergeDeep(a, b);

    console.log(c)

</script>
