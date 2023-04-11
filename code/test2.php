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


    function toast(message, severity) {
        const bgClasses = {
            info: 'bg-info',
            success: 'bg-success',
            warning: 'bg-warning',
            error: 'bg-danger'
        };
        const iconClasses = {
            info: 'info-circle',
            success: 'check-circle',
            warning: 'exclamation-circle',
            error: 'exclamation-triangle'
        };

        let toastContainer = document.getElementById('unicaen-vue-toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'unicaen-vue-toast-container';
            toastContainer.classList.add('toast-container', 'position-fixed', 'top-0', 'end-0', 'p-3');
            document.body.appendChild(toastContainer);
        }

        // Création de l'élément HTML pour le toast
        const toast = document.createElement('div');
        toast.classList.add('toast', 'show', 'text-white', bgClasses[severity] ? bgClasses[severity] : 'bg-secondary');
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');

        if (severity === 'error'){
            toast.setAttribute('style', 'width:100%');
        }

        const toastContent =
            '<button class="btn-close btn-close-white h5" style="float:right" data-bs-dismiss="toast" aria-label="Close"></button>' +
            '<i class="icon fas fa-' + iconClasses[severity] + '" style="float: left;font-size: 26pt;padding-left: .4rem;margin-top:.4rem;padding-right: 1rem;"></i>' +
            '<div class="toast-body">' + message + '  </div>';


        toast.innerHTML = toastContent;

        // Ajout du toast à l'élément du conteneur de toasts
        toastContainer.appendChild(toast);

        // Affichage du toast
        //const bsToast = new bootstrap.Toast(toast);
        //bsToast.show();

        // Masquage du toast si ce n'est pas une erreur
        if (severity !== 'error') {
            // setTimeout(() => {
            //     toast.classList.remove('show');
            // }, 3000);
        }
    }

    $(() => {
        toast('mon success est phénoménal et j\'ai envie d\'en parler tout le temps mais ce serait bien trop long à expliquer et patati et patatta', 'success');
        toast('Et voici une info à ne pas rater!!', 'info');
        toast('Warning', 'warning');
        toast('Erreur magistrale!!!', 'error');
    });

</script>
