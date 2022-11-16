<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

?>


<h1>Buttons</h1>

<h2>Taille normale</h2>

<button class="btn btn-primary">btn btn-primary</button>

<button class="btn btn-secondary">btn btn-secondary</button>

<button class="btn btn-warning">btn btn-warning</button>

<button class="btn btn-danger">btn btn-danger</button>

<h2>Taille SM</h2>

<button class="btn btn-sm btn-primary">btn btn-primary</button>

<button class="btn btn-sm btn-secondary">btn btn-secondary</button>

<button class="btn btn-sm btn-warning">btn btn-warning</button>

<button class="btn btn-sm btn-danger">btn btn-danger</button>

<h2>Taille XS</h2>

<button class="btn btn-xs btn-primary">btn btn-primary</button>

<button class="btn btn-xs btn-secondary">btn btn-secondary</button>

<button class="btn btn-xs btn-warning">btn btn-warning</button>

<button class="btn btn-xs btn-danger">btn btn-danger</button>


<h1>Alerts</h1>
<h2>Normales</h2>
<div class="alert alert-info">
    alert alert-info
</div>

<div class="alert alert-success">
    alert alert-success
</div>

<div class="alert alert-danger">
    alert alert-danger
</div>

<div class="alert alert-warning">
    alert alert-warning
</div>

<h2>Avec titres</h2>

<div class="alert alert-info">
    <h4>Titre</h4>
    alert alert-info
</div>

<div class="alert alert-success">
    <h4>Titre</h4>
    alert alert-success
</div>

<div class="alert alert-danger">
    <h4>Titre</h4>
    alert alert-danger
</div>

<div class="alert alert-warning">
    <h4>Titre</h4>
    alert alert-warning
</div>


<h1>Wells</h1>


<div class="card-well">
    <h2>Titre du well</h2>
    contenu du well
</div>


<h1>Cards</h1>

<div class="card bg-default">
    <div class="card-header">
        card-header default
    </div>
    <div class="card-body">
        card-body
    </div>
</div>

<div class="card bg-info">
    <div class="card-header">
        card-header info
    </div>
    <div class="card-body">
        card-body
    </div>
</div>

<div class="card bg-warning">
    <div class="card-header">
        card-header warning
    </div>
    <div class="card-body">
        card-body
    </div>
</div>

<div class="card bg-danger">
    <div class="card-header">
        card-header danger
    </div>
    <div class="card-body">
        card-body
    </div>
</div>


<div class="contrat panel panel-success" id="contrat-25014">
    <div class="panel-heading panel-heading-h3">
        <h3>Contrat n°25014 - UFR HSS</h3>
    </div>

    <div class="panel-body">
        body
    </div>

    <div class="panel-footer">
        footer
    </div>
</div>


<h1>Cartridge</h1>

<div class="cartridge gray bordered">
    <span><a href="#">UFR HSS</a></span>
    <span><a href="#">Master 2A Arts, Lettres et Civilisations</a></span>
    <span><a href="#">2MPROD2A - Développement de l'entreprise de production</a></span>
</div>


<h1>Tableaux</h1>

<h2>Taille standard</h2>

<table class="table table-bordered">
    <thead>
    <tr>
        <th style="width:5%">Civilité</th>
        <th style="width:15%">Nom</th>
        <th style="width:15%">Prénom</th>
        <th style="width:15%">Structure</th>
        <th style="width:15%">Statut</th>
        <th style="width:15%">Date de naissance</th>
        <th style="width:10%">N° personnel</th>
    </tr>
    </thead>
    <tbody class="table-hover">
    <tr>
        <td>Madame</td>
        <td>Test</td>
        <td>Sabine</td>
        <td>INSPE</td>
        <td>Vacataire</td>
        <td>06/10/1974</td>
        <td></td>
    </tr>
    </tbody>
</table>

<h2>Taille sm</h2>

<table class="table table-bordered table-sm">
    <thead>
    <tr>
        <th style="width:5%">Civilité</th>
        <th style="width:15%">Nom</th>
        <th style="width:15%">Prénom</th>
        <th style="width:15%">Structure</th>
        <th style="width:15%">Statut</th>
        <th style="width:15%">Date de naissance</th>
        <th style="width:10%">N° personnel</th>
    </tr>
    </thead>
    <tbody class="table-hover">
    <tr>
        <td>Madame</td>
        <td>Test</td>
        <td>Sabine</td>
        <td>INSPE</td>
        <td>Vacataire</td>
        <td>06/10/1974</td>
        <td></td>
    </tr>
    </tbody>
</table>

<h2>Taille xs</h2>

<table class="table table-bordered table-xs">
    <thead>
    <tr>
        <th style="width:5%">Civilité</th>
        <th style="width:15%">Nom</th>
        <th style="width:15%">Prénom</th>
        <th style="width:15%">Structure</th>
        <th style="width:15%">Statut</th>
        <th style="width:15%">Date de naissance</th>
        <th style="width:10%">N° personnel</th>
    </tr>
    </thead>
    <tbody class="table-hover">
    <tr>
        <td>Madame</td>
        <td>Test</td>
        <td>Sabine</td>
        <td>INSPE</td>
        <td>Vacataire</td>
        <td>06/10/1974</td>
        <td></td>
    </tr>
    </tbody>
</table>


<h1>Fenêtre Modale</h1>


<div class="modal" tabindex="1" style="position:relative;display:block">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Modal body text goes here.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>


<h1>Formulaires</h1>

<h2>form-select</h2>

<select class="form-select">
    <option selected>Open this select menu</option>
    <option value="1">One</option>
    <option value="2">Two</option>
    <option value="3">Three</option>
</select>

<h2>bootstrap-select</h2>
<select class="selectpicker">
    <option selected>Open this select menu</option>
    <option value="1">One</option>
    <option value="2">Two</option>
    <option value="3">Three</option>
</select>