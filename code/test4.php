<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

exec('php ' . getcwd() . '/public/index.php UnicaenCode test6' . " > /dev/null &");

?>
<div class="aff">
    Affichage...
</div>
<script type="application/javascript">

    function affRes()
    {
        $.ajax({
            type: 'POST',
            url: '/unicaen-code/test6',
            data: {},
            success: function (data) {
                var endOfFlux = '<END-OF-FLUX />';
                var theEnd = false;
                if (data.substr(-endOfFlux.length) === endOfFlux) {
                    theEnd = true;
                    data = data.substr(0, data.length - endOfFlux.length);
                }
                if (!theEnd) {
                    data += '<span class="loading">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> Chargement en cours...';
                }
                $('.aff').html(data);
                if (!theEnd) {
                    setTimeout(affRes, 1000);
                }
            },
        });
    }

    $(function () {
        setTimeout(affRes, 1000);
    });
</script>