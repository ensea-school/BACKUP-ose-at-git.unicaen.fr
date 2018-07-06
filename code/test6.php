<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

use Zend\Form\Form;

var_dump($_POST);

$horaire = isset($_POST['horaire']) ? $_POST['horaire'] : null;

$hd = $horaire;
//$hd = null;//'32/05/2017';

$form = new Form();
$form->add([
    'type'    => 'DateTime',
    'name'    => 'horaire',
    'options' => [
        'label'    => 'Horaire',
        'format'   => 'd/m/Y à H:i',
    ],
]);
$form->add([
    'name'       => 'submit',
    'type'       => 'Submit',
    'attributes' => [
        'value' => 'Enregistrer',
        'title' => "Enregistrer",
        'class' => 'btn btn-primary',
    ],
]);
$form->get('horaire')->setValue($hd);

echo $this->form($form);

