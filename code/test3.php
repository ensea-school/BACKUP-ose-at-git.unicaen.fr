<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

use Application\Entity\Db\Intervenant;
use Mission\Entity\Db\Mission;
use Service\Entity\Db\TypeVolumeHoraire;

?>
<script>


    var data = {
        0: {id: 15, heures: 15},
        1: {id: 14, heures: 14},
        2: {id: 120, heures: 120}
    };

    console.log(data[Util.json.indexById(data, 120)]);


    var data2 = [
        {id: 15, heures: 15},
        {id: 14, heures: 14},
        {id: 120, heures: 120}
    ];

    console.log(data2[Util.json.indexById(data2, 120)]);

</script>
