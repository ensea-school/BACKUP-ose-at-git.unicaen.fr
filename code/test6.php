<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

/** @var \Formule\Service\TestService $sft */
$sft = $container->get(\Formule\Service\TestService::class);

/** @var \Formule\Service\FormulatorService $formulator */
$formulator = $container->get(\Formule\Service\FormulatorService::class);

$bdd = OseAdmin::instance()->getBdd();

$minId = 226071;

$max = (int)$bdd->selectOne("SELECT count(*) CC FROM formule_test_intervenant fi WHERE fi.id > $minId", [], 'CC');

$sql = "SELECT ID FROM formule_test_intervenant fi WHERE fi.id > $minId ORDER BY ID";

$num = 0;
$parser = $bdd->selectEach($sql);
while($data = $parser->next()){
    $num++;
    $id = (int)$data['ID'];

    $fti = $sft->get($id);

    $formulator->calculer($fti);

    $sft->save($fti);
    $sft->getEntityManager()->clear();
    echo "Calcul effectué pour ".$id." - $num / $max\n";
}