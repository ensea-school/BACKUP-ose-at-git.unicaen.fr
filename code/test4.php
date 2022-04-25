<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

if ($controller->getRequest()->isXmlHttpRequest()) {
    $filename = getcwd() . '/cache/testRunner';
    $content  = file_get_contents($filename);
    file_put_contents($filename, '');
    echo $content;

    return;
}

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
            url: '/unicaen-code/test4',
            data: {},
            success: function (data)
            {
                var endOfFlux = '<END-OF-FLUX />';
                var theEnd = false;
                if (data.substr(-endOfFlux.length) === endOfFlux) {
                    theEnd = true;
                    data = data.substr(0, data.length - endOfFlux.length);
                    $('.aff .chargement').hide();
                }
                $('.aff .body').append(data);
                if (!theEnd) {
                    setTimeout(affRes, 1000);
                }
            }
        });
    }

    $(function () {
        $('.aff').html('<div class="body"></div><div class="chargement"><span class="loading">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> Chargement en cours...</div>');
        setTimeout(affRes, 1000);
    });
</script>