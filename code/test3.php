<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */
?>
<div class="res">

</div>
<script type="text/javascript">

    Util.ajax = function (params) {

        if (params.type === undefined) {
            params.type = 'POST';
        }

        params.url = Util.url(params.url);

        // var getArgs = data ? $.param(data) : null;
        // return Url.getBase() + route + (getArgs ? '?' + getArgs : '');
        //
        // params.url = Url.getBase().

    };



    $(function () {
        console.clear();

        let route = 'mission/liste/:intervenant';
        let params = {intervenant: '1000000000'};
        //let query = {order: 'ASC'};
        let query = undefined;

        console.log(Util.url(route, params, query));
        /*
                $.ajax({
                    type: 'POST',
                    //url: '/mission/liste/:intervenant',
                    url: '/mission/liste/1000000000',
                    urlParams: {intervenant: 1000000000},
                    data: this.mission,
                    success: function (response) {
                        console.log(response);
                    }
                });
        */

    });
</script>