<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

$form = new \Laminas\Form\Form();


$form->add([
    'type'       => 'Date',
    'name'       => 'd1',
    'options'    => [
        'label' => 'Date de début',
    ],
    'attributes' => [
        'step' => '1',
    ],
]);

$form->add([
    'type'       => 'DateTime',
    'name'       => 'd2',
    'options'    => [
        'label' => 'Date de début',
    ],
    'attributes' => [
        'step' => '60',
    ],
]);

$form->add([
    'name'       => 'submit',
    'type'       => 'Submit',
    'attributes' => [
        'value' => 'Enregistrer',
        'class' => 'btn btn-primary',
    ],
]);
//$form->inpu

var_dump($_POST);

if (isset($_POST['d1'])) {
    $d1 = \Application\Filter\DateTimeFromString::run($_POST['d1']);
    var_dump($d1);
}

if (isset($_POST['d2'])) {
    $d2 = \Application\Filter\DateTimeFromString::run($_POST['d2']);
    var_dump($d2);
}


$form->get('d1')->setValue($d1?->format('Y-m-d\TH:i'));
$form->get('d2')->setValue($d2?->format('Y-m-d\TH:i'));


echo $this->form($form);