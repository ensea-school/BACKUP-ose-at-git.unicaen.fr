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
<div>
    <h2>SelectPicker</h2>

    <select class="selectpicker">
        <option>Test 1</option>
        <option>Lorem Ipsum</option>
        <option>Tets 5</option>
    </select>


</div>

<div>
    <h2>form-select</h2>

    <select class="form-select">
        <option>Test 1</option>
        <option>Lorem Ipsum</option>
        <option>Tets 5</option>
    </select>


</div>

<div>
    <h2>SelectPicker + form-select</h2>

    <select class="form-select selectpicker">
        <option>Test 1</option>
        <option>Lorem Ipsum</option>
        <option>Tets 5</option>
    </select>


</div>

<div>
    <h2>sans classe</h2>

    <select class="">
        <option>Test 1</option>
        <option>Lorem Ipsum</option>
        <option>Tets 5</option>
    </select>


</div>