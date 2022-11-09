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

<button type="button" class="btn btn-secondary mod-ajax tt" data-url="<?= $url ?>">
    mod-ajax1
</button>
<a data-confirm="true" data-content="sür ?" data-title="Mon titre" class="btn btn-secondary mod-ajax tt" href="<?= $url ?>">
    mod-ajax2
</a>


<a class="mod-ajax" data-confirm="true" data-content="sür ?" data-title="Mon titre" href="<?= $url ?>">
    mod-ajax3
</a>

<a class="mod-ajax" id="p2"
   href="<?= $this->url('motif-non-paiement/supprimer', ['motifNonPaiement' => 1]) ?>"
   title="Supprimer le motif de non paiement"
   data-content="<p class='lead text-danger'><strong>Attention!</strong> Confirmez-vous cette suppression ?</p>"
   data-confirm="true"
   data-confirm-button="Oui"
   data-cancel-button="Non"
   data-submit-reload="true"
>
    <i class="fas fa-trash-can"></i>
</a>


<a class="mod-ajax" id="p1" data-confirm="true"
   xhref="<?= $this->url('motif-non-paiement/supprimer', ['motifNonPaiement' => 1]) ?>"
   title="Supprimer le motif de non paiement"
   data-content="<p class='lead text-danger'><strong>Attention!</strong> Confirmez-vous cette suppression ?</p>"

>Test
    de mod-ajax</a>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>
    <button onclick="f1()">Show</button>
    <button onclick="f2()">Hide</button>
    <button onclick="f3()">setContent</button>
    <input id="cc" type="text" value="<h1 class='page-header'>Mon titre</h1> mon nouveau contenu"/>
    <button onclick="f4()">getContent</button>
    <button onclick="f5()">setTitle</button>
    <input id="dd" type="text" value="mon nouveau titre"/>
    <button onclick="f6()">getTitle</button>
    <button onclick="f7()">shown</button>
    <script>

        $(function () {
            $('#p2').modAjax({
                show: function (event, popAjax) {
                    console.log('show');
                },
                hide: function (event, popAjax) {
                    console.log('hide');
                },
                change: function (event, popAjax) {
                    console.log('change');
                },
                submit: function (event, popAjax) {
                    console.log('submit');
                },
                error: function (event, popAjax) {
                    console.log('error');
                }
            });

        });


        function f1()
        {
            $('#p1').modAjax('show');
        }

        function f2()
        {
            $('#p1').modAjax('hide');
        }

        function f3()
        {
            $('#p1').modAjax('setContent', $('#cc').val());
        }

        function f4()
        {
            console.log($('#p1').modAjax('getContent'));
        }

        function f5()
        {
            $('#p1').modAjax('setTitle', $('#dd').val());
        }

        function f6()
        {
            console.log('getTitle');
        }

        function f7()
        {
            console.log($('#p1').modAjax('shown'));
        }

    </script>
</div>

<div class="intranavigator" id="intratest">
    <a href="<?= $url ?>">clic!</a>

</div>