<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */

//select * from v_tbl_paiement where intervenant_id = 826844 AND service_referentiel_id = 21673

$stbl = $container->get(\UnicaenTbl\Service\TableauBordService::class);

$data = [//'INTERVENANT_ID' => '9290',
         'INTERVENANT_ID' => 9290,
         //'ANNEE_ID' => 2015,
];
//$data = [];

$stbl->calculer('piece_jointe', $data);
